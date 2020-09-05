<?php
    class Forgotpassword extends CI_Controller {
        function __construct()
        {
            //parent::CI_Controller();
            parent::__construct();

            // $this->load->library('administration');
//			$this->output->enable_profiler(TRUE);
            // checkLogin('admin');
        }
        function index()
        {
            $data['title'] = "Forgot Password";
            if(isset($_POST['submit']) && $_POST['submit'] == "Send")
            {  
                // echo "<pre>";
                // print_r($_POST);
                // exit;

                $this->db->select('*');
                $this->db->from('users u');
                $this->db->where('email', $_POST['email']);
                $query = $this->db->get();

                if ($query->num_rows() > 0) {

                    $row = $query->row();
                    if($row->status == "Enable"){
                        $token_str = random_strings(10);
                        
                        $update_data = array(
                            'remember_token' => $token_str
                        );
                        $this->db->where('email',$_POST['email']);
                        if($this->db->update('users',$update_data)){
                            $body = '<a href="'.base_url().ADMINPATH.'forgotpassword/checkchange/'.$token_str.'" target="_blank">CLick here To Change Password</a>';
                            if(send_mail($_POST['email'],'Forgot Password',$body)){
                                $this->session->set_flashdata('success', 'Verify email To change Password.');
                                $this->load->view(ADMINPATH.'login');
                            }else{
                                $this->session->set_flashdata('error', 'Something Wrong.Please Try Again');
                                $this->load->view(ADMINPATH.'forgot_password', $data);
                            }
                        }

                    }else{
                        $this->session->set_flashdata('error', 'Account Is Deactivate With This Email.');
                        $this->load->view(ADMINPATH.'forgot_password', $data);
                    }
                }else{
                    $this->session->set_flashdata('error', 'Account Not Exist With This Email.');
                    $this->load->view(ADMINPATH.'forgot_password', $data);
                }

            }else{
                $this->load->view(ADMINPATH.'forgot_password', $data);
            }
        }

        function checkchange($token_str)
        {
            $this->db->select('*');
            $this->db->from('users u');
            $this->db->where('remember_token', $token_str);
            $query = $this->db->get();
            if ($query->num_rows() > 0 && $token_str != "") {

                if(isset($_POST['password']) && $_POST['password'] != "" && isset($_POST['confirm_password']) && $_POST['confirm_password'] != "" && isset($_POST['submit']) && $_POST['submit'] == "Change Password"){
                    $update_data = array(
                        'remember_token' => "",
                        'password' => md5($_POST['password']),
                    );
                    $this->db->where('remember_token',$token_str);
                    if($this->db->update('users',$update_data)){
                        $this->session->set_flashdata('success', 'Password Change Successfully.');
                        redirect(ADMINPATH.'login');
                    }
                }else{
                    $data['token_str'] = $token_str;
                    $this->load->view(ADMINPATH.'forgot_change_password', $data);
                }

            }else{
                $this->load->view(ADMINPATH.'not_found');
            }
        }

    }
?>