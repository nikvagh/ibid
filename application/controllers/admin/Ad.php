<?php
    class Ad extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model(ADMINPATH.'ad_model','ad');
            // $this->load->library('upload');
            checkLogin('admin');
            $this->accessibility->check_access('ad');
        }

        function index(){
            $data['ad_manage'] = TRUE;
            $data['title']="Ads";

            if($this->input->post('action') == "change_publish"){
                if ($result = $this->ad->st_update()) {
                    $this->session->set_flashdata('notification', 'Ad status has been update successfully.');
                    redirect(ADMINPATH.'ad');
                }
            }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
                if ($result = $this->member->delete()) {
                    $this->session->set_flashdata('notification', 'Ad deleted successfully.');
                    redirect(ADMINPATH.'ad');
                }
            }
            // elseif ($this->input->post('action') == "deleteselected") {
            //     if ($result = $this->product->deleteselected()) {
            //         $this->session->set_flashdata('notification', 'categoty has been deleted successfully.');
            //         redirect('product');
            //     }
            // }
            
            $data['manage_data'] = $this->ad->get_ads();
            $this->load->view(ADMINPATH.'ad/list',$data);
        }

        function add(){ 
            $data['ad_form'] = TRUE;
            $data['action']='add';
            $data['title']="Ads";
            
            if(isset($_POST['submit'])){

                // $config = [
                //     [
                //             'field' => 'name',
                //             'label' => 'Name',
                //             'rules' => 'required',
                //             'errors' => [
                //                     // 'required' => 'Member Id Missing.',
                //             ],
                //     ],
                //     [
                //             'field' => 'username',
                //             'label' => 'Username',
                //             'rules' => 'required|callback_usernameCheck_add',
                //             'errors' => [
                //                     // 'required' => 'Amount is required fields',
                //             ],
                //     ],
                //     [
                //             'field' => 'phone',
                //             'label' => 'Phone',
                //             'rules' => 'required|numeric|callback_phoneCheck_add',
                //             'errors' => [
                //                     // 'required' => 'Amount is required fields',
                //             ],
                //     ],
                //     [
                //             'field' => 'email',
                //             'label' => 'email',
                //             'rules' => 'required|callback_chk_valid_email|callback_emailCheck_add',
                //             'errors' => [
                //                     // 'required' => 'Member Id Missing.',
                //             ],
                //     ],
                //     [
                //             'field' => 'password',
                //             'label' => 'Password',
                //             'rules' => 'required|min_length[6]',
                //             'errors' => [
                //                     // 'required' => 'Password is Required',
                //             ],
                //     ],
                //     [
                //             'field' => 'confirm_password',
                //             'label' => 'Confirm Password',
                //             'rules' => 'required|matches[password]',
                //             'errors' => [
                //                     // 'required' => 'Confirmn Password is Required',
                //             ],
                //     ],
                    
                // ];
    
                // $this->form_validation->set_data($_POST);
                // $this->form_validation->set_rules($config);
            
                // if ($this->form_validation->run() == FALSE)
                // {
                //     $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                //     $this->load->view(ADMINPATH.'ad/add',$data); 
                // }else{
                    if($this->ad->insert()) {
                        $this->session->set_flashdata('notification', 'Ad information has been insert successfully.');
                        redirect(ADMINPATH.'ad');
                    }
                // }
                
            }else{
                $this->load->view(ADMINPATH.'ad/add',$data); 
            }
        }

        function edit(){
            $data['ad_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Admin Ad";
            // $data['companies'] = $this->ad->get_companies();
            $data['form_data'] = $this->ad->getDataById($this->uri->segment(4));
            
            if(isset($_POST['submit'])){

                // $config = [
                //     [
                //             'field' => 'name',
                //             'label' => 'Name',
                //             'rules' => 'required',
                //             'errors' => [
                //                     // 'required' => 'Member Id Missing.',
                //             ],
                //     ],
                //     [
                //             'field' => 'username',
                //             'label' => 'Username',
                //             'rules' => 'required|callback_usernameCheck_edit',
                //             'errors' => [
                //                     // 'required' => 'Amount is required fields',
                //             ],
                //     ],
                //     [
                //             'field' => 'phone',
                //             'label' => 'Phone',
                //             'rules' => 'required|numeric|callback_phoneCheck_edit',
                //             'errors' => [
                //                     // 'required' => 'Amount is required fields',
                //             ],
                //     ],
                //     [
                //             'field' => 'email',
                //             'label' => 'email',
                //             'rules' => 'required|callback_chk_valid_email|callback_emailCheck_edit',
                //             'errors' => [
                //                     // 'required' => 'Member Id Missing.',
                //             ],
                //     ],
                //     [
                //             'field' => 'password',
                //             'label' => 'Password',
                //             'rules' => 'min_length[6]',
                //             'errors' => [
                //                     // 'required' => 'Password is Required',
                //             ],
                //     ],
                //     [
                //             'field' => 'confirm_password',
                //             'label' => 'Confirm Password',
                //             'rules' => 'matches[password]',
                //             'errors' => [
                //                     // 'required' => 'Confirmn Password is Required',
                //             ],
                //     ],
                    
                // ];
                // $this->form_validation->set_data($_POST);
                // $this->form_validation->set_rules($config);
            
                // if ($this->form_validation->run() == FALSE)
                // {
                //     $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                //     $this->load->view(ADMINPATH.'ad/edit',$data); 
                // }else{
                    if ($result = $this->ad->update()) {
                        $this->session->set_flashdata('notification','Ad information has been update successfully.');
                        redirect(ADMINPATH.'ad');
                    }
                // }
            }else{
                $this->load->view(ADMINPATH.'ad/edit',$data);
            }
        }

        // function view(){
        //     $data['user_form'] = TRUE;
        //     $data['action']="edit";
        //     $data['title']="Member";
        //     $data['form_data'] = $this->member->getDataById($this->uri->segment(4));
        //     $this->load->view(ADMINPATH.'member/view',$data); 
        // }

        function filter(){
            // echo "<pre>";
            // print_r($_POST);

            if($_POST['submit'] == 'Apply'){
                $_SESSION['ad']['filter_date_start'] = $_POST['filter_date_start'];
                $_SESSION['ad']['filter_date_end'] = $_POST['filter_date_end'];
            }else if($_POST['submit'] == 'Reset'){
                unset($_SESSION['ad']);
            }

            redirect(ADMINPATH.'ad');
        }

        public function valid_url_format(){
            // echo "<pre>";
            // print_r($_GET);
            // exit;
            // $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
            $pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
            if (!preg_match($pattern,trim($_GET['target']))){
                // $this->form_validation->set_message('valid_url_format', 'The URL you entered is not correctly formatted.');
                // return FALSE;
                echo json_encode(false);
                exit;
            }
            echo json_encode(true);
            exit;
        }

        function exportXLS(){
            $data = $this->ad->get_ads();
            $this->load->library('xls');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            
            $rowSheet1 = 1;$colSheet1 = 0;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'#');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Id');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Title');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Banner');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Target Url');
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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['title']);
                $colSheet1++;

                if (file_exists(ADBNR_PATH . 'thumb/50x50_' . $val['pic'])) {
                    $profile_pic = base_url() . ADBNR_PATH . 'thumb/50x50_' . $val['pic'];
                } else {
                    $profile_pic = "";
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$profile_pic);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['target']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['status']);
                $colSheet1++;

                $count++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0);

            $this->xls->d_load($objPHPExcel,'ads');
        }

    }
?>