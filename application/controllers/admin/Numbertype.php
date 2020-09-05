<?php
    class Numbertype extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model(ADMINPATH.'numbertype_model','numbertype');
            // $this->load->library('upload');
            checkLogin('admin');
            $this->load->library('accessibility');
            $this->accessibility->check_access('numbertype');
        }

        function index(){
            $data['numbertype_manage'] = TRUE;
            $data['title']="Numbers Types";

            if($this->input->post('action') == "change_publish"){
                if ($result = $this->numbertype->st_update()) {
                    $this->session->set_flashdata('notification', 'Number Type status has been update successfully.');
                    redirect(ADMINPATH.'numbertype');
                }
            }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
                if ($result = $this->member->delete()) {
                    $this->session->set_flashdata('notification', 'Number Type deleted successfully.');
                    redirect(ADMINPATH.'numbertype');
                }
            }
            // elseif ($this->input->post('action') == "deleteselected") {
            //     if ($result = $this->product->deleteselected()) {
            //         $this->session->set_flashdata('notification', 'categoty has been deleted successfully.');
            //         redirect('product');
            //     }
            // }
            
            $data['manage_data'] = $this->numbertype->get_numbertypes();
            $this->load->view(ADMINPATH.'numbertype/list',$data);
        }

        function add(){ 
            $data['numbertype_form'] = TRUE;
            $data['action']='add';
            $data['title']="Numbers Type";
            // $data['companies'] = $this->numbertype->get_companies();
            
            if(isset($_POST['submit'])){
                if ($this->numbertype->insert()) {
                    $this->session->set_flashdata('notification', 'Number Type information has been insert successfully.');
                    redirect(ADMINPATH.'numbertype');
                }
            // }elseif($this->input->post('cancel')){
            //     redirect('product');
            }else{
                $data['category_addedit'] = TRUE;
                $this->load->view(ADMINPATH.'numbertype/add',$data); 
            }
        }

        function edit(){
            $data['numbertype_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Numbers Type";
            // $data['companies'] = $this->numbertype->get_companies();
            
            if(isset($_POST['submit'])){
                if ($result = $this->numbertype->update()) {
                    $this->session->set_flashdata('notification','Number Type information has been update successfully.');
                    redirect(ADMINPATH.'numbertype');
                }
            // }elseif($this->input->post('cancel')){
            //         redirect('product');
            }else{
                // echo $this->uri->segment(3);exit;
                $data['form_data'] = $this->numbertype->getDataById($this->uri->segment(4));
                $data['category_addedit'] = TRUE;
                $this->load->view(ADMINPATH.'numbertype/edit',$data);
            }
        }

        // function view(){
        //     $data['numbertype_form'] = TRUE;
        //     $data['action']="edit";
        //     $data['title']="Member";
        //     $data['form_data'] = $this->member->getDataById($this->uri->segment(4));
        //     $this->load->view(ADMINPATH.'member/view',$data); 
        // }

        function filter(){
            // echo "<pre>";
            // print_r($_POST);

            if($_POST['submit'] == 'Apply'){
                $_SESSION['numbertype']['filter_date_start'] = $_POST['filter_date_start'];
                $_SESSION['numbertype']['filter_date_end'] = $_POST['filter_date_end'];
            }else if($_POST['submit'] == 'Reset'){
                unset($_SESSION['numbertype']);
            }

            redirect(ADMINPATH.'numbertype');
        }

    }
?>