<?php
    class Login extends CI_Controller {
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
            // if ($this->member->logged_in) 
            if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == "admin")
            {
                // $data['title'] = "Dashboard";
                // $this->load->view(ADMINPATH.'dashboard', $data);
                redirect(ADMINPATH.'dashboard','location');
            }else{
                $data['title'] = "Login";
                $this->load->view(ADMINPATH.'login', $data);
            }
        }
        function dologin()
        {
            // $this->validation->set_rules('username', 'Username', 'required');
            // if ($this->validation->run() == FALSE)
            // {
            //     echo "1111";
            //         // $this->load->view('myform');
            // }
            // else
            // {
            //         echo "222";
            //         // $this->load->view('formsuccess');
            // }

            // if ($this->admin->logged_in)
            if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == "admin")
            {
                // echo "111";
                // exit;
                // $data['title'] = "Dashboard";
                // $this->load->view(ADMINPATH.'index',$data);
                redirect(ADMINPATH.'dashboard','location');
            } else {
                // echo "222";
                // exit;
                if (!$this->input->post('submit'))
                {
                    // echo "333";
                    // exit;
                    $data['title'] = "Login";
                    $this->load->view(ADMINPATH.'login', $data);
                } else {
                    // echo "444";
                    // exit;
                    // $this->logout1();
                    $username = $this->input->post('username');
                    $password = $this->input->post('password');

                    if ($this->admin->login($username, $password))
                    {
                        // echo "555";
                        // exit;
                        redirect(ADMINPATH.'dashboard','location');
                    } else {
                        $data['title'] = "Login";
                        $this->session->set_flashdata('error', 'Invalid Login. Please try again.');
                        $this->load->view(ADMINPATH.'login', $data);
                    }
                }
            }
        }

        function logout()
        {
            $this->admin->logout();
            redirect(ADMINPATH.'login');
        }

        // function logout1()
        // {
        //     $this->admin->logout();
        //     $this->member->logout();
        // }

    }
?>