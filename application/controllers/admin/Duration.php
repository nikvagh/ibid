<?php
    class Duration extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model(ADMINPATH.'duration_model','duration');
            // $this->load->library('upload');
            checkLogin('admin');
            $this->load->library('accessibility');
            $this->accessibility->check_access('duration');
        }

        function index(){
            $data['duration_manage'] = TRUE;
            $data['title']="Days";

            if($this->input->post('action') == "change_publish"){
                if ($result = $this->duration->st_update()) {
                    $this->session->set_flashdata('notification', 'Duration status has been update successfully.');
                    redirect(ADMINPATH.'duration');
                }
            }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
                if ($result = $this->member->delete()) {
                    $this->session->set_flashdata('notification', 'Duration deleted successfully.');
                    redirect(ADMINPATH.'duration');
                }
            }
            // elseif ($this->input->post('action') == "deleteselected") {
            //     if ($result = $this->product->deleteselected()) {
            //         $this->session->set_flashdata('notification', 'categoty has been deleted successfully.');
            //         redirect('product');
            //     }
            // }
            
            $data['manage_data'] = $this->duration->get_durations();
            $this->load->view(ADMINPATH.'duration/list',$data);
        }

        function add(){ 
            $data['duration_form'] = TRUE;
            $data['action']='Days';
            $data['title']="Day";
            // $data['companies'] = $this->duration->get_companies();
            
            if(isset($_POST['submit'])){
                if ($this->duration->insert()) {
                    $this->session->set_flashdata('notification', 'Duration information has been insert successfully.');
                    redirect(ADMINPATH.'duration');
                }
            // }elseif($this->input->post('cancel')){
            //     redirect('product');
            }else{
                $data['category_addedit'] = TRUE;
                $this->load->view(ADMINPATH.'duration/add',$data); 
            }
        }

        function edit(){
            $data['duration_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Day";
            // $data['companies'] = $this->duration->get_companies();
            
            if(isset($_POST['submit'])){
                if ($result = $this->duration->update()) {
                    $this->session->set_flashdata('notification','Duration information has been update successfully.');
                    redirect(ADMINPATH.'duration');
                }
            // }elseif($this->input->post('cancel')){
            //         redirect('product');
            }else{
                // echo $this->uri->segment(3);exit;
                $data['form_data'] = $this->duration->getDataById($this->uri->segment(4));
                $data['category_addedit'] = TRUE;
                $this->load->view(ADMINPATH.'duration/edit',$data);
            }
        }

        // function view(){
        //     $data['duration_form'] = TRUE;
        //     $data['action']="edit";
        //     $data['title']="Member";
        //     $data['form_data'] = $this->member->getDataById($this->uri->segment(4));
        //     $this->load->view(ADMINPATH.'member/view',$data); 
        // }

        function filter(){
            // echo "<pre>";
            // print_r($_POST);

            if($_POST['submit'] == 'Apply'){
                $_SESSION['duration']['filter_date_start'] = $_POST['filter_date_start'];
                $_SESSION['duration']['filter_date_end'] = $_POST['filter_date_end'];
            }else if($_POST['submit'] == 'Reset'){
                unset($_SESSION['duration']);
            }

            redirect(ADMINPATH.'duration');
        }

        function exportXLS(){
            $data = $this->duration->get_durations();
            $this->load->library('xls');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            
            $rowSheet1 = 1;$colSheet1 = 0;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'#');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Id');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Number of Days');
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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['no_of_days']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['status']);
                $colSheet1++;

                $count++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0);

            $this->xls->d_load($objPHPExcel,'days');
        }

    }
?>