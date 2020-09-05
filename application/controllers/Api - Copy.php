<?php
    class Api extends CI_Controller{
        function __construct(){
            parent::__construct();
            // $this->load->library('administration');
            $this->load->model('api_model','api');
            $this->load->library('upload');

            // if (!isset($_SERVER['PHP_AUTH_USER'])) {
            //     header('WWW-Authenticate: Basic realm="My Realm"');
            //     header('HTTP/1.0 401 Unauthorized');
            //     // echo 'Text to send if user hits Cancel button';
            //     // exit;
            // } else {
            //     echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
            //     echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
            // }

            $this->req_token();
            // $this->getBearerToken();

            header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
        }

        /** 
         * Get header Authorization
         * */
        // function getAuthorizationHeader(){
        //     $headers = null;
        //     if (isset($_SERVER['Authorization'])) {
        //         $headers = trim($_SERVER["Authorization"]);
        //     }
        //     else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        //         $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        //     } elseif (function_exists('apache_request_headers')) {
        //         $requestHeaders = apache_request_headers();
        //         // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        //         $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //         //print_r($requestHeaders);
        //         if (isset($requestHeaders['Authorization'])) {
        //             $headers = trim($requestHeaders['Authorization']);
        //         }
        //     }
        //     return $headers;
        // }
        /**
        * get access token from header
        * */
        // function getBearerToken() {
        //     $headers = $this->getAuthorizationHeader();
        //     // HEADER: Get the access token from the header
        //     if (!empty($headers)) {
        //         if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
        //             return $matches[1];
        //         }
        //     }
        //     return null;
        // }

        function req_token(){
            header('Cache-Control: no-cache, must-revalidate, max-age=0');
            $AUTH_USER = 'ibid';
            $AUTH_PASS = '123456';
            header('Cache-Control: no-cache, must-revalidate, max-age=0');

            $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
            if (!$has_supplied_credentials || $_SERVER['PHP_AUTH_USER'] != $AUTH_USER || $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS) {
                header('WWW-Authenticate: Basic realm="My Realm"');
                header('HTTP/1.0 401 Unauthorized');
                echo 'Invalid Auth';
                exit;
            }
        }

        function index(){ }

        public function login(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            // echo "<pre>";print_r($_POST);

            $config = [
                [
                        'field' => 'username',
                        'label' => 'Username',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Please enter username .',
                        ],
                ],
                [
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Password is Required',
                        ],
                ],
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $array['message'] = $this->form_validation->error_array();
                echo json_encode($array);
                exit;

            }else{
                $this->db->select('*');
                $this->db->where('email = "'.$this->input->post('username').'"');
                $this->db->or_where('phone = "'.$this->input->post('username').'"');
                $this->db->or_where('username = "'.$this->input->post('username').'"');
                $query1 = $this->db->get('members');

                // echo $this->db->last_query();
                if ($query1->num_rows() > 0) {
                    $member = $query1->row();

                    if($member){
                        if ($_POST['password'] == $member->password) {

                            if ($member->status == 'Enable') {
                                $member_token = $this->generate_token($member->id);
                                $member1 = $this->get_member_by_id($member->id);
                                $array['status'] = 200;
                                $array['title'] = 'Login Success!';
                                $array['message'] = $member1;
                                // $array['member_token'] = $member_token;
                                echo json_encode($array);
                                exit;
                            }else{
                                $array['status'] = 330;
                                $array['title'] = 'Login failed!';
                                $array['message'] = 'ERROR: You are not active, please contact our support center.';
                                echo json_encode($array);
                                exit;
                            }
                        }else{
                            $array['status'] = 320;
                            $array['title'] = 'Login failed!';
                            $array['message'] = 'Password incorrect';
                            echo json_encode($array);
                            exit;
                        }

                    }else{
                        $array['status'] = 310;
                        $array['title'] = 'Login failed!';
                        $array['message'] = 'Username incorrect';
                        echo json_encode($array);
                        exit;
                    }

                }else{
                    $array['status'] = 310;
                    $array['title'] = 'Login failed!';
                    $array['message'] = 'Username incorrect';
                    echo json_encode($array);
                    exit;
                }
            }

        }

        public function logout(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            
            if(isset($_POST['member_id'])){
                $this->generate_token($_POST['member_id']);
                $array['status'] = 200;
                $array['title'] = 'Success';
                $array['message'] = 'Logout Successfully';
                echo json_encode($array);
                exit;
            }else{
                $array['status'] = 310;
                $array['title'] = 'Error!';
                $array['message'] = 'Member Id Missing!!';
                echo json_encode($array);
                exit;
            }
        }

        public function generate_token($member_id){
            $str = random_strings(20);
            $data = array(
                "member_token" => $str
            );
            $this->db->where('id',$member_id);
            if($this->db->update('members',$data)){
                return $str;
            }
        }

        public function get_member_by_id($member_id){
            $this->db->select('*');
            $this->db->where('id',$member_id);
            $query1 = $this->db->get('members');
            $member = array();
            $member = $query1->row();
            return $member;
        }

        public function signup() {

            // echo '<pre>';print_r($request);
            // exit;

            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            // echo "<pre>";print_r($_POST);
            // exit;

            $config = [
                [
                        'field' => 'name',
                        'label' => 'Name',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Please enter name .',
                        ],
                ],
                [
                        'field' => 'username',
                        'label' => 'Username',
                        'rules' => 'required|callback_usernamecheck',
                        'errors' => [
                                'required' => 'username is required',
                        ],
                ],
                [
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'required|callback_emailcheck|callback_chk_valid_email',
                        'errors' => [
                                'required' => 'Email is Required',
                        ],
                ],
                [
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'required|min_length[6]',
                        'errors' => [
                                'required' => 'Password is Required',
                        ],
                ],
                [
                        'field' => 'confirm_password',
                        'label' => 'Confirm Password',
                        'rules' => 'required|matches[password]',
                        'errors' => [
                                'required' => 'Confirmn Password is Required',
                        ],
                ],
                [
                        'field' => 'phone',
                        'label' => 'Phone',
                        'rules' => 'required|numeric|callback_phonecheck',
                        'errors' => [
                                'required' => 'Phone is Required',
                                'numeric' => 'Enter Valid Phone Number',
                        ],
                ],
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $array['message'] = $this->form_validation->error_array();
                echo json_encode($array);
                exit;

            }else{
                $qid = "";
                if(isset($_POST['qid'])){
                    $qid = $_POST['qid'];
                }
                $signup_data = array(
                            'name' => $_POST['name'],
                            'username' => $_POST['username'],
                            'email' => $_POST['email'],
                            'password' => $_POST['password'],
                            'phone' => $_POST['phone'],
                            'qid' => $qid,
                            'profile_pic' => '',
                            'member_token' => '',
                            'notification' => 'off',
                );

                if($this->db->insert('members',$signup_data)){
                    $member_id = $this->db->insert_id();
                    $this->generate_token($member_id);

                    $array['status'] = 200;
                    $array['title'] = 'Success!';
                    echo json_encode($array);
                    exit;
                }
            }

        }

        public function addBid() {
            // echo '<pre>';print_r($request);
            // exit;
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            $this->token_check($_POST);
            // echo "<pre>";print_r($_POST);
            // exit;

            $config = [
                [
                        'field' => 'member_id',
                        'label' => 'Member',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Add Member Id.',
                        ],
                ],
                [
                        'field' => 'number_type',
                        'label' => 'Number Type',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Please Select Car Number Or Phone Number.',
                        ],
                ],
                [
                        'field' => 'number_subtype',
                        'label' => 'Email',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Please Select Types Of Number.',
                        ],
                ],
                [
                        'field' => 'upgrade_type',
                        'label' => 'Password',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Select Bid Upgrade Type Standered Or Premium',
                        ],
                ],
                [
                        'field' => 'number',
                        'label' => 'Confirm Password',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Please Enter Number',
                        ],
                ],
                [
                        'field' => 'starting_bid_amount',
                        'label' => 'Phone',
                        'rules' => 'required|numeric',
                        'errors' => [
                                'required' => 'Please Select Starting Bid Amount ',
                                'numeric' => 'Please Enter VAlid Amount',
                        ],
                ],
                [
                        'field' => 'duration_id',
                        'label' => 'Duration',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Please Select Duration ',
                        ],
                ],
                [
                        'field' => 'fee_id',
                        'label' => 'Fee',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Fee is Required Field ',
                        ],
                ],
                [
                    'field' => 'accept_payment_type',
                    'label' => 'Accept Payment Type',
                    'rules' => 'required',
                    'errors' => [
                            'required' => 'Please Select Payment Accept Mode ',
                    ],
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $array['message'] = $this->form_validation->error_array();
                echo json_encode($array);
                exit;

            }else{

                $this->db->select('*');
                $this->db->where('id',$this->input->post('duration_id'));
                $query1 = $this->db->get('durations');
                $duration = $query1->row();

                if($duration){
                    $bid_end_datetime = Date('Y-m-d H:i:s', strtotime('+'.$duration->no_of_days.' days'));
                }

                $coupon = "";
                if(isset($_POST['coupon'])){
                    $coupon = $_POST['coupon'];
                }

                $bid_data = array(
                            'member_id' => $_POST['member_id'],
                            'number_type' => $_POST['number_type'],
                            'number_subtype' => $_POST['number_subtype'],
                            'upgrade_type' => $_POST['upgrade_type'],
                            'number' => $_POST['number'],
                            'starting_bid_amount' => $_POST['starting_bid_amount'],
                            'duration' => $_POST['duration_id'],
                            'fee' => $_POST['fee_id'],
                            'coupon' => $coupon,
                            'accept_payment_type' => $_POST['accept_payment_type'],
                            'bid_end_datetime' => $bid_end_datetime,
                            'notification' => 'off',
                            'purchaser' => 0,
                            'status' => 'Disable',
                            'live' => 'N'
                );

                if($this->db->insert('bids',$bid_data)){
                    $bid_id = $this->db->insert_id();

                    $array['status'] = 200;
                    $array['title'] = 'Success!';
                    $array['bid_id'] = $bid_id;
                    echo json_encode($array);
                    exit;
                }
            }

        }

        public function getDurationList(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // echo $this->input->method(TRUE);
            $this->token_check($_POST);

            $this->db->select('id,no_of_days');
            $query1 = $this->db->get('durations');
            $res = array();
            if ($query1->num_rows() > 0) {
                $res = $query1->result();
            }

            echo json_encode($res);
        }

        public function getFeeList(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // echo $this->input->method(TRUE);
            $this->token_check($_POST);

            $this->db->select('id,TRUNCATE(start_amount,0) as start_amount,TRUNCATE(to_amount,0) as to_amount,TRUNCATE(fee_amount,0) as fee_amount');
            $query1 = $this->db->get('fees');
            $res = array();
            if ($query1->num_rows() > 0) {
                $res = $query1->result();
            }

            echo json_encode($res);
        }

        public function getFeeByAmount(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // echo $this->input->method(TRUE);
            $this->token_check($_POST);

            if(isset($_POST['amount'])){
                if (is_numeric($_POST['amount'])) {
                    $this->db->select('id,TRUNCATE(fee_amount,0) as fee_amount');
                    $this->db->where('start_amount <= '.$_POST['amount'].' AND to_amount >='.$_POST['amount']);
                    $query1 = $this->db->get('fees');
                    $res = array();
                    if ($query1->num_rows() > 0) {
                        $res = $query1->row();
                        echo json_encode($res);
                    }else{
                        $array['status'] = 404;
                        $array['title'] = 'Error!';
                        $array['message'] = 'Not Found';
                        echo json_encode($array);
                        exit;
                    }
                }else{
                    $array['status'] = 320;
                    $array['title'] = 'Error!';
                    $array['message'] = 'Invalid Amount';
                    echo json_encode($array);
                    exit;
                }
            }else{
                $array['status'] = 310;
                $array['title'] = 'Error!';
                $array['message'] = 'Amount Id Missing!!';
                echo json_encode($array);
                exit;
            }

            
        }

        public function token_check($post){
            // echo "<pre>";
            // print_r($post);
            $err = "Y";
            if(isset($post['member_token'])){
                $this->db->select('id');
                $this->db->where('member_token =',$post['member_token']);
                $query1 = $this->db->get('members');
                if ($query1->num_rows() > 0) {
                    $err = "N";
                    return true;
                }
            }
            
            if($err == "Y"){
                // header('WWW-Authenticate: Basic realm="My Realm"');
                header('HTTP/1.0 401 Unauthorized');
                echo 'Invalid Member Token';
                exit;
            }
        }

        public function emailcheck()
        {
            $this->db->select('*');
            $this->db->where('email =',$this->input->post('email'));
            $query1 = $this->db->get('members');
            if ($query1->num_rows() > 0) {
                $this->form_validation->set_message('emailcheck', 'Email Already Exists');
                return false;
            }else{
                return true;
            }
        }

        public function usernamecheck()
        {
            $this->db->select('*');
            $this->db->where('username =',$this->input->post('username'));
            $query1 = $this->db->get('members');
            if ($query1->num_rows() > 0) {
                $this->form_validation->set_message('usernamecheck', 'Username Already Exists');
                return false;
            }else{
                return true;
            }
        }

        public function phonecheck()
        {
            $this->db->select('*');
            $this->db->where('phone =',$this->input->post('phone'));
            $query1 = $this->db->get('members');
            if ($query1->num_rows() > 0) {
                $this->form_validation->set_message('phonecheck', 'Phone Number Already Exists');
                return false;
            }else{
                return true;
            }
        }

        public function chk_valid_email()
        {
            if(filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)){
                return true;
            }else{
                $this->form_validation->set_message('chk_valid_email', 'Enter Valid Email');
                return false;
            }
        }
        

    }
?>