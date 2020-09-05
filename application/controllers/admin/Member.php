<?php
    class Member extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model(ADMINPATH.'member_model','member');
            checkLogin('admin');
            $this->load->library('accessibility');
            $this->accessibility->check_access('member');
        }

        function index(){
            $data['member_manage'] = TRUE;
            $data['title']="Members";

            if($this->input->post('action') == "change_publish"){
                if ($result = $this->member->st_update()) {
                    $this->session->set_flashdata('notification', 'Member status has been update successfully.');
                    redirect(ADMINPATH.'member');
                }
            }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
                if ($result = $this->member->delete()) {
                    $this->session->set_flashdata('notification', 'Member deleted successfully.');
                    redirect(ADMINPATH.'member');
                }
            }
            // elseif ($this->input->post('action') == "deleteselected") {
            //     if ($result = $this->product->deleteselected()) {
            //         $this->session->set_flashdata('notification', 'categoty has been deleted successfully.');
            //         redirect('product');
            //     }
            // }
            
            $data['manage_data'] = $this->member->get_members();
            $this->load->view(ADMINPATH.'member/list',$data);
        }

        function add(){ 
            $data['member_form'] = TRUE;
            $data['action']='add';
            $data['title']="Members";
            $data['companies'] = $this->member->get_companies();
            
            if(isset($_POST['submit'])){
                if ($this->member->insert()) {
                    $this->session->set_flashdata('notification', 'Member information has been insert successfully.');
                    redirect('member');
                }
            // }elseif($this->input->post('cancel')){
            //     redirect('product');
            }else{
                $data['member_addedit'] = TRUE;
                $this->load->view('member/add',$data); 
            }
        }

        function edit(){
            $data['member_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Member";
            // $data['companies'] = $this->member->get_companies();
            
            $data['form_data'] = $this->member->getDataById($this->uri->segment(4));
            $data['member_addedit'] = TRUE;
            
            if(isset($_POST['submit'])){

                $config = [
                    [
                            'field' => 'password',
                            'label' => 'password',
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
                    $this->load->view(ADMINPATH.'member/edit',$data); 
                }else{
                    if ($result = $this->member->update()) {
                        $this->session->set_flashdata('notification','Member information has been update successfully.');
                        redirect(ADMINPATH.'member');
                    }
                }

            // }elseif($this->input->post('cancel')){
            //         redirect('product');
            }else{
                // echo $this->uri->segment(3);exit;
                $this->load->view(ADMINPATH.'member/edit',$data); 
            }
        }

        function view(){
            $data['member_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Member";
            // $data['companies'] = $this->member->get_companies();
            
            // if(isset($_POST['submit'])){
            //     if ($result = $this->member->update()) {
            //         $this->session->set_flashdata('notification','Member information has been update successfully.');
            //         redirect('member');
            //     }
            // }elseif($this->input->post('cancel')){
            //         redirect('product');
            // }else{
                // echo $this->uri->segment(3);exit;
                $data['form_data'] = $this->member->getDataById($this->uri->segment(4));
                // echo "<pre>";
                // print_r($data);

                $this->load->view(ADMINPATH.'member/view',$data); 
            // }
        }

        function filter(){
            // echo "<pre>";
            // print_r($_POST);

            if($_POST['submit'] == 'Apply'){
                $_SESSION['member']['filter_date_start'] = $_POST['filter_date_start'];
                $_SESSION['member']['filter_date_end'] = $_POST['filter_date_end'];
            }else if($_POST['submit'] == 'Reset'){
                unset($_SESSION['member']);
            }

            redirect(ADMINPATH.'member');
        }


        function notification_modal(){
            // $_POST['id'];
            $data['member_id'] = $_POST['id'];
            $this->load->view(ADMINPATH.'member/notification',$data); 
        }

        function exportXLS(){
            $data = $this->member->get_members();
            $this->load->library('xls');
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            // Create a first sheet, representing sales data
            $objPHPExcel->setActiveSheetIndex(0);
            // Rename sheet
            
            $rowSheet1 = 1;$colSheet1 = 0;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'#');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Id');
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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['email']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['phone']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['status']);
                $colSheet1++;

                // if($val['status']=='Enable'){
                //     if($val['progress_status'] == '0'){
                //         $progress_status = 'OnGoing';
                //     }else if($val['progress_status'] == '1'){
                //         $progress_status = 'Completed';
                //     }
                // }else{
                //     $progress_status = 'Waiting For Approval';
                // }

                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$progress_status);
                $colSheet1++;
                $count++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0);

            $this->xls->d_load($objPHPExcel,'member');
        }

    }
?>