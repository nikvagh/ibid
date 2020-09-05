<?php
    class Fee extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model(ADMINPATH.'fee_model','fee');
            // $this->load->library('upload');
            checkLogin('admin');
            $this->load->library('accessibility');
            $this->accessibility->check_access('fee');
        }

        function index(){
            $data['fee_manage'] = TRUE;
            $data['title']="Fees";

            if($this->input->post('action') == "change_publish"){
                if ($result = $this->fee->st_update()) {
                    $this->session->set_flashdata('notification', 'Fee status has been update successfully.');
                    redirect(ADMINPATH.'fee');
                }
            }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
                if ($result = $this->member->delete()) {
                    $this->session->set_flashdata('notification', 'Fee deleted successfully.');
                    redirect(ADMINPATH.'fee');
                }
            }
            // elseif ($this->input->post('action') == "deleteselected") {
            //     if ($result = $this->product->deleteselected()) {
            //         $this->session->set_flashdata('notification', 'categoty has been deleted successfully.');
            //         redirect('product');
            //     }
            // }
            
            $data['manage_data'] = $this->fee->get_fees();
            $this->load->view(ADMINPATH.'fee/list',$data);
        }

        function add(){ 
            $data['fee_form'] = TRUE;
            $data['action']='add';
            $data['title']="Fees";
            // $data['companies'] = $this->fee->get_companies();
            
            if(isset($_POST['submit'])){
                if ($this->fee->insert()) {
                    $this->session->set_flashdata('notification', 'Fee information has been insert successfully.');
                    redirect(ADMINPATH.'fee');
                }
            // }elseif($this->input->post('cancel')){
            //     redirect('product');
            }else{
                $data['category_addedit'] = TRUE;
                $this->load->view(ADMINPATH.'fee/add',$data); 
            }
        }

        function edit(){
            $data['fee_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Fees";
            // $data['companies'] = $this->fee->get_companies();
            
            if(isset($_POST['submit'])){
                if ($result = $this->fee->update()) {
                    $this->session->set_flashdata('notification','Fee information has been update successfully.');
                    redirect(ADMINPATH.'fee');
                }
            // }elseif($this->input->post('cancel')){
            //         redirect('product');
            }else{
                // echo $this->uri->segment(3);exit;
                $data['form_data'] = $this->fee->getDataById($this->uri->segment(4));
                $data['category_addedit'] = TRUE;
                $this->load->view(ADMINPATH.'fee/edit',$data);
            }
        }

        // function view(){
        //     $data['fee_form'] = TRUE;
        //     $data['action']="edit";
        //     $data['title']="Member";
        //     $data['form_data'] = $this->member->getDataById($this->uri->segment(4));
        //     $this->load->view(ADMINPATH.'member/view',$data); 
        // }

        function filter(){
            // echo "<pre>";
            // print_r($_POST);

            if($_POST['submit'] == 'Apply'){
                $_SESSION['fee']['filter_date_start'] = $_POST['filter_date_start'];
                $_SESSION['fee']['filter_date_end'] = $_POST['filter_date_end'];
            }else if($_POST['submit'] == 'Reset'){
                unset($_SESSION['fee']);
            }

            redirect(ADMINPATH.'fee');
        }

        function exportXLS(){
            $data = $this->fee->get_fees();
            $this->load->library('xls');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            
            $rowSheet1 = 1;$colSheet1 = 0;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'#');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Id');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Start Amount');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'To Amount');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Fees');
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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['start_amount']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['to_amount']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['fee_amount']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['status']);
                $colSheet1++;

                $count++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0);

            $this->xls->d_load($objPHPExcel,'fee');
        }

    }
?>