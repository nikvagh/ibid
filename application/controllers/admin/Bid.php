<?php
    class Bid extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model(ADMINPATH.'bid_model','bid');
            // $this->load->library('upload');
            checkLogin('admin');
            $this->load->library('accessibility');
            $this->accessibility->check_access('bid');
        }

        function index(){
            // echo date('Y-m-d H:i:s');
            $data['bid_manage'] = TRUE;
            $data['title']="Bids";

            if($this->input->post('action') == "change_publish"){
                if ($result = $this->bid->st_update()) {
                    $this->session->set_flashdata('notification', 'Bid status has been update successfully.');
                    redirect(ADMINPATH.'bid');
                }
            }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
                if ($result = $this->bid->delete()) {
                    $this->session->set_flashdata('notification', 'Bid deleted successfully.');
                    redirect(ADMINPATH.'bid');
                }
            }
            // elseif ($this->input->post('action') == "deleteselected") {
            //     if ($result = $this->product->deleteselected()) {
            //         $this->session->set_flashdata('notification', 'categoty has been deleted successfully.');
            //         redirect('product');
            //     }
            // }
            
            $data['manage_data'] = $this->bid->get_bids();
            $this->load->view(ADMINPATH.'bid/list',$data);
        }

        function add(){ 
            $data['bid_form'] = TRUE;
            $data['action']='add';
            $data['title']="Bids";
            $data['companies'] = $this->bid->get_companies();
            
            if(isset($_POST['submit'])){
                if ($this->bid->insert()) {
                    $this->session->set_flashdata('notification', 'Bid information has been insert successfully.');
                    redirect('bid');
                }
            // }elseif($this->input->post('cancel')){
            //     redirect('product');
            }else{
                $data['category_addedit'] = TRUE;
                $this->load->view('bid/add',$data); 
            }
        }

        function edit(){
            $data['bid_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Bid";
            // $data['companies'] = $this->bid->get_companies();
            
            if(isset($_POST['submit'])){
                if ($result = $this->bid->update()) {
                    $this->session->set_flashdata('notification','Bid information has been update successfully.');
                    redirect('bid');
                }
            // }elseif($this->input->post('cancel')){
            //         redirect('product');
            }else{
                // echo $this->uri->segment(3);exit;
                $data['form_data'] = $this->bid->getDataById($this->uri->segment(3));
                $data['category_addedit'] = TRUE;
                $this->load->view('bid/edit',$data); 
            }
        }

        function view(){
            $data['bid_view'] = TRUE;
            $data['action']="view";
            $data['title']="Bid";
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
                $data['form_data'] = $this->bid->getDataById($this->uri->segment(4));
                $data['placed_bids'] = $this->bid->getPlacedBidListById($this->uri->segment(4));

                if($this->uri->segment(5) == 'view'){
                    $data['active_tab'] = 'general';
                }else if($this->uri->segment(5) == 'filter'){
                    $data['active_tab'] = 'place_bid';
                }
                
                // echo "<pre>";
                // print_r($data);
                // exit;

                $this->load->view(ADMINPATH.'bid/view',$data); 
            // }
        }

        function filter(){
            // echo "<pre>";
            // print_r($_POST);

            if($_POST['submit'] == 'Apply'){
                $_SESSION['bid']['filter_date_start'] = $_POST['filter_date_start'];
                $_SESSION['bid']['filter_date_end'] = $_POST['filter_date_end'];
                $_SESSION['bid']['filter_progress_status'] = $_POST['filter_progress_status'];
            }else if($_POST['submit'] == 'Reset'){
                unset($_SESSION['bid']);
            }

            redirect(ADMINPATH.'bid');
        }

        function filter_place_bid($id){
            // echo "<pre>";
            // print_r($_POST);

            if($_POST['submit'] == 'Apply'){
                $_SESSION['place_bid']['filter_date_start'] = $_POST['filter_date_start'];
                $_SESSION['place_bid']['filter_date_end'] = $_POST['filter_date_end'];
                $_SESSION['place_bid']['filter_date_end'] = $_POST['filter_date_end'];
            }else if($_POST['submit'] == 'Reset'){
                unset($_SESSION['place_bid']);
            }

            redirect(ADMINPATH.'bid/view/'.$id.'/filter');
        }

        function exportXLS(){ 

            $data = $this->bid->get_bids();
            // include('./PHPExcel/PHPExcel.php');
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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Added By');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Number Type');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Number Sub Type');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Number');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Upgrade Type');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Starting Bid Amount');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Duration (Days)');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Fee');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Coupon');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Bid End Date & Time');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Purchaser');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Progress Status');
            $colSheet1++;

            $count = 1;
            foreach($data as $val){
                $rowSheet1++;$colSheet1 = 0;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$count);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['id']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['added_by_name']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['numbertype_str']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['numbersubtype_str']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['number']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['upgrade_type']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['starting_bid_amount']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['duration_str']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['fee_str']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['coupon']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['bid_end_datetime']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['purchaser_name']);
                $colSheet1++;

                if($val['status']=='Enable'){
                    if($val['progress_status'] == '0'){
                        $progress_status = 'OnGoing';
                    }else if($val['progress_status'] == '1'){
                        $progress_status = 'Completed';
                    }
                }else{
                    $progress_status = 'Waiting For Approval';
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$progress_status);
                $colSheet1++;

                $count++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0);

            $this->xls->d_load($objPHPExcel,'bid');
    
            // Redirect output to a client’s web browser (Excel5)
            // header('Content-Type: application/vnd.ms-excel');
            // header('Content-Disposition: attachment;filename="bid.xls"');
            // header('Cache-Control: max-age=0');
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            // $objWriter->save('php://output');
            // exit;
        }

        function placed_bid_exportXLS($id){

            $data = $this->bid->getPlacedBidListById($id);

            $this->load->library('xls');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            
            $rowSheet1 = 1;$colSheet1 = 0;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'#');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Member');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Amount');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Date & Time');
            $colSheet1++;

            $count = 1;
            foreach($data as $val){
                $rowSheet1++;$colSheet1 = 0;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$count);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['name']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['amount']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['created_at']);
                $colSheet1++;

                $count++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0);
            $this->xls->d_load($objPHPExcel,'placed_bid');

        }

    }
?>