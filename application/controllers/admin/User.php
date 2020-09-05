<?php
    class User extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model(ADMINPATH.'user_model','user');
            // $this->load->library('upload');
            checkLogin('admin');
            if($this->session->userdata('loginData')->user_type != "super_admin"){
                redirect(ADMINPATH.'dashboard');
            }
        }

        function index(){
            $data['user_manage'] = TRUE;
            $data['title']="Admin Users";

            if($this->input->post('action') == "change_publish"){
                if ($result = $this->user->st_update()) {
                    $this->session->set_flashdata('notification', 'User status has been update successfully.');
                    redirect(ADMINPATH.'user');
                }
            }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
                if ($result = $this->member->delete()) {
                    $this->session->set_flashdata('notification', 'User deleted successfully.');
                    redirect(ADMINPATH.'user');
                }
            }
            // elseif ($this->input->post('action') == "deleteselected") {
            //     if ($result = $this->product->deleteselected()) {
            //         $this->session->set_flashdata('notification', 'categoty has been deleted successfully.');
            //         redirect('product');
            //     }
            // }
            
            $data['manage_data'] = $this->user->get_users();
            $this->load->view(ADMINPATH.'user/list',$data);
        }

        function add(){ 
            $data['user_form'] = TRUE;
            $data['action']='add';
            $data['title']="Admin User";
            // $data['companies'] = $this->user->get_companies();
            $data['privileges_list'] = $this->user->getPrivileges();

            
            if(isset($_POST['submit'])){

                $config = [
                    [
                            'field' => 'name',
                            'label' => 'Name',
                            'rules' => 'required',
                            'errors' => [
                                    // 'required' => 'Member Id Missing.',
                            ],
                    ],
                    [
                            'field' => 'username',
                            'label' => 'Username',
                            'rules' => 'required|callback_usernameCheck_add',
                            'errors' => [
                                    // 'required' => 'Amount is required fields',
                            ],
                    ],
                    [
                            'field' => 'phone',
                            'label' => 'Phone',
                            'rules' => 'required|numeric|callback_phoneCheck_add',
                            'errors' => [
                                    // 'required' => 'Amount is required fields',
                            ],
                    ],
                    [
                            'field' => 'email',
                            'label' => 'email',
                            'rules' => 'required|callback_chk_valid_email|callback_emailCheck_add',
                            'errors' => [
                                    // 'required' => 'Member Id Missing.',
                            ],
                    ],
                    [
                            'field' => 'password',
                            'label' => 'Password',
                            'rules' => 'required|min_length[6]',
                            'errors' => [
                                    // 'required' => 'Password is Required',
                            ],
                    ],
                    [
                            'field' => 'confirm_password',
                            'label' => 'Confirm Password',
                            'rules' => 'required|matches[password]',
                            'errors' => [
                                    // 'required' => 'Confirmn Password is Required',
                            ],
                    ],
                    
                ];
    
                $this->form_validation->set_data($_POST);
                $this->form_validation->set_rules($config);
            
                if ($this->form_validation->run() == FALSE)
                {
                    $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                    $this->load->view(ADMINPATH.'user/add',$data); 
                }else{
                    if($this->user->insert()) {
                        $this->session->set_flashdata('notification', 'User information has been insert successfully.');
                        redirect(ADMINPATH.'user');
                    }
                }
                
            // }elseif($this->input->post('cancel')){
            //     redirect('product');
            }else{
                $this->load->view(ADMINPATH.'user/add',$data); 
            }
        }

        function edit(){
            $data['user_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Admin User";
            // $data['companies'] = $this->user->get_companies();
            $data['form_data'] = $this->user->getDataById($this->uri->segment(4));
            $data['privileges_list'] = $this->user->getPrivileges();
            
            if(isset($_POST['submit'])){

                $config = [
                    [
                            'field' => 'name',
                            'label' => 'Name',
                            'rules' => 'required',
                            'errors' => [
                                    // 'required' => 'Member Id Missing.',
                            ],
                    ],
                    [
                            'field' => 'username',
                            'label' => 'Username',
                            'rules' => 'required|callback_usernameCheck_edit',
                            'errors' => [
                                    // 'required' => 'Amount is required fields',
                            ],
                    ],
                    [
                            'field' => 'phone',
                            'label' => 'Phone',
                            'rules' => 'required|numeric|callback_phoneCheck_edit',
                            'errors' => [
                                    // 'required' => 'Amount is required fields',
                            ],
                    ],
                    [
                            'field' => 'email',
                            'label' => 'email',
                            'rules' => 'required|callback_chk_valid_email|callback_emailCheck_edit',
                            'errors' => [
                                    // 'required' => 'Member Id Missing.',
                            ],
                    ],
                    [
                            'field' => 'password',
                            'label' => 'Password',
                            'rules' => 'min_length[6]',
                            'errors' => [
                                    // 'required' => 'Password is Required',
                            ],
                    ],
                    [
                            'field' => 'confirm_password',
                            'label' => 'Confirm Password',
                            'rules' => 'matches[password]',
                            'errors' => [
                                    // 'required' => 'Confirmn Password is Required',
                            ],
                    ],
                    
                ];
                $this->form_validation->set_data($_POST);
                $this->form_validation->set_rules($config);
            
                if ($this->form_validation->run() == FALSE)
                {
                    $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                    $this->load->view(ADMINPATH.'user/edit',$data); 
                }else{
                    if ($result = $this->user->update()) {
                        $this->session->set_flashdata('notification','User information has been update successfully.');
                        redirect(ADMINPATH.'user');
                    }
                }
            }else{
                // echo $this->uri->segment(3);exit;
                $this->load->view(ADMINPATH.'user/edit',$data);
            }
        }

        // function view(){
        //     $data['user_form'] = TRUE;
        //     $data['action']="edit";
        //     $data['title']="Member";
        //     $data['form_data'] = $this->member->getDataById($this->uri->segment(4));
        //     $this->load->view(ADMINPATH.'member/view',$data); 
        // }

        function emailCheck_add(){
            $err = 0;
            $this->db->select('*');
            $this->db->where('email =',$this->input->post('email'));
            // $this->db->where('id !=',$this->input->post('id'));
            $query1 = $this->db->get('users');
            if ($query1->num_rows() > 0) {
                $err++;
            }

            if ($err > 0) {
                $this->form_validation->set_message('emailCheck_add', 'Email Already Exist.');
                return false;
            }else{
                return true;
            }
        }

        function usernameCheck_add(){
            $err = 0;
            $this->db->select('*');
            $this->db->where('username =',$this->input->post('username'));
            // $this->db->where('id !=',$this->input->post('id'));
            $query1 = $this->db->get('users');
            if ($query1->num_rows() > 0) {
                $err++;
            }

            if ($err > 0) {
                $this->form_validation->set_message('usernameCheck_add', 'Username Already Exist.');
                return false;
            }else{
                return true;
            }
        }

        function phoneCheck_add(){
            $err = 0;
            $this->db->select('*');
            $this->db->where('phone =',$this->input->post('phone'));
            // $this->db->where('id !=',$this->input->post('id'));
            $query1 = $this->db->get('users');
            if ($query1->num_rows() > 0) {
                $err++;
            }

            if ($err > 0) {
                $this->form_validation->set_message('phoneCheck_add', 'Phone Number Already Exist.');
                return false;
            }else{
                return true;
            }
        }

        function emailCheck_edit(){
            $err = 0;
            $this->db->select('*');
            $this->db->where('email =',$this->input->post('email'));
            $this->db->where('id !=',$this->input->post('id'));
            $query1 = $this->db->get('users');
            if ($query1->num_rows() > 0) {
                $err++;
            }

            if ($err > 0) {
                $this->form_validation->set_message('emailCheck_edit', 'Email Already Exist.');
                return false;
            }else{
                return true;
            }
        }

        function usernameCheck_edit(){
            $err = 0;
            $this->db->select('*');
            $this->db->where('username =',$this->input->post('username'));
            $this->db->where('id !=',$this->input->post('id'));
            $query1 = $this->db->get('users');
            if ($query1->num_rows() > 0) {
                $err++;
            }

            if ($err > 0) {
                $this->form_validation->set_message('usernameCheck_edit', 'Username Already Exist.');
                return false;
            }else{
                return true;
            }
        }

        function phoneCheck_edit(){
            $err = 0;
            $this->db->select('*');
            $this->db->where('phone =',$this->input->post('phone'));
            $this->db->where('id !=',$this->input->post('id'));
            $query1 = $this->db->get('users');
            if ($query1->num_rows() > 0) {
                $err++;
            }

            if ($err > 0) {
                $this->form_validation->set_message('phoneCheck_edit', 'Phone Number Already Exist.');
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

        function filter(){
            // echo "<pre>";
            // print_r($_POST);

            if($_POST['submit'] == 'Apply'){
                $_SESSION['user_filt']['filter_date_start'] = $_POST['filter_date_start'];
                $_SESSION['user_filt']['filter_date_end'] = $_POST['filter_date_end'];
            }else if($_POST['submit'] == 'Reset'){
                unset($_SESSION['user_filt']);
            }

            redirect(ADMINPATH.'user');
        }

        function exportXLS(){
            $data = $this->user->get_users();
            $this->load->library('xls');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            
            $rowSheet1 = 1;$colSheet1 = 0;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'#');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Id');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Name');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Username');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Email');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Phone');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Status');
            $colSheet1++;

            $count = 1;
            foreach($data as $val){
                $rowSheet1++;$colSheet1 = 0;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$count);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['id']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['name']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['username']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['email']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['phone']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['status']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['status']);
                $colSheet1++;

                $count++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0);

            $this->xls->d_load($objPHPExcel,'admin_users');
        }

    }
?>