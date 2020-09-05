<?php
    class Api extends CI_Controller{
        function __construct(){
            parent::__construct();
            // $this->load->library('administration');

            date_default_timezone_set('Asia/Qatar');

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
            
            // echo $this->getAuthorizationHeader();
            // echo "<pre>";print_r($_SERVER);
            // exit;

            // $this->req_token();
            // $this->getBearerToken();
            
            // $header = $this->input->request_headers();
            // print_r($header);

            // header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type,      Accept");
            header("Content-Type: application/json");
        }

        /** 
         * Get header Authorization
         * */
        function getAuthorizationHeader(){
            $headers = null;
            if (isset($_SERVER['Authorization'])) {
                $headers = trim($_SERVER["Authorization"]);
            }
            else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            } elseif (function_exists('apache_request_headers')) {
                $requestHeaders = apache_request_headers();
                // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                // print_r($requestHeaders);
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
        }
        /**
        * get access token from header
        * */
        function getBearerToken() {
            $headers = $this->getAuthorizationHeader();
            // HEADER: Get the access token from the header
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    return $matches[1];
                }
            }
            return null;
        }

        public function token_check($post = array()){
            // $err = "Y";
            // if(isset($post['member_token'])){
            //     $this->db->select('id');
            //     $this->db->where('member_token =',$post['member_token']);
            //     $query1 = $this->db->get('members');
            //     if ($query1->num_rows() > 0) {
            //         $err = "N";
            //         return true;
            //     }
            // }
            
            // if($err == "Y"){
            //     header('HTTP/1.0 401 Unauthorized');
            //     echo 'Invalid Member Token';
            //     exit;
            // }
            
            $bearer = $this->getBearerToken();
            // echo $bearer;
            // exit;

            $err = "Y";
            if($bearer != ""){
                $this->db->select('id');
                $this->db->where('member_token =',$bearer);
                $query1 = $this->db->get('members');
                if ($query1->num_rows() > 0) {
                    $err = "N";
                    return true;
                }
            }

            if($err == "Y"){
                header('HTTP/1.0 401 Unauthorized');
                // echo 'Invalid Auth';

                $array['status'] = 401;
                $array['title'] = 'Error!';
                $array['message'] = array("error"=> "Invalid Auth");
                echo json_encode($array);
                exit;
            }
        }

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

        public function update_firebase_token($member_id,$firebase_token){
            $data = array(
                "firebase_token" => $firebase_token
            );
            $this->db->where('id',$member_id);
            if($this->db->update('members',$data)){
                return $firebase_token;
            }
        }

        function index(){ }

        public function login(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            // echo "<pre>";print_r($_POST);
            if(!isset($_POST['firebase_token'])){
                $array['status'] = 340;
                $array['title'] = 'Error!';
                $array['message'] = array('error'=>'Firebase Token Missing');
                echo json_encode($array);
                exit;
            }

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
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                // $multiple = $this->form_validation->error_array();

                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                //  = 'error';
                // echo "<pre>";
                // print_r($new_err);
                // exit;

                $array['message'] = $new_err;
                echo json_encode($array);
                exit;

            }else{
                $this->db->select('*');
                $this->db->where('((email = "'.$this->input->post('username').'") OR (phone = "'.$this->input->post('username').'") OR (username = "'.$this->input->post('username').'")) ');
                // $this->db->where('password', $this->input->post('password'));
                $this->db->where('otp_varified','Y');
                $query1 = $this->db->get('members');

                // echo $this->db->last_query();
                if ($query1->num_rows() > 0) {
                    $member = $query1->row();

                    if($member){
                        if ($_POST['password'] == $member->password) {

                            if ($member->status == 'Enable') {
                                $member_token = $this->generate_token($member->id);
                                $firebase_token = $this->update_firebase_token($member->id,$this->input->post('firebase_token'));
                                $member1 = $this->get_member_by_id_select_fileds('id,name,username,email,phone,qid,notification,status,member_token,firebase_token,',$member->id);
                                $array['status'] = 200;
                                $array['title'] = 'Login Success!';
                                $array['message'] = $member1;
                                // $array['member_token'] = $member_token;
                                echo json_encode($array);
                                exit;
                            }else{
                                $array['status'] = 330;
                                $array['title'] = 'Erorr:';
                                $array['message'] = array('error'=>'You are not active, please contact our support center.');
                                echo json_encode($array);
                                exit;
                            }
                        }else{
                            $array['status'] = 320;
                            $array['title'] = 'Erorr: ';
                            $array['message'] = array('error'=>'Password incorrect.');
                            echo json_encode($array);
                            exit;
                        }

                    }else{
                        $array['status'] = 310;
                        $array['title'] = 'Erorr: ';
                        $array['message'] = array('error' =>'Username incorrect');
                        echo json_encode($array);
                        exit;
                    }

                }else{
                    $array['status'] = 310;
                    $array['title'] = 'Erorr: ';
                    $array['message'] = array('error' =>'Username incorrect');
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
                $array['message'] = array('success'=>'Logout Successfully');
                echo json_encode($array);
                exit;
            }else{
                $array['status'] = 310;
                $array['title'] = 'Error: ';
                $array['message'] = array('error'=> 'Member Id Missing!!');
                echo json_encode($array);
                exit;
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

        public function get_member_by_id_select_fileds($select,$member_id){
            $this->db->select($select);
            $this->db->where('id',$member_id);
            $query1 = $this->db->get('members');
            $member = array();
            $member = $query1->row();
            return $member;
        }

        public function get_bid_by_id($bid_id){
            $this->db->select('*');
            $this->db->where('id',$bid_id);
            $query1 = $this->db->get('bids');
            $res = array();
            $res = $query1->row();
            // echo "<pre>";print_r($res);
            // exit;
            return $res;
        }

        public function signup() {

            // echo '<pre>';print_r($request);
            // exit;

            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);


            // if(!isset($_POST['firebase_token'])){
            //     $array['status'] = 340;
            //     $array['title'] = 'Error:';
            //     $array['message'] = array('error'=>'Firebase Token Missing');
            //     echo json_encode($array);
            //     exit;
            // }

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
                        'rules' => 'required|numeric|min_length[8]|max_length[8]|callback_phonecheck',
                        'errors' => [
                                'required' => 'Phone is Required',
                                'numeric' => 'Enter Valid Phone Number',
                                'min_length' => 'Enter Valid Phone Number',
                                'max_length' => 'Enter Valid Phone Number',
                        ],
                ],
                [
                        'field' => 'firebase_token',
                        'label' => 'Firebase Token',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Firebase Token Missing',
                        ],
                ],
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
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
                            'profile_pic' => 'profile_default.png',
                            'member_token' => '',
                            'notification' => 'on',
                            'firebase_token' => $_POST['firebase_token']
                );

                if($this->db->insert('members',$signup_data)){
                    $member_id = $this->db->insert_id();
                    $this->generate_token($member_id);
                    // $this->sendOTP();
                    $array_res = array(
                        'otp_auth_id' => $member_id,
                        'phone' => $_POST['phone']
                    );

                    $array['status'] = 200;
                    // $array['title'] = 'Success!';
                    $array['title'] = '';
                    $array['message'] = $array_res;
                    echo json_encode($array);
                    exit;
                }
            }

        }

        public function sendOTP(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            $config = [
                [
                        'field' => 'otp_auth_id',
                        'label' => 'OTP AUTH ID',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'OTP AUTH ID is missing.',
                        ],
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{
                $member = $this->get_member_by_id($_POST['otp_auth_id']);

                $otp_sms = '1234';
                $member_up = array(
                        "otp" => $otp_sms
                );
                $this->db->where('id',$_POST['otp_auth_id']);
                $this->db->update('members',$member_up);

                $array['status'] = 200;
                $array['title'] = 'OTP Sent successfully';
                $array['message'] = (object) array();
                echo json_encode($array);
            }
        }

        public function resendOTP(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            $config = [
                [
                        'field' => 'otp_auth_id',
                        'label' => 'OTP AUTH ID',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'OTP AUTH ID is missing.',
                        ],
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{
                $member = $this->get_member_by_id($_POST['otp_auth_id']);

                $otp_sms = '1234';
                $member_up = array(
                    "otp" => $otp_sms
                );
                $this->db->where('id',$_POST['otp_auth_id']);
                $this->db->update('members',$member_up);

                $array['status'] = 200;
                $array['title'] = 'OTP Sent Again. Please Verify.';
                $array['message'] = (object) array();
                echo json_encode($array);
            }
        }

        public function verifyOTP(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            $config = [
                [
                        'field' => 'otp_auth_id',
                        'label' => 'OTP AUTH ID',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'OTP AUTH ID is missing.',
                        ],
                ],
                [
                        'field' => 'otp',
                        'label' => 'OTP',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'OTP is required',
                        ],
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{

                // check_same email,phone,or username at same time otp sent --- then only 1 need to varify..not another
                $error = "";
                $this->db->select('*');
                $this->db->where('id',$_POST['otp_auth_id']);
                $query1 = $this->db->get('members');
                if ($query1->num_rows() > 0) {
                    $otp_member = $query1->row();

                    $this->db->select('*');
                    $this->db->where('((email = "'.$otp_member->email.'") OR (phone = "'.$otp_member->phone.'") OR (username = "'.$otp_member->username.'"))');
                    $this->db->where('otp_varified','Y');
                    $this->db->where('id !=',$_POST['otp_auth_id']);
                    $query2 = $this->db->get('members');
                    // echo $this->db->last_query();
                    if ($query2->num_rows() > 0) {
                        // echo "111";
                        $error = "mismatch";
                    }
                }else{
                    // echo "222";
                    $error = "not_found";
                }

                if($error == 'mismatch' || $error == 'not_found'){
                    $array['status'] = 310;
                    $array['title'] = 'Error';
                    $array['message'] = array('error'=> 'Please Try Again');
                    echo json_encode($array);
                    exit;
                }
                // ============================
                
                //varify otp
                
                $this->db->select('*');
                // $this->db->where('phone', $_POST['phone']);
                $this->db->where('otp', $_POST['otp']);
                $this->db->where('id',$_POST['otp_auth_id']);
                $query1 = $this->db->get('members');
                if ($query1->num_rows() > 0) {
                    $member = $query1->row();
                }else{
                    $array['status'] = 310;
                    $array['title'] = 'Error';
                    $array['message'] = array('error'=> 'Wrong OTP');
                    echo json_encode($array);
                    exit;
                }

                // ===========================

                $update_data = array(
                    "otp_varified" => 'Y'
                );
                $this->db->where('id',$_POST['otp_auth_id']);
                if($this->db->update('members',$update_data)){
                    $member1 = $this->get_member_by_id_select_fileds('id,name,username,email,phone,qid,notification,status,member_token,firebase_token,',$_POST['otp_auth_id']);
                    $array['status'] = 200;
                    $array['title'] = 'Success';
                    // $array['message'] = array('success'=> 'Member Verified Successfully');
                    $array['message'] = $member1;
                    echo json_encode($array);
                    exit;
                }

            }
        }

        public function forgot_password(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            $config = [
                [
                    'field' => 'phone',
                    'label' => 'Phone',
                    'rules' => 'required',
                    'errors' => [
                            'required' => 'Phone Number is missing.',
                    ],
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{

                $this->db->select('*');
                $this->db->where('phone',$_POST['phone']);
                $this->db->where('otp_varified','Y');
                $query1 = $this->db->get('members');
                if ($query1->num_rows() > 0) {
                    $member = $query1->row();

                    // sending OTP
                    $otp_sms = '1234';
                    
                    $member_up = array(
                            "otp" => $otp_sms
                    );
                    $this->db->where('id',$member->id);
                    $this->db->update('members',$member_up);

                    $array['status'] = 200;
                    $array['title'] = 'Success';
                    $array['message'] = array('success'=> 'OTP Sent Successfully');
                    echo json_encode($array);
                    exit;

                    // $array['status'] = 200;
                    // $array['title'] = 'Account With Phone Number Is Available';
                    // $array['message'] = (object) array();
                    // echo json_encode($array);
                    // exit;
                }else{
                    $array['status'] = 310;
                    $array['title'] = 'Error';
                    $array['message'] = array('error'=> 'Account Not Exist With Your Phone Number');
                    echo json_encode($array);
                    exit;
                }
                
            }
        }

        public function forgot_otp_data(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            $config = [
                    [
                            'field' => 'otp',
                            'label' => 'OTP',
                            'rules' => 'required',
                            'errors' => [
                                    'required' => 'OTP is missing.',
                            ]
                    ],
                    [
                            'field' => 'phone',
                            'label' => 'Phone',
                            'rules' => 'required',
                            'errors' => [
                                    'required' => 'Phone Number is missing.',
                            ]
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
                    ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{

                $this->db->select('*');
                $this->db->where('phone', $_POST['phone']);
                $this->db->where('otp', $_POST['otp']);
                $this->db->where('otp_varified','Y');
                $query1 = $this->db->get('members');
                if ($query1->num_rows() > 0) {
                    $member = $query1->row();
                }else{
                    // verifyOTP
                    // if($_POST['otp'] != '1234'){
                        $array['status'] = 310;
                        $array['title'] = 'Error';
                        $array['message'] = array("error"=>'Wrong OTP');
                        echo json_encode($array);
                        exit;
                    // }
                }
                // =============

                $data_update = array(
                    'password' => $_POST['password'],
                    'otp' => ''
                );
                $this->db->where('phone',$_POST['phone']);
                if($this->db->update('members',$data_update)){
                    $array['status'] = 200;
                    $array['message'] = array("success"=>'Password Changed Successfully');
                    echo json_encode($array);
                    exit;
                }
            }
        }

        public function addBid() {
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
                        'label' => 'Number Subtype',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Please Select Types Of Number.',
                        ],
                ],
                [
                        'field' => 'upgrade_type',
                        'label' => 'Upgrade Type',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Select Bid Upgrade Type Standered Or Premium',
                        ],
                ],
                [
                        'field' => 'number',
                        'label' => 'Number',
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
                ],
                [
                    'field' => 'total',
                    'label' => 'Total',
                    'rules' => 'required|numeric',
                    'errors' => [
                            'required' => 'Total is missing',
                            'numeric' => 'Invalid Total',
                    ],
                ],
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';

                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;

            }else{

                if($_POST['upgrade_type'] != "standard" && $_POST['upgrade_type'] != "premium"){
                    $array['status'] = 400;
                    $array['title'] = 'Error';
                    $array['message'] = array("error"=>'upgrade_type must be standard or premium');
                    echo json_encode($array);
                    exit;
                }

                if((isset($_POST['coupon']) && !isset($_POST['discount'])) || (!isset($_POST['coupon']) && isset($_POST['discount']))){
                    $array['status'] = 310;
                    $array['title'] = 'Error';
                    $array['message'] = array("error"=>'Discount & coupon Both Required!');
                    echo json_encode($array);
                    exit;
                }

                $numbertype_str = "";
                $numbersubtype_str = "";
                $duration_str = "";
                $fee_str = "";

                $this->db->select('*');
                $this->db->where('id',$this->input->post('duration_id'));
                $query1 = $this->db->get('durations');
                $duration = $query1->row();

                if($duration){
                    // $bid_end_datetime = Date('Y-m-d H:i:s', strtotime('+'.$duration->no_of_days.' days'));
                    $duration_str = $duration->no_of_days;
                }
                // ---------------

                $this->db->select('nst.id,nst.sub_name,nt.name');
                $this->db->from('number_subtypes nst');
                $this->db->join('number_types nt','nt.id = nst.number_type_id','left');
                $this->db->where('nst.id',$this->input->post('number_subtype'));
                $this->db->group_by('nst.id');
                $query2 = $this->db->get();
                $nst = $query2->row();

                if($nst){
                    $numbertype_str = $nst->name;
                    $numbersubtype_str = $nst->sub_name;
                }
                
                // ---------------

                $this->db->select('f.id,f.fee_amount');
                $this->db->where('f.id',$this->input->post('fee_id'));
                $this->db->from('fees f');
                $query3 = $this->db->get();
                $fees_ar = $query3->row();

                if($fees_ar){
                    $fee_str = $fees_ar->fee_amount;
                }

                // ---------------

                $coupon = "";
                $discount = "";
                if(isset($_POST['coupon']) && isset($_POST['discount'])){
                    $coupon = $_POST['coupon'];
                    $discount = $_POST['discount'];
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
                            'numbertype_str' => $numbertype_str,
                            'numbersubtype_str' => $numbersubtype_str,
                            'duration_str' => $duration_str,
                            'fee_str' => $fee_str,
                            'discount' => $discount,
                            // 'bid_end_datetime' => $bid_end_datetime,
                            // 'notification' => 'off',
                            'purchaser' => 0,
                            'total' => $_POST['total'],
                            'status' => 'Disable',
                            'live' => 'Y',
                            'created_at' => date('Y-m-d H:i:s')
                );

                if($this->db->insert('bids',$bid_data)){
                    $bid_id = $this->db->insert_id();

                    $array['status'] = 200;
                    $array['title'] = 'Success!';
                    $array['message'] = (object) array();
                    echo json_encode($array);
                    exit;
                }
            }

        }

        public function BidSummary(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check($_POST);

            if(isset($_POST['bid_id'])){
                $this->db->select('b.id,TRUNCATE(b.starting_bid_amount,0) as starting_bid_amount,b.upgrade_type,d.no_of_days as duration,TRUNCATE(f.fee_amount,0) as fee,TRUNCATE(f.fee_amount,0) as total,ns.sub_name');
                $this->db->join('number_subtypes ns','ns.id=b.number_subtype','left');
                $this->db->join('durations d','d.id=b.duration','left');
                $this->db->join('fees f','f.id=b.fee','left');
                $this->db->where('b.id',$_POST['bid_id']);
                $this->db->from('bids b');
                $query1 = $this->db->get();
                $res = array();
                if ($query1->num_rows() > 0) {
                    $res = $query1->row();
                }
                echo json_encode($res);
            }else{
                $array['status'] = 310;
                $array['title'] = 'Error!';
                $array['message'] = 'Bid Id Missing!!';
                echo json_encode($array);
                exit;
            }
        }

        public function confirmBid(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check($_POST);

            if(isset($_POST['bid_id'])){
                $data = array(
                    "live" => 'Y',
                    "status" => 'Enable',
                );
                $this->db->where('id',$_POST['bid_id']);
                if($this->db->update('bids',$data)){
                    $array['status'] = 200;
                    $array['title'] = 'Success';
                    $array['message'] = 'Bid Confirm Successfully';
                    echo json_encode($array);
                    exit;
                }
            }else{
                $array['status'] = 310;
                $array['title'] = 'Error!';
                $array['message'] = 'Bid Id Missing!!';
                echo json_encode($array);
                exit;
            }
        }

        public function getNumbersList(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // echo $this->input->method(TRUE);
            $this->token_check($_POST);

            $this->db->select('id,name');
            $this->db->where('status','Enable');
            $query1 = $this->db->get('number_types');
            $res = array();
            if ($query1->num_rows() > 0) {
                $array1 = array();
                $array1 = $query1->result();
                
                foreach($array1 as $key_m => $val_m){
                    // $array1->sub_types = (object) array();
                    $this->db->select('id,sub_name');
                    $this->db->where('status','Enable');
                    $this->db->where('number_type_id',$val_m->id);
                    $query2 = $this->db->get('number_subtypes');
                    if ($query2->num_rows() > 0) {
                        $res2 = $query2->result();
                        $array1[$key_m]->sub_types =  $res2;
                    }
                }

                // $res = (object) array();
                $res = $array1;
            }

            $array['status'] = 200;
            $array['title'] = 'Success';
            $array['message'] = $res;
            echo json_encode($array);
            exit;
        }

        public function getDurationList(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // echo $this->input->method(TRUE);
            $this->token_check($_POST);

            $this->db->select('id,no_of_days');
            $this->db->where('status','Enable');
            $query1 = $this->db->get('durations');
            $res = array();
            if ($query1->num_rows() > 0) {
                $res = $query1->result();
            }

            $array['status'] = 200;
            $array['title'] = 'Success';
            $array['message'] = $res;
            echo json_encode($array);
        }

        public function getFeeList(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // echo $this->input->method(TRUE);
            $this->token_check($_POST);

            $this->db->select('id,TRUNCATE(start_amount,0) as start_amount,TRUNCATE(to_amount,0) as to_amount,TRUNCATE(fee_amount,0) as fee_amount');
            $this->db->where('status','Enable');
            $query1 = $this->db->get('fees');
            $res = array();
            if ($query1->num_rows() > 0) {
                $res = $query1->result();
            }

            $array['status'] = 200;
            $array['title'] = 'Success';
            $array['message'] = $res;
            echo json_encode($array);
        }

        public function getFeeByAmount($amount){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // echo $this->input->method(TRUE);
            $this->token_check($_POST);

            // echo $amount;

            // if(isset($_POST['amount'])){
                if (is_numeric($amount)) {
                    $this->db->select('id,TRUNCATE(fee_amount,0) as fee_amount');
                    $this->db->where('start_amount <= '.$amount.' AND to_amount >='.$amount);
                    $this->db->where('status','Enable');
                    $query1 = $this->db->get('fees');
                    $res = array();
                    if ($query1->num_rows() > 0) {
                        $res = $query1->row();

                        $array['status'] = 200;
                        $array['title'] = 'Success';
                        $array['message'] = $res;
                        echo json_encode($array);
                    }else{
                        $array['status'] = 404;
                        $array['title'] = 'Error!';
                        $array['message'] = array('error'=> 'Empty!!!');
                        echo json_encode($array);
                        exit;
                    }
                }else{
                    $array['status'] = 320;
                    $array['title'] = 'Error!';
                    $array['message'] = array('error'=> 'Invalid Amount');
                    echo json_encode($array);
                    exit;
                }
            // }else{
            //     $array['status'] = 310;
            //     $array['title'] = 'Error!';
            //     $array['message'] = array('error'=> 'Amount is Missing');
            //     echo json_encode($array);
            //     exit;
            // }
        }

        public function myProfile($member_id){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            // echo "<pre>";print_r($_POST);
            $this->token_check();

            // if(isset($_POST['member_id'])){
                $this->db->select('id,name,email,password,phone,profile_pic as profile_pic_name');
                $this->db->where('id',$member_id);
                $query1 = $this->db->get('members');
                $res = array();
                if ($query1->num_rows() > 0) {
                    $res = $query1->row();
                    
                    if(file_exists(PROFILE_PATH.'thumb/50x50_'.$res->profile_pic_name)){
                        $res->profile_pic_url = base_url().PROFILE_PATH.'thumb/50x50_'.$res->profile_pic_name;
                    }else{
                        $res->profile_pic_url = base_url().PROFILE_PATH.'thumb/50x50_'.'profile_default.png';
                    }

                    $array['status'] = 200;
                    $array['title'] = 'Success';
                    $array['message'] = $res;
                    echo json_encode($array);
                }else{
                    $array['status'] = 404;
                    $array['title'] = 'Error!';
                    $array['message'] = array('error'=> 'Not Found!!');
                    echo json_encode($array);
                    exit;
                }
            // }else{
            //     $array['status'] = 310;
            //     $array['title'] = 'Error!';
            //     $array['message'] = array('error'=> 'Member Id Missing!!');
            //     echo json_encode($array);
            //     exit;
            // }
        }

        public function settings($member_id){
            // $postdata = file_get_contents("php://input");
            // $_POST = json_decode($postdata, true);
            $this->token_check();

            // if(isset($_POST['member_id'])){
                $this->db->select('id,password,email,phone,qid,notification');
                $this->db->where('id',$member_id);
                $query1 = $this->db->get('members');
                $res = array();
                if ($query1->num_rows() > 0) {
                    $res = $query1->row();

                    $array['status'] = 200;
                    $array['title'] = 'Success';
                    $array['message'] = $res;
                    echo json_encode($array);
                }else{
                    $array['status'] = 404;
                    $array['title'] = 'Error!';
                    $array['message'] = array('error'=> 'Not Found!!');
                    echo json_encode($array);
                    exit;
                }
            // }else{   
            //     $array['status'] = 310;
            //     $array['title'] = 'Error!';
            //     $array['message'] = array('error'=> 'Member Id Missing!!');
            //     echo json_encode($array);
            //     exit;
            // }
        }

        public function edit_Notification(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check($_POST);

            $config = [
                [
                        'field' => 'member_id',
                        'label' => 'Member Id',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Member Id Missing.',
                        ],
                ],
                [
                        'field' => 'notification',
                        'label' => 'Notification',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Notification is Missing',
                        ],
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{
                if($_POST['notification'] == "on" || $_POST['notification'] == "off"){
                    $data = array(
                        "notification" => $_POST['notification']
                    );
                    $this->db->where('id',$_POST['member_id']);
                    if($this->db->update('members',$data)){
                        $array['status'] = 200;
                        $array['title'] = 'success';
                        if($_POST['notification'] == "off"){
                            $array['message'] = array('success'=> 'Notifications Disabled');
                        }else{
                            $array['message'] = array('success'=> 'Notifications Enabled');
                        }
                        echo json_encode($array);
                        exit;
                    }
                }else{
                    $array['status'] = 310;
                    $array['title'] = 'Error!';
                    $array['message'] = array('error'=> 'Notification value must be on or off');
                    echo json_encode($array);
                    exit;
                }
            }
        }

        public function edit_email(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check($_POST);

            $config = [
                [
                        'field' => 'member_id',
                        'label' => 'Member Id',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Member Id Missing.',
                        ],
                ],
                [
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'required|callback_emailcheck_edit|callback_chk_valid_email',
                        'errors' => [
                                'required' => 'Email is Missing',
                        ],
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{
                $data = array(
                    "email" => $_POST['email']
                );
                $this->db->where('id',$_POST['member_id']);
                if($this->db->update('members',$data)){
                    $array['status'] = 200;
                    $array['title'] = 'success';
                    $array['message'] = array('success'=> 'Email Changed Successfully');
                    echo json_encode($array);
                    exit;
                }
            }
        }

        public function edit_phone(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check($_POST);

            $config = [
                [
                        'field' => 'member_id',
                        'label' => 'Member Id',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Member Id Missing.',
                        ],
                ],
                [
                        'field' => 'phone',
                        'label' => 'Phone',
                        'rules' => 'required|numeric|min_length[8]|max_length[8]|callback_phonecheck_edit',
                        'errors' => [
                                'required' => 'Phone Number Missing.',
                                'min_length' => 'Phone Enter Valid Phone.',
                                'max_length' => 'Phone Enter Valid Phone.',
                        ],
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{
                $data = array(
                    "phone" => $_POST['phone']
                );
                $this->db->where('id',$_POST['member_id']);
                if($this->db->update('members',$data)){
                    $array['status'] = 200;
                    $array['title'] = 'success';
                    $array['message'] = array('success'=> 'Phone Number Changed Successfully');
                    echo json_encode($array);
                    exit;
                }
            }
        }

        public function edit_password(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check($_POST);

            $config = [
                [
                        'field' => 'member_id',
                        'label' => 'Member Id',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Member Id Missing.',
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
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{
                $data = array(
                    "password" => $_POST['password']
                );
                $this->db->where('id',$_POST['member_id']);
                if($this->db->update('members',$data)){
                    $array['status'] = 200;
                    $array['title'] = 'success';
                    $array['message'] = array('success'=> 'Password Changed Successfully');
                    echo json_encode($array);
                    exit;
                }
            }
        }

        public function edit_qid(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check($_POST);

            $config = [
                [
                        'field' => 'member_id',
                        'label' => 'Member Id',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Member Id Missing.',
                        ],
                ],
                [
                        'field' => 'qid',
                        'label' => 'QID',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'QID is Required',
                        ],
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{
                $data = array(
                    "qid" => $_POST['qid']
                );
                $this->db->where('id',$_POST['member_id']);
                if($this->db->update('members',$data)){
                    $array['status'] = 200;
                    $array['title'] = 'success';
                    $array['message'] = array('success'=> 'QID Changed Successfully');
                    echo json_encode($array);
                    exit;
                }
            }
        }

        public function getAllBidsList(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // $this->token_check();

            // $config = [
            //     [
            //         'field' => 'member_id',
            //         'label' => 'Member Id',
            //         'rules' => 'required',
            //         'errors' => [
            //                 'required' => 'Member Id Missing.',
            //         ],
            //     ]
            // ];

            // $this->form_validation->set_data($_POST);
            // $this->form_validation->set_rules($config);
        
            // if ($this->form_validation->run() == FALSE)
            // {
            //     $array['status'] = 400;
            //     $array['title'] = 'Error!';
            //     $new_err = array();
            //     foreach($this->form_validation->error_array() as $key => $val){
            //         $new_err['error'] = $val;
            //         break;
            //     }
            //     $array['message'] = $new_err;
            //     echo json_encode($array);
            //     exit;
            // }else{

                // =================================== purchase update code ========================
                // $this->db->select('*');
                // $this->db->where('bid_end_datetime <= CURRENT_TIMESTAMP()');
                // $this->db->where('progress_status = "0"');
                // $query_bids1 = $this->db->get('bids');

                // if ($query_bids1->num_rows() > 0) {
                //     $res_bids1 = $query_bids1->result();

                //     foreach($res_bids1 as $val1){
                //         $bid_id = $val1->id;

                //         $this->db->select('max(amount) as amount,member_id,bid_id');
                //         $this->db->where('bid_id',$bid_id);
                //         $query_bids2 = $this->db->get('member_bids');
                //         $res_bids2 = $query_bids2->result();

                //         foreach($res_bids2 as $val2){
                //             if($val2->amount > 0){
                //                 $update_data_purcahse = array(
                //                     "purchaser" => $val2->member_id,
                //                     "progress_status" => '1'
                //                 );
                //                 $this->db->where('id',$bid_id);
                //                 $this->db->update('bids',$update_data_purcahse);
                //             }else{

                //             }
                //         }

                //     }
                // }

                // ===================================
                // print_r($_POST);
                $price_filter_flag = "N";
                if(isset($_POST['filter']['price_id']) && $_POST['filter']['price_id'] != "" && $_POST['filter']['price_id'] != "5"){

                    $this->db->select('*');
                    $this->db->where('p.id',$_POST['filter']['price_id']);
                    $query_price_select = $this->db->get('price_filter p');
                    if($query_price_select->num_rows() > 0) {
                        $price_row = $query_price_select->row();
                        // print_r($price_row);
                        // if($price_row->to_amount == 0){
                        //     $this->db->where('b.starting_bid_amount >= "'.$price_row->start_amount.'"');
                        // }else{
                        //     $this->db->where('b.starting_bid_amount >= "'.$price_row->start_amount.'" AND b.starting_bid_amount <= "'.$price_row->to_amount.'"');
                        // }
                        $price_start_amount = $price_row->start_amount;
                        $price_to_amount = $price_row->to_amount;
                        $price_filter_flag = "Y";
                    }else{
                        $array['status'] = 310;
                        $array['title'] = 'Error!';
                        $array['message'] = array('error'=> "Invalid Price Id");
                        echo json_encode($array);
                        exit;
                    }

                }

                // $this->db->select('b.id,TRUNCATE(b.starting_bid_amount,0) as price_bid,b.upgrade_type,b.bid_end_datetime,b.number,ns.sub_name,nb.notification');
                $this->db->select('b.id,b.starting_bid_amount,b.upgrade_type,b.bid_end_datetime,b.number,ns.sub_name');
                $this->db->join('number_subtypes ns','ns.id = b.number_subtype','left');
                $this->db->join('fees f','f.id=b.fee','left');
                // $this->db->join('notification_bids nb','nb.bid_id = b.id AND nb.member_id = '.$_POST["member_id"].'','left');
                if(isset($_POST['filter']['number_subtype_id']) && !empty($_POST['filter']['number_subtype_id'])){

                    // echo "<pre>";print_r($_POST['filter']);
                    // exit;
                    // $this->db->where('ns.id',$_POST['filter']['number_subtype_id']);
                    $this->db->where_in('ns.id', $_POST['filter']['number_subtype_id']);
                }
                $this->db->where('b.progress_status','0');
                $this->db->where('b.status','Enable');
                // $this->db->where('b.id',$_POST['bid_id']);
                $this->db->from('bids b');
                $this->db->group_by('b.id');
                $this->db->order_by('b.upgrade_type','ASC');
                $query1 = $this->db->get();

                // echo $this->db->last_query();

                $res = array();$array2 = array();
                if ($query1->num_rows() > 0) {
                    $array1 = array();
                    $array1 = $query1->result();
                    
                    foreach($array1 as $key_m => $val_m){

                        // ==================
                        $array1[$key_m]->expiry_date = date('d-m-Y',strtotime($val_m->bid_end_datetime));
                        if(isset($_POST["member_id"])){
                            $this->db->select('notification');
                            $this->db->where('bid_id',$val_m->id);
                            $this->db->where('member_id',$_POST["member_id"]);
                            $query_check = $this->db->get('notification_bids nb');
                            if ($query_check->num_rows() > 0) {
                                $noti = $query_check->row();
                                $array1[$key_m]->notification = $noti->notification;
                            }else{
                                $array1[$key_m]->notification = "off";
                            }
                        }else{
                            $array1[$key_m]->notification = "off";
                        }
                        // if($val_m->notification == ""){
                        //    $array1[$key_m]->notification = "off";
                        // }

                        // ====================
                        
                        $this->db->select('max(amount) as max_bid');
                        $this->db->where('bid_id',$val_m->id);
                        $query_max = $this->db->get('member_bids');
                        $max_bid_amt = $query_max->row()->max_bid;
                        // echo $this->db->last_query();
                        if($max_bid_amt > $val_m->starting_bid_amount){
                            $array1[$key_m]->price_bid =  $max_bid_amt;
                        }else{
                            $array1[$key_m]->price_bid =  $val_m->starting_bid_amount;
                        }

                    
                        // ====================
                        $this->db->select('count(id) as total_no_bids');
                        $this->db->where('bid_id',$val_m->id);
                        $query2 = $this->db->get('member_bids');
                        $total_no_bids = $query2->row()->total_no_bids;
                        $array1[$key_m]->total_no_of_bids =  $total_no_bids;

                        // ===========

                        $array1[$key_m]->post_type = "bid";

                        if($price_filter_flag == "Y"){
                            if($price_row->to_amount == 0){
                                if($array1[$key_m]->price_bid >= $price_start_amount){
                                	$array2[] = $array1[$key_m];
                                }else{
                                    // unset($array1[$key_m]);
                                }
                            }else{
                                if($array1[$key_m]->price_bid >= $price_start_amount && $array1[$key_m]->price_bid <= $price_to_amount){
                                	$array2[] = $array1[$key_m];
                                }else{
                                    // unset($array1[$key_m]);
                                }
                            }
                        }else{
                        	$array2[] = $array1[$key_m];
                        }
                        
                        // ===============

                    }

                    // print_r($array1);
                    // unset($array1[0]);
                    // print_r($array1);

                    // $res = $array1;
                    $res = $array2;
                }

                // ============= adds =============
                $this->db->select('a.title,a.pic as picname');
                $this->db->where('a.status','Enable');
                $this->db->from('ads a');
                // $this->db->order_by('n.id','DESC');
                $query_ad = $this->db->get();
                $res_ad = array();
                if ($query_ad->num_rows() > 0) {
                    $res_ad = $query_ad->result();
                    foreach($res_ad as $key=>$val){

                        if(file_exists(ADBNR_PATH.$val->picname) && $val->picname != ""){
                            $res_ad[$key]->pic = base_url().ADBNR_PATH.$val->picname;
                        }else{
                            $res_ad[$key]->pic = "";
                            // $res[$key]->profile_pic_url = base_url().ADBNR_PATH.'profile_default.png';
                        }

                        $res_ad[$key]->post_type = "ad";

                    }
                }

                // print_r($res_ad);

                $new_array = array();
                $cnt_bid = 1;$cnt_ad = 0;
                foreach($res as $key=>$val){
                    $new_array[] = $res[$key];
                    if($cnt_bid == 1 || $cnt_bid == 3 || $cnt_bid == 6 || $cnt_bid == 10 || $cnt_bid == 15 || ($cnt_bid % 5 == 0 && $cnt_bid > 15) ){
                    // if($cnt_bid % 2 == 0){

                        if(array_key_exists($cnt_ad,$res_ad)){
                            $new_array[] = $res_ad[$cnt_ad];
                            $cnt_ad ++;
                        }

                    }
                    // if($cnt_bid == 3){
                    //     $cnt_bid = 6;
                    // }else{
                        $cnt_bid ++;
                    // }
                }

                // ======================

                $array['status'] = 200;
                $array['title'] = 'success';
                $array['message'] = $new_array;
                echo json_encode($array);
                // print_r($res);
                // return $res;

            // }
        }

        public function getBidsList(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // $this->token_check();

            $config = [
                [
                    'field' => 'member_id',
                    'label' => 'Member Id',
                    'rules' => 'required',
                    'errors' => [
                            'required' => 'Member Id Missing.',
                    ],
                ],
                [
                    'field' => 'bid_progress_status',
                    'label' => 'Bid Progress Status',
                    'rules' => 'required',
                    'errors' => [
                            'required' => 'Bid Progress Status Is Missing.',
                    ],
                ],
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{

                if($_POST['bid_progress_status'] != '0' && $_POST['bid_progress_status'] != '1'){
                    $array['status'] = 310;
                    $array['title'] = 'Error!';
                    $array['message'] = array('error'=> "Bid Stats must be 0 or 1. 0 = 'going on' 1 = 'completed' ");
                    echo json_encode($array);
                    exit;
                }

                // $this->db->select('b.id,TRUNCATE(b.starting_bid_amount,0) as price_bid,b.upgrade_type,b.bid_end_datetime,b.number,ns.sub_name,nb.notification');
                $this->db->select('b.id,b.starting_bid_amount,b.upgrade_type,b.progress_status as iscompleteStatus,b.bid_end_datetime,b.number,ns.sub_name');
                $this->db->join('number_subtypes ns','ns.id=b.number_subtype','left');
                $this->db->join('fees f','f.id=b.fee','left');
                // $this->db->join('notification_bids nb','nb.bid_id = b.id AND nb.member_id = '.$_POST["member_id"].'','left');
                // $this->db->join('member_bids mb','mb.bid_id = b.id AND nb.member_id = '.$_POST["member_id"].'','left');
                $this->db->join('member_bids mb','mb.bid_id = b.id','left');
                $this->db->where('b.progress_status',$_POST['bid_progress_status']);
                $this->db->where('b.status','Enable');
                $this->db->where('mb.member_id',$_POST['member_id']);
                $this->db->from('bids b');
                $this->db->group_by('b.id');
                $query1 = $this->db->get();
                $res = array();
                if ($query1->num_rows() > 0) {
                    $array1 = array();
                    $array1 = $query1->result();
                    
                    foreach($array1 as $key_m => $val_m){

                        $array1[$key_m]->expiry_date = date('d-m-Y',strtotime($val_m->bid_end_datetime));
                        // if($val_m->notification == ""){
                        //    $array1[$key_m]->notification = "off";
                        // }
                        // ============

                        $this->db->select('max(amount) as max_bid');
                        $this->db->where('bid_id',$val_m->id);
                        $query_max = $this->db->get('member_bids');
                        $max_bid_amt = $query_max->row()->max_bid;
                        // echo $this->db->last_query();
                        if($max_bid_amt > $val_m->starting_bid_amount){
                            $array1[$key_m]->price_bid =  $max_bid_amt;
                        }else{
                            $array1[$key_m]->price_bid =  $val_m->starting_bid_amount;
                        }

                        // ============

                        // $array1->sub_types = (object) array();
                        $this->db->select('count(id) as total_no_bids');
                        $this->db->where('bid_id',$val_m->id);
                        $query2 = $this->db->get('member_bids');
                        $total_no_bids = $query2->row()->total_no_bids;
                        $array1[$key_m]->total_no_of_bids =  $total_no_bids;
                    }
                    $res = $array1;
                }

                // echo json_encode($res);
                $array['status'] = 200;
                $array['title'] = 'Success';
                $array['message'] = $res;
                echo json_encode($array);
                exit;
            }
        }

        public function getMyAdsList(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check();

            $config = [
                [
                    'field' => 'member_id',
                    'label' => 'Member Id',
                    'rules' => 'required',
                    'errors' => [
                            'required' => 'Member Id Missing.',
                    ],
                ],
                [
                    'field' => 'bid_progress_status',
                    'label' => 'Bid Progress Status',
                    'rules' => 'required',
                    'errors' => [
                            'required' => 'Bid Progress Status Is Missing.',
                    ],
                ],
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{

                if($_POST['bid_progress_status'] != '0' && $_POST['bid_progress_status'] != '1'){
                    $array['status'] = 310;
                    $array['title'] = 'Error!';
                    $array['message'] = array('error'=> "Bid Stats must be 0 or 1. 0 = 'going on' 1 = 'completed'");
                    echo json_encode($array);
                    exit;
                }

                // $this->db->select('b.id,TRUNCATE(b.starting_bid_amount,0) as price_bid,b.upgrade_type,b.bid_end_datetime,b.number,ns.sub_name,nb.notification');
                $this->db->select('b.id,b.starting_bid_amount,b.upgrade_type,b.progress_status as iscompleteStatus,b.bid_end_datetime,b.number,ns.sub_name,nb.notification');
                $this->db->join('number_subtypes ns','ns.id=b.number_subtype','left');
                $this->db->join('fees f','f.id=b.fee','left');
                $this->db->join('notification_bids nb','nb.bid_id = b.id AND nb.member_id = '.$_POST["member_id"].'','left');
                // $this->db->join('member_bids mb','mb.bid_id = b.id AND nb.member_id = '.$_POST["member_id"].'','left');
                $this->db->where('b.progress_status',$_POST['bid_progress_status']);
                $this->db->where('b.member_id',$_POST['member_id']);
                $this->db->where('b.status','Enable');
                // $this->db->where('b.id',$_POST['bid_id']);
                $this->db->from('bids b');
                $this->db->group_by('b.id');
                $query1 = $this->db->get();
                $res = array();
                if ($query1->num_rows() > 0) {
                    $array1 = array();
                    $array1 = $query1->result();
                    
                    foreach($array1 as $key_m => $val_m){

                        $array1[$key_m]->expiry_date = date('d-m-Y',strtotime($val_m->bid_end_datetime));
                        if($val_m->notification == ""){
                           $array1[$key_m]->notification = "off";
                        }

                        // ====================
                        $this->db->select('max(amount) as max_bid');
                        $this->db->where('bid_id',$val_m->id);
                        $query_max = $this->db->get('member_bids');
                        $max_bid_amt = $query_max->row()->max_bid;
                        // echo $this->db->last_query();
                        if($max_bid_amt > $val_m->starting_bid_amount){
                            $array1[$key_m]->price_bid =  $max_bid_amt;
                        }else{
                            $array1[$key_m]->price_bid =  $val_m->starting_bid_amount;
                        }

                        // ===================
                        // $array1->sub_types = (object) array();
                        $this->db->select('count(id) as total_no_bids');
                        $this->db->where('bid_id',$val_m->id);
                        $query2 = $this->db->get('member_bids');
                        $total_no_bids = $query2->row()->total_no_bids;
                        $array1[$key_m]->total_no_of_bids =  $total_no_bids;
                    }
                    $res = $array1;
                }

                $array['status'] = 200;
                $array['title'] = 'Success';
                $array['message'] = $res;
                echo json_encode($array);
                // echo json_encode($res);
            }
        }

        public function get_ongoing_bid_detail($bid_id){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // $this->token_check();

            // $config = [
            //     [
            //         'field' => 'bid_id',
            //         'label' => 'Bid Id',
            //         'rules' => 'required',
            //         'errors' => [
            //                 'required' => 'Bid Id Missing.',
            //         ],
            //     ]
            // ];

            // $this->form_validation->set_data($_POST);
            // $this->form_validation->set_rules($config);
        
            // if ($this->form_validation->run() == FALSE)
            // {
            //     $array['status'] = 400;
            //     $array['title'] = 'Error!';
            //     $new_err = array();
            //     foreach($this->form_validation->error_array() as $key => $val){
            //         $new_err['error'] = $val;
            //         break;
            //     }
            //     $array['message'] = $new_err;
            //     echo json_encode($array);
            //     exit;
            // }else{

                // if($_POST['bid_progress_status'] != 0 && $_POST['bid_progress_status'] != 1){
                //     $array['status'] = 310;
                //     $array['title'] = 'Error!';
                //     $array['message'] = "Bid Stats must be 0 or 1. 0 = 'going on' 1 = 'completed' ";
                //     echo json_encode($array);
                //     exit;
                // }

                $this->db->select('b.id,b.starting_bid_amount as price_bid,b.upgrade_type,b.bid_end_datetime,b.number,ns.sub_name');
                $this->db->join('number_subtypes ns','ns.id=b.number_subtype','left');
                $this->db->join('fees f','f.id=b.fee','left');
                // $this->db->join('notification_bids nb','nb.bid_id = b.id AND nb.member_id = '.$_POST["member_id"].'','left');
                // $this->db->join('member_bids mb','mb.bid_id = b.id AND nb.member_id = '.$_POST["member_id"].'','left');
                // $this->db->where('b.progress_status',$_POST['bid_progress_status']);
                // $this->db->where('b.member_id',$_POST['member_id']);
                $this->db->where('b.status','Enable');
                $this->db->where('b.id',$bid_id);
                $this->db->from('bids b');
                // $this->db->group_by('b.id');
                $query1 = $this->db->get();

                // echo $this->db->last_query();
                $res = array();
                if ($query1->num_rows() > 0) {
                    $array1 = array();
                    $array1 = $query1->result();
                    
                    foreach($array1 as $key_m => $val_m){

                        $array1[$key_m]->purchaser = "";
                        $array1[$key_m]->profile_pic_name = "";
                        $array1[$key_m]->profile_pic_url = "";

                        $array1[$key_m]->expiry_date = date('d-m-Y',strtotime($val_m->bid_end_datetime));
                        // if($val_m->notification == ""){
                        //    $array1[$key_m]->notification = "off";
                        // }

                        // ============

                        // $array1->sub_types = (object) array();
                        $this->db->select('count(id) as total_no_bids');
                        $this->db->where('bid_id',$val_m->id);
                        $query2 = $this->db->get('member_bids');
                        $total_no_bids = $query2->row()->total_no_bids;
                        $array1[$key_m]->total_no_of_bids =  $total_no_bids;

                        $this->db->select('max(amount) as max_bid,created_at');
                        $this->db->where('bid_id',$val_m->id);
                        $query3 = $this->db->get('member_bids');
                        $max_bid = $query3->row();
                        $array1[$key_m]->last_bid_amount =  $max_bid->max_bid;
                        $array1[$key_m]->submitted_date =  date('M-d-Y',strtotime($max_bid->created_at));
                    }
                    $res = $array1;
                }

                $array['status'] = 200;
                $array['title'] = 'Success';
                $array['message'] = $res;
                echo json_encode($array);

            // }
        }

        public function get_completed_bid_detail($bid_id){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            // $this->token_check();

            // $config = [
            //     [
            //         'field' => 'bid_id',
            //         'label' => 'Bid Id',
            //         'rules' => 'required',
            //         'errors' => [
            //                 'required' => 'Bid Id Missing.',
            //         ],
            //     ]
            // ];

            // $this->form_validation->set_data($_POST);
            // $this->form_validation->set_rules($config);
        
            // if ($this->form_validation->run() == FALSE)
            // {
            //     $array['status'] = 400;
            //     $array['title'] = 'Error!';
            //     $new_err = array();
            //     foreach($this->form_validation->error_array() as $key => $val){
            //         $new_err['error'] = $val;
            //         break;
            //     }
            //     $array['message'] = $new_err;
            //     echo json_encode($array);
            //     exit;
            // }else{

                // if($_POST['bid_progress_status'] != 0 && $_POST['bid_progress_status'] != 1){
                //     $array['status'] = 310;
                //     $array['title'] = 'Error!';
                //     $array['message'] = "Bid Stats must be 0 or 1. 0 = 'going on' 1 = 'completed' ";
                //     echo json_encode($array);
                //     exit;
                // }

                $this->db->select('b.id,b.starting_bid_amount as price_bid,b.upgrade_type,b.bid_end_datetime,b.number,ns.sub_name,m.name as purchaser,m.profile_pic as profile_pic_name');
                $this->db->join('number_subtypes ns','ns.id=b.number_subtype','left');
                $this->db->join('fees f','f.id=b.fee','left');
                $this->db->join('members m','m.id = b.purchaser','left');
                // $this->db->join('member_bids mb','mb.bid_id = b.id AND nb.member_id = '.$_POST["member_id"].'','left');
                // $this->db->where('b.progress_status',$_POST['bid_progress_status']);
                // $this->db->where('b.member_id',$_POST['member_id']);
                $this->db->where('b.status','Enable');
                $this->db->where('b.id',$bid_id);
                $this->db->from('bids b');
                // $this->db->group_by('b.id');
                $query1 = $this->db->get();

                // echo $this->db->last_query();
                $res = array();
                if ($query1->num_rows() > 0) {
                    $array1 = array();
                    $array1 = $query1->result();
                    
                    foreach($array1 as $key_m => $val_m){

                        if(file_exists(PROFILE_PATH.'thumb/50x50_'.$val_m->profile_pic_name)){
                            $val_m->profile_pic_url = base_url().PROFILE_PATH.'thumb/50x50_'.$val_m->profile_pic_name;
                        }else{
                            $val_m->profile_pic_url = base_url().PROFILE_PATH.'thumb/50x50_'.'profile_default.png';
                        }

                        $array1[$key_m]->expiry_date = date('d-m-Y',strtotime($val_m->bid_end_datetime));
                        // if($val_m->notification == ""){
                        //    $array1[$key_m]->notification = "of   f";
                        // }

                        // ============

                        // $array1->sub_types = (object) array();
                        $this->db->select('count(id) as total_no_bids');
                        $this->db->where('bid_id',$val_m->id);
                        $query2 = $this->db->get('member_bids');
                        $total_no_bids = $query2->row()->total_no_bids;
                        $array1[$key_m]->total_no_of_bids =  $total_no_bids;

                        $this->db->select('max(amount) as max_bid,created_at');
                        $this->db->where('bid_id',$val_m->id);
                        $query3 = $this->db->get('member_bids');
                        $max_bid = $query3->row();
                        $array1[$key_m]->last_bid_amount =  $max_bid->max_bid;
                        $array1[$key_m]->submitted_date =  date('M-d-Y',strtotime($max_bid->created_at));
                    }
                    $res = $array1;
                }

                $array['status'] = 200;
                $array['title'] = 'Success';
                $array['message'] = $res;
                echo json_encode($array);

            // }
        }

        public function placeNewBid(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check($_POST);

            $config = [
                [
                        'field' => 'member_id',
                        'label' => 'Member Id',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Member Id Missing.',
                        ],
                ],
                [
                        'field' => 'bid_id',
                        'label' => 'Bid Id',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Bid Id Missing.',
                        ],
                ],
                [
                        'field' => 'current_bid_amount',
                        'label' => 'Current Bid Amount',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Current Bid Amount Required',
                        ],
                ]
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{
            
                $bid = $this->get_bid_by_id($_POST['bid_id']);
                // echo "b".$bid->member_id;
                // echo "m".$_POST['member_id'];
                // exit;
                if(isset($bid->member_id) && $bid->member_id == $_POST['member_id']){
                    $array['status'] = 320;
                    $array['title'] = 'Error!';
                    $array['message'] = array('error'=> 'You Can Not Place Bid On your Own Ads');
                    echo json_encode($array);
                    exit;
                }

                $this->db->select('max(amount) as minimum_amount');
                // $this->db->where('member_id',$_POST['member_id']);
                $this->db->where('bid_id',$_POST['bid_id']);
                $query_max = $this->db->get('member_bids mb');

                $max_amount = $query_max->row()->minimum_amount;
                if($bid->starting_bid_amount > $max_amount){
                    // echo "111";
                    $minimum_amount = $bid->starting_bid_amount;
                }else{
                    // echo "222";
                    $minimum_amount = $max_amount+1;
                }
                // exit;

                // if(isset($bid->starting_bid_amount) && $bid->starting_bid_amount > $_POST['current_bid_amount']){
                if(isset($minimum_amount) && $minimum_amount > $_POST['current_bid_amount']){
                    $array['status'] = 310;
                    $array['title'] = 'error!';
                    $array['message'] = array('error'=> 'Amount Must Be Greter than or Equal to '.number_format($minimum_amount).' QAR');
                    echo json_encode($array);
                    exit;
                }

                $data_ins = array(
                    'member_id' => $_POST['member_id'],
                    'bid_id' => $_POST['bid_id'],
                    'amount' => $_POST['current_bid_amount'],
                    'created_at' => date('Y-m-d H:i:s')
                );

                if($this->db->insert('member_bids',$data_ins)){
                    $member_bid_id = $this->db->insert_id();

                    // $notification_add = array(
                    //     'member_id' => $bid->member_id,
                    //     'bid_id' => $_POST['bid_id'],
                    //     'title' => "You Have New Bids Your Ads"
                    // );
                    // $this->db->insert('notifications',$notification_add);

                    $this->send_push_notification($_POST['bid_id'],$_POST['member_id'],$bid->member_id,$_POST['current_bid_amount'],$bid->number);
                    $array['status'] = 200;
                    $array['title'] = 'Success!';
                    $array['message'] = array('success'=> 'Bid Placed Successfully.');
                    echo json_encode($array);
                    exit;
                }

            }
        }

        public function change_bid_notification(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check($_POST);

            $config = [
                [
                        'field' => 'member_id',
                        'label' => 'Member Id',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Member Id Missing.',
                        ],
                ],
                [
                        'field' => 'bid_id',
                        'label' => 'Bid Id',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Bid Id Missing.',
                        ],
                ],
                [
                        'field' => 'notification',
                        'label' => 'Notification',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Notification Missing.',
                        ],
                ],
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{

                $success = "N";
                if($_POST['notification'] == "on"){

                    $msg = 'Notifications Enabled';
                    $this->db->select('nb.*');
                    $this->db->where('member_id',$_POST['member_id']);
                    $this->db->where('bid_id',$_POST['bid_id']);
                    $query1 = $this->db->get('notification_bids nb');
                    if ($query1->num_rows() > 0) {
                        $data = array(
                            "notification" => $_POST['notification']
                        );
                        $this->db->where('member_id',$_POST['member_id']);
                        $this->db->where('bid_id',$_POST['bid_id']);
                        if($this->db->update('notification_bids',$data)){
                            $success = "Y";
                        }
                    }else{
                        $data = array(
                            "notification" => $_POST['notification'],
                            "member_id" => $_POST['member_id'],
                            "bid_id" => $_POST['bid_id']
                        );
                        if($this->db->insert('notification_bids',$data)){
                            $success = "Y";
                        }
                    }

                }else if($_POST['notification'] == "off"){

                    $msg = 'Notifications Disabled';
                    $data = array(
                        "notification" => $_POST['notification']
                    );
                    $this->db->where('member_id',$_POST['member_id']);
                    $this->db->where('bid_id',$_POST['bid_id']);
                    if($this->db->update('notification_bids',$data)){
                        $success = "Y";
                    }
                }else{
                    $array['status'] = 310;
                    $array['title'] = 'Error!';
                    $array['message'] = array('error'=> 'Notification value must be on or off');
                    echo json_encode($array);
                    exit;
                }

                if($success == "Y"){
                    $array['status'] = 200;
                    $array['title'] = 'success';
                    $array['message'] = array('success'=> $msg);
                    echo json_encode($array);
                    exit;
                }
            }
        }

        public function getFilterValue(){
            $this->token_check();

            $res = array();

            $this->db->select('id,name');
            $query1 = $this->db->get('number_types');
            if ($query1->num_rows() > 0) {
                $array1 = array();
                $array1 = $query1->result();
                
                foreach($array1 as $key_m => $val_m){
                    $this->db->select('id,sub_name');
                    $this->db->where('number_type_id',$val_m->id);
                    $query2 = $this->db->get('number_subtypes');
                    if ($query2->num_rows() > 0) {
                        $res2 = $query2->result();
                        $array1[$key_m]->sub_types =  $res2;
                    }
                }

                $res['numbers'] = $array1;
            }

            $this->db->select('*');
            $query3 = $this->db->get('price_filter');
            if ($query3->num_rows() > 0) {
                $array3 = array();
                foreach($query3->result() as $key_m => $val_m){
                    if($val_m->start_amount == 0){
                        $array3[$key_m]['price_id'] = $val_m->id;
                        // $array3[$key_m]['text'] = 'Under '.$val_m->to_amount.' QAR';
                        $array3[$key_m]['text'] = $val_m->text_heading;
                    }else if($val_m->to_amount == 0){
                        $array3[$key_m]['price_id'] = $val_m->id;
                        // $array3[$key_m]['text'] = 'Over '.$val_m->start_amount.' QAR';
                        $array3[$key_m]['text'] = $val_m->text_heading;
                    }else{
                        $array3[$key_m]['price_id'] = $val_m->id;
                        // $array3[$key_m]['text'] = $val_m->start_amount.' QAR - '.$val_m->to_amount.' QAR';
                        $array3[$key_m]['text'] = $val_m->text_heading;
                    }
                }

                $res['price'] = $array3;
            }

            $array['status'] = 200;
            $array['title'] = 'success';
            $array['message'] = $res;
            echo json_encode($array);
            exit;
        }

        public function get_notifications($member_id){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check();

            // $config = [
            //     [
            //             'field' => 'member_id',
            //             'label' => 'Member Id',
            //             'rules' => 'required',
            //             'errors' => [
            //                     'required' => 'Member Id Missing.',
            //             ],
            //     ]
            // ];

            // $this->form_validation->set_data($_POST);
            // $this->form_validation->set_rules($config);
        
            // if ($this->form_validation->run() == FALSE)
            // {
            //     $array['status'] = 400;
            //     $array['title'] = 'Error!';
            //     $new_err = array();
            //     foreach($this->form_validation->error_array() as $key => $val){
            //         $new_err['error'] = $val;
            //         break;
            //     }
            //     $array['message'] = $new_err;
            //     echo json_encode($array);
            //     exit;
            // }else{
                $this->db->select('n.id,n.bid_id,n.title,n.created_at,b.number,b.upgrade_type,b.numbersubtype_str as sub_name');
                $this->db->where('n.member_id',$member_id);
                $this->db->join('bids b','b.id = n.bid_id','left');
                $this->db->from('notifications n');
                $this->db->order_by('n.id','DESC');
                $query1 = $this->db->get();
                $res = array();
                if ($query1->num_rows() > 0) {
                    $res = $query1->result();
                }

                $array['status'] = 200;
                $array['title'] = 'success';
                $array['message'] = $res;
                // $array = (object) $array;
                echo json_encode($array);
                exit;
            // }
        }

        public function terms_condition_link(){
            $res = array('link' => base_url());

            $array['status'] = 200;
            $array['title'] = 'success';
            $array['message'] = $res;
            // $array = (object) $array;
            echo json_encode($array);
            exit;
        }

        public function apply_coupon(){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);
            $this->token_check($_POST);

            $config = [
                // [
                //         'field' => 'member_id',
                //         'label' => 'Member Id',
                //         'rules' => 'required',
                //         'errors' => [
                //                 'required' => 'Member Id Missing.',
                //         ],
                // ],
                [
                        'field' => 'amount',
                        'label' => 'Amount',
                        'rules' => 'required|numeric',
                        'errors' => [
                                'required' => 'Amount Is Missing.',
                                'numeric' => 'Invalid Amount',
                        ],
                ],
                [
                        'field' => 'coupon',
                        'label' => 'Coupon',
                        'rules' => 'required',
                        'errors' => [
                                'required' => 'Coupon Is Missing.',
                        ],
                ],
            ];

            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules($config);
        
            if ($this->form_validation->run() == FALSE)
            {
                $array['status'] = 400;
                $array['title'] = 'Error!';
                $new_err = array();
                foreach($this->form_validation->error_array() as $key => $val){
                    $new_err['error'] = $val;
                    break;
                }
                $array['message'] = $new_err;
                echo json_encode($array);
                exit;
            }else{

                $this->db->select('c.*');
                $this->db->where('code',$_POST['coupon']);
                $this->db->where('status','Enable');
                $this->db->where('expiry_date >= CURDATE()');
                $query1 = $this->db->get('coupons c');
                if($query1->num_rows() > 0) {
                    $coupon = $query1->row();

                    if($coupon->amount_type == 0){
                        $res['total'] = $_POST['amount'];
                        $res['discount'] = ($_POST['amount'] * $coupon->amount)/100;
                        $res['payeble_amount'] =  $_POST['amount'] - (($_POST['amount'] * $coupon->amount)/100);
                    }else if($coupon->amount_type == 1){
                        $res['total'] = $_POST['amount'];
                        $res['discount'] = $coupon->amount;
                        $res['payeble_amount'] = $_POST['amount'] - $coupon->amount;
                    }

                    $array['status'] = 200;
                    $array['title'] = 'success';
                    $array['message'] = $res;
                    echo json_encode($array);
                    exit;
                }else{
                    $array['status'] = 310;
                    $array['title'] = 'Error!';
                    $array['message'] = array('error'=> 'Invalid Coupon Code');
                    echo json_encode($array);
                    exit;
                }

            }
        }

        public function get_adBanner(){
            // $postdata = file_get_contents("php://input");
            // $_POST = json_decode($postdata, true);
            // $this->token_check();

            // $config = [
            //     [
            //             'field' => 'member_id',
            //             'label' => 'Member Id',
            //             'rules' => 'required',
            //             'errors' => [
            //                     'required' => 'Member Id Missing.',
            //             ],
            //     ]
            // ];

            // $this->form_validation->set_data($_POST);
            // $this->form_validation->set_rules($config);
        
            // if ($this->form_validation->run() == FALSE)
            // {
            //     $array['status'] = 400;
            //     $array['title'] = 'Error!';
            //     $new_err = array();
            //     foreach($this->form_validation->error_array() as $key => $val){
            //         $new_err['error'] = $val;
            //         break;
            //     }
            //     $array['message'] = $new_err;
            //     echo json_encode($array);
            //     exit;
            // }else{
                $this->db->select('a.title,a.pic as picname');
                $this->db->where('a.status','Enable');
                $this->db->from('ads a');
                // $this->db->order_by('n.id','DESC');
                $query1 = $this->db->get();
                $res = array();
                if ($query1->num_rows() > 0) {
                    $res = $query1->result();
                    foreach($res as $key=>$val){

                        if(file_exists(ADBNR_PATH.$val->picname) && $val->picname != ""){
                            $res[$key]->pic = base_url().ADBNR_PATH.$val->picname;
                        }else{
                            $res[$key]->pic = "";
                            // $res[$key]->profile_pic_url = base_url().ADBNR_PATH.'profile_default.png';
                        }

                    }
                }

                $array['status'] = 200;
                $array['title'] = 'success';
                $array['message'] = $res;
                // $array = (object) $array;
                echo json_encode($array);
                exit;
            // }
        }

        public function send_push_notification($bid_id,$place_bid_member_id,$bid_adder_id,$amount,$number){

            $title = "New bid submitted";
            $body = "Bid - ".$number." New Bid Placed at ".$amount." QAR";

            // send to member which added bid
            $this->db->select('m.notification as member_notification,m.firebase_token');
            $this->db->where('m.id',$bid_adder_id);
            $this->db->from('members m');
            $query0 = $this->db->get();
            if ($query0->num_rows() > 0) {
                $array0 = $query0->row();
                if($array0->member_notification == 'on'){
                    $notification_add0 = array(
                        'member_id' => $bid_adder_id,
                        'bid_id' => $bid_id,
                        'title' => $title,
                        'message' => $body,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('notifications',$notification_add0);

                    $isNotificationType = true;
                    if($this->sendPushToAndroid($array0->firebase_token,$title,$body,$isNotificationType)){
                        
                    }
                }

            }

            // send to member which on notification
            $this->db->select('nb.*,m.firebase_token,m.notification as member_notification');
            $this->db->join('members m','m.id = nb.member_id','left');
            $this->db->where('nb.bid_id',$bid_id);
            $this->db->where('nb.member_id !=',$place_bid_member_id);
            $this->db->where('nb.notification','on');
            $this->db->from('notification_bids nb');
            $query1 = $this->db->get();
            if ($query1->num_rows() > 0) {
                $array1 = $query1->result();
                foreach($array1 as $key_m => $val_m){

                    if($val_m->member_notification == 'on'){
                        $notification_add = array(
                            'member_id' => $val_m->member_id,
                            'bid_id' => $bid_id,
                            'title' => $title,
                            'message' => $body,
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('notifications',$notification_add);

                        // $title = "New Bid arrived.";
                        // $body = "New Bid Arrived, place high to puchase it.";
                        $isNotificationType = true;
                        if($this->sendPushToAndroid($val_m->firebase_token,$title,$body,$isNotificationType)){
                            
                        }
                    }

                }
            }

        }

        public function test_notification(){
            $deviceToken = "cxrJWRfCSEeew_b_6idJcb:APA91bEJqxnNgnuMF-DkGqHvYjvYvI5nisoV74mvByCrRmBITmKh59_wtfQ9-wZamJLzoKd8rR8pPvyzixr_Sa56V5aCsnpYpw_hrcTiyQErOsXVgSyxwwU4hctHCC7mp5o2qYaFAB9Y";
            $title = "test title";
            $body = "test body";
            $isNotificationType = true;
            $this->sendPushToAndroid($deviceToken,$title,$body,$isNotificationType);
        }

        public function sendPushToAndroid($deviceToken, $title, $body, $isNotificationType)
        {
            // define("FIREBASE_API_KEY", "AAAA9qk5Ss8:APA91bG6KptZIsjyv9tnRixr1vqi86OVo3tbQLZhtOn74vzkeO0yzPJnIxDmrIdgqypoDZuDV5m6mLOuQEh_LQE6db0utHYdPacgxJdWXv4e4Sr4l8NiQy8bfOhihNrZLYBU1EURuaAv");
            // define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");
            $FIREBASE_API_KEY = "AAAA9qk5Ss8:APA91bG6KptZIsjyv9tnRixr1vqi86OVo3tbQLZhtOn74vzkeO0yzPJnIxDmrIdgqypoDZuDV5m6mLOuQEh_LQE6db0utHYdPacgxJdWXv4e4Sr4l8NiQy8bfOhihNrZLYBU1EURuaAv";
            $FIREBASE_FCM_URL = "https://fcm.googleapis.com/fcm/send";

            $fields = array();

            $fields['to'] = $deviceToken;
            $fields['priority'] = "high";

            if ($isNotificationType) {
                $payload['title'] = $title;
                $payload['body'] = $body;
                $fields['notification'] = $payload;
            }

            $headers = array(
                'Authorization: key=' . $FIREBASE_API_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $FIREBASE_FCM_URL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Problem occurred: ' . curl_error($ch));
            }

            curl_close($ch);
            // print_r($result);
            return $result;
        }

        public function sendPushToIos($deviceToken, $title, $body, $isNotificationType)
        {
            $url = "https://fcm.googleapis.com/fcm/send";
            $token = "";
            $serverKey = '';
            $title = "Title";
            $body = "Body of the message";
            $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '1');
            $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
            $json = json_encode($arrayToSend);
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key='. $serverKey;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
            //Send the request
            $response = curl_exec($ch);
            //Close request
            if ($response === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
        }

        public function emailcheck()
        {
            $this->db->select('*');
            $this->db->where('email =',$this->input->post('email'));
            $this->db->where('otp_varified','Y');
            $query1 = $this->db->get('members');
            if ($query1->num_rows() > 0) {
                $this->form_validation->set_message('emailcheck', 'Email Already Exists');
                return false;
            }else{
                return true;
            }
        }

        public function emailcheck_edit()
        {
            $this->db->select('*');
            $this->db->where('email =',$this->input->post('email'));
            $this->db->where('otp_varified','Y');
            $this->db->where('id !=',$this->input->post('member_id'));
            $query1 = $this->db->get('members');
            if ($query1->num_rows() > 0) {
                $this->form_validation->set_message('emailcheck_edit', 'Email Already Exists');
                return false;
            }else{
                return true;
            }
        }

        public function usernamecheck()
        {
            $this->db->select('*');
            $this->db->where('username =',$this->input->post('username'));
            $this->db->where('otp_varified','Y');
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
            $this->db->where('otp_varified','Y');
            $query1 = $this->db->get('members');
            if ($query1->num_rows() > 0) {
                $this->form_validation->set_message('phonecheck', 'Phone Number Already Exists');
                return false;
            }else{
                return true;
            }
        }

        public function phonecheck_edit()
        {
            $this->db->select('*');
            $this->db->where('phone =',$this->input->post('phone'));
            $this->db->where('otp_varified','Y');
            $this->db->where('id !=',$this->input->post('member_id'));
            $query1 = $this->db->get('members');
            // echo $this->db->last_query();
            if ($query1->num_rows() > 0) {
                $this->form_validation->set_message('phonecheck_edit', 'Phone Number Already Exists');
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