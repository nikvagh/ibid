<?php
    class Coupon extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model(ADMINPATH.'coupon_model','coupon');
            $this->load->model(ADMINPATH.'member_model','member');
            // $this->load->library('upload');
            checkLogin('admin');
            $this->load->library('accessibility');
            $this->accessibility->check_access('coupon');
        }

        function index(){
            $data['coupon_manage'] = TRUE;
            $data['title']="Coupon";

            if($this->input->post('action') == "change_publish"){
                if ($result = $this->coupon->st_update()) {
                    $this->session->set_flashdata('notification', 'Coupon status has been update successfully.');
                    redirect(ADMINPATH.'coupon');
                }
            }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
                if ($result = $this->member->delete()) {
                    $this->session->set_flashdata('notification', 'Coupon deleted successfully.');
                    redirect(ADMINPATH.'coupon');
                }
            }
            // elseif ($this->input->post('action') == "deleteselected") {
            //     if ($result = $this->product->deleteselected()) {
            //         $this->session->set_flashdata('notification', 'categoty has been deleted successfully.');
            //         redirect('product');
            //     }
            // }
            
            $data['manage_data'] = $this->coupon->get_coupons();
            $this->load->view(ADMINPATH.'coupon/list',$data);
        }

        function add(){ 
            $data['coupon_form'] = TRUE;
            $data['action']='add';
            $data['title']="Coupon";
            $data['members'] = $this->member->get_members();
            
            if(isset($_POST['submit'])){

                $config = [
                    [
                            'field' => 'code',
                            'label' => 'Code',
                            'rules' => 'required|callback_couponcheck',
                            'errors' => [
                                    // 'required' => 'Member Id Missing.',
                            ],
                    ],
                    [
                            'field' => 'amount',
                            'label' => 'Amount',
                            'rules' => 'required|numeric|callback_amountcheck',
                            'errors' => [
                                    // 'required' => 'Amount is required fields',
                                    'numeric' => 'Enter valid amount',
                            ],
                    ],
                    [
                            'field' => 'amount_type',
                            'label' => 'Amount Type',
                            'rules' => 'required',
                            'errors' => [
                                    // 'required' => 'Amount Type is required fields',
                            ],
                    ],
                    [
                            'field' => 'discount_on',
                            'label' => 'Discount On',
                            'rules' => 'required',
                            'errors' => [
                                    // 'required' => 'Amount Type is required fields',
                            ],
                    ],
                    [
                            'field' => 'users',
                            'label' => 'Apply On',
                            'rules' => 'required',
                            'errors' => [
                                    // 'required' => 'Amount Type is required fields',
                            ],
                    ],
                    [
                            'field' => 'member_id',
                            'label' => 'User',
                            'rules' => 'callback_check_user_type',
                            'errors' => [
                                    // 'required' => 'Amount Type is required fields',
                            ],
                    ],
                    [
                            'field' => 'expiry_date',
                            'label' => 'Expiry date',
                            'rules' => 'required',
                            'errors' => [
                                    // 'required' => 'Expiry is required fields',
                            ],
                    ]
                    
                ];
    
                $this->form_validation->set_data($_POST);
                $this->form_validation->set_rules($config);
            
                if ($this->form_validation->run() == FALSE)
                {
                    $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                    $this->load->view(ADMINPATH.'coupon/add',$data); 
                }else{
                    if($this->coupon->insert()) {
                        $this->session->set_flashdata('notification', 'Coupon information has been insert successfully.');
                        redirect(ADMINPATH.'coupon');
                    }
                }
                
            // }elseif($this->input->post('cancel')){
            //     redirect('product');
            }else{
                $this->load->view(ADMINPATH.'coupon/add',$data); 
            }
        }

        function edit(){
            $data['coupon_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Coupon";
            $data['members'] = $this->member->get_members();
            $data['form_data'] = $this->coupon->getDataById($this->uri->segment(4));
            
            if(isset($_POST['submit'])){

                $config = [
                    [
                            'field' => 'code',
                            'label' => 'Code',
                            'rules' => 'required|callback_couponcheck_edit',
                            'errors' => [
                                    // 'required' => 'Member Id Missing.',
                            ],
                    ],
                    [
                            'field' => 'amount',
                            'label' => 'Amount',
                            'rules' => 'required|numeric|callback_amountcheck',
                            'errors' => [
                                    // 'required' => 'Amount is required fields',
                                    'numeric' => 'Enter valid amount',
                            ],
                    ],
                    [
                            'field' => 'amount_type',
                            'label' => 'Amount Type',
                            'rules' => 'required',
                            'errors' => [
                                    // 'required' => 'Amount Type is required fields',
                            ],
                    ],
                    [
                        'field' => 'discount_on',
                        'label' => 'Discount On',
                        'rules' => 'required',
                        'errors' => [
                                // 'required' => 'Amount Type is required fields',
                        ],
                    ],
                    [
                            'field' => 'users',
                            'label' => 'Apply On',
                            'rules' => 'required',
                            'errors' => [
                                    // 'required' => 'Amount Type is required fields',
                            ],
                    ],
                    [
                            'field' => 'member_id',
                            'label' => 'User',
                            'rules' => 'callback_check_user_type',
                            'errors' => [
                                    // 'required' => 'Amount Type is required fields',
                            ],
                    ],
                    [
                            'field' => 'expiry_date',
                            'label' => 'Expiry date',
                            'rules' => 'required',
                            'errors' => [
                                    // 'required' => 'Expiry is required fields',
                            ],
                    ]
                    
                ];
                $this->form_validation->set_data($_POST);
                $this->form_validation->set_rules($config);
            
                if ($this->form_validation->run() == FALSE)
                {
                    $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                    $this->load->view(ADMINPATH.'coupon/edit',$data); 
                }else{
                    if ($result = $this->coupon->update()) {
                        $this->session->set_flashdata('notification','Coupon information has been update successfully.');
                        redirect(ADMINPATH.'coupon');
                    }
                }
            }else{
                // echo $this->uri->segment(3);exit;
                $this->load->view(ADMINPATH.'coupon/edit',$data);
            }
        }

        // function view(){
        //     $data['coupon_form'] = TRUE;
        //     $data['action']="edit";
        //     $data['title']="Member";
        //     $data['form_data'] = $this->member->getDataById($this->uri->segment(4));
        //     $this->load->view(ADMINPATH.'member/view',$data); 
        // }

        public function amountcheck(){
            if($this->input->post('amount') > 100 && $this->input->post('amount_type') == '0'){
                $this->form_validation->set_message('amountcheck', 'Percentage Must Be Less Than or Equal to 100');
                return false;
            }else{
                return true;
            }
        }

        public function couponcheck(){
            $this->db->select('*');
            $this->db->where('code =',$this->input->post('code'));
            // $this->db->where('id !=',$this->input->post('member_id'));
            $query1 = $this->db->get('coupons');
            // echo $this->db->last_query();
            if ($query1->num_rows() > 0) {
                $this->form_validation->set_message('couponcheck', 'Coupon Code Already Exist');
                return false;
            }else{
                return true;
            }
        }

        public function couponcheck_edit(){
            $this->db->select('*');
            $this->db->where('code =',$this->input->post('code'));
            $this->db->where('id !=',$this->input->post('id'));
            $query1 = $this->db->get('coupons');
            // echo $this->db->last_query();exit;
            if ($query1->num_rows() > 0) {
                $this->form_validation->set_message('couponcheck_edit', 'Coupon Code Already Exist');
                return false;
            }else{
                return true;
            }
        }

        function filter(){
            // echo "<pre>";
            // print_r($_POST);

            if($_POST['submit'] == 'Apply'){
                $_SESSION['coupon']['filter_date_start'] = $_POST['filter_date_start'];
                $_SESSION['coupon']['filter_date_end'] = $_POST['filter_date_end'];
            }else if($_POST['submit'] == 'Reset'){
                unset($_SESSION['coupon']);
            }

            redirect(ADMINPATH.'coupon');
        }

        function check_user_type(){
            if($_POST['users'] == '1'){
                if(isset($_POST['member_id']) && $_POST['member_id'] == ""){
                    $this->form_validation->set_message('check_user_type', 'User is Required!');
                    return false;
                }
            }
            return true;
        }

        function exportXLS(){
            $data = $this->coupon->get_coupons();
            $this->load->library('xls');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            
            $rowSheet1 = 1;$colSheet1 = 0;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'#');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Id');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Code');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Amount');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Discount Type');
            $colSheet1++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,'Expiry Date');
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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['code']);
                $colSheet1++;
                
                if($val['amount_type'] == '0'){ 
                    $amount = $val['amount'].' %'; 
                }else if($val['amount_type'] == '1'){ 
                    $amount = $val['amount'].' QAR'; 
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$amount);
                $colSheet1++;

                if($val['amount_type'] == '0'){
                    $amount_type = "Percentage";
                }else if($val['amount_type'] == '1'){
                    $amount_type = "Amount";
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$amount_type);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['expiry_date']);
                $colSheet1++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colSheet1,$rowSheet1,$val['status']);
                $colSheet1++;
                $count++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0);

            $this->xls->d_load($objPHPExcel,'coupons');
        }

    }
?>