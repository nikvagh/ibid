<?php
    class Plan extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model('plan_model','plan');
            $this->load->model('product_model','product');
            $this->load->library('upload');
            $this->load->library('administration');
        }

        function index(){
            $data['plan_manage'] = TRUE;
            $data['title']="Plan";

            if($this->input->post('action') == "change_publish"){
                if ($result = $this->plan->st_update()) {
                    $this->session->set_flashdata('notification', 'Plan status has been update successfully.');
                    redirect('plan');
                }
            }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
                if ($result = $this->plan->delete()) {
                    $this->session->set_flashdata('notification', 'Plan deleted successfully.');
                    redirect('plan');
                }
            }
            // elseif ($this->input->post('action') == "deleteselected") {
            //     if ($result = $this->plan->deleteselected()) {
            //         $this->session->set_flashdata('notification', 'categoty has been deleted successfully.');
            //         redirect('plan');
            //     }
            // }
            
            $data['manage_data'] = $this->plan->get_plans();
            $this->load->view('plan/list',$data);
        }

        function add(){ 
            $data['plan_form'] = TRUE;
            $data['action']='add';
            $data['title']="Plan";
            $data['companies'] = $this->product->get_companies();
            // $data['products'] = $this->product->get_all_products();
            
            if(isset($_POST['submit'])){
                if ($this->plan->insert()) {
                    $this->session->set_flashdata('notification', 'Plan information has been insert successfully.');
                    redirect('plan');
                }
            // }elseif($this->input->post('cancel')){
            //     redirect('plan');
            }else{
                $data['category_addedit'] = TRUE;
                $this->load->view('plan/add',$data); 
            }
        }

        function edit(){
            $data['plan_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Plan";

            $data['form_data'] = $this->plan->getDataById($this->uri->segment(3));
            $data['companies'] = $this->product->get_companies();

            // echo $this->uri->segment(3);
            // echo "<pre>";print_r($data['form_data']);exit;

            $data['products'] = array();
            if($data['form_data']['product_id'] != ""){
                $data['products'] = $this->product->get_product_by_compnayId($data['form_data']['company_id']);
            }
            
            if(isset($_POST['submit'])){
                if ($result = $this->plan->update()) {
                    $this->session->set_flashdata('notification','Plan information has been update successfully.');
                    redirect('plan');
                }
            // }elseif($this->input->post('cancel')){
            //         redirect('plan');
            }else{
                // echo $this->uri->segment(3);exit;
                // $data['form_data'] = $this->plan->getDataById($this->uri->segment(3));
                $data['category_addedit'] = TRUE;
                $this->load->view('plan/edit',$data); 
            }
        }

        function get_product_by_compnayId(){
            // echo $_POST['company_id'];
            $products = $this->product->get_product_by_compnayId($_POST['company_id']);
            echo json_encode($products);
        }

    }
?>