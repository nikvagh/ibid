<?php
class Profile extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model(ADMINPATH.'profile_model','profile');
            $this->load->library('upload');
            checkLogin('admin');

            // echo "<pre>";
            // print_r($_SESSION);
            // exit;

        }
        function index(){
            $data['title']="Profile";
            $data['edit_profile']=TRUE;
            $data['profile'] = $this->profile->get_user();
            // echo "<pre>";print_r($data['profile']);
            // exit;

            if(isset($_POST['submit'])){

                $config = [
                    [
                            'field' => 'name',
                            'label' => 'Name',
                            'rules' => 'required',
                            'errors' => [
                                    // 'required' => 'Amount is required fields',
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
                    $this->load->view(ADMINPATH.'profile_view.php',$data);
                }else{
                    if ($result = $this->profile->update_profile()) {
                        $this->session->set_flashdata('success', 'Profile Details Updated successfully.');
                    }else{
                        $this->session->set_flashdata('error', 'Something Wrong. Please Try Again');
                    }
                    redirect(ADMINPATH.'profile/');
                }

            }else{
                $this->load->view(ADMINPATH.'profile_view.php',$data);
            }

            
            // $this->load->view(ADMINPATH.'profile_edit.php',$data);
        }

        // function view_profile(){
        //     $data['title']="Profile";
        //     $data['profile'] = $this->profile->get_admin();
        //     $this->load->view(ADMINPATH.'profile_view.php',$data);
        // }
        // function update_profile()
        // {
            // echo "<pre>";
            // print_r($_POST);
            // exit;
            
            // if($_POST['submit']){
                // if ($result = $this->profile->update_profile()) {
                //     $this->session->set_flashdata('success', 'Profile Details Updated successfully.');
                // }else{
                //     $this->session->set_flashdata('error', 'Something Wrong. Please Try Again');
                // }

                // redirect(ADMINPATH.'profile/index/');
            // }
        // }

        function emailCheck_edit(){
            $err = 0;
            $this->db->select('*');
            $this->db->where('email =',$this->input->post('email'));
            $this->db->where('id !=',$this->session->userdata('id'));
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
            $this->db->where('id !=',$this->session->userdata('id'));
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
            $this->db->where('id !=',$this->session->userdata('id'));
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

    }
?>