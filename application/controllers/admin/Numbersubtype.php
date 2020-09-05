<?php
    class Numbersubtype extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model(ADMINPATH.'numbersubtype_model','numbersubtype');
            // $this->load->library('upload');
            checkLogin('admin');
            $this->load->library('accessibility');
            $this->accessibility->check_access('numbersubtype');
        }

        function index(){
            $data['numbersubtype_manage'] = TRUE;
            $data['title']="Numbers Sub Types";
            // $data['numbertypes'] = $this->numbersubtype->get_numbertype_active();

            if($this->input->post('action') == "change_publish"){
                if ($result = $this->numbersubtype->st_update()) {
                    $this->session->set_flashdata('notification', 'Number Sub Type status has been update successfully.');
                    redirect(ADMINPATH.'numbersubtype');
                }
            }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
                if ($result = $this->member->delete()) {
                    $this->session->set_flashdata('notification', 'Number Sub Type deleted successfully.');
                    redirect(ADMINPATH.'numbersubtype');
                }
            }
            // elseif ($this->input->post('action') == "deleteselected") {
            //     if ($result = $this->product->deleteselected()) {
            //         $this->session->set_flashdata('notification', 'categoty has been deleted successfully.');
            //         redirect('product');
            //     }
            // }
            
            $data['manage_data'] = $this->numbersubtype->get_numbersubtypes();
            $this->load->view(ADMINPATH.'numbersubtype/list',$data);
        }

        function add(){ 
            $data['numbersubtype_form'] = TRUE;
            $data['action']='add';
            $data['title']="Numbers Sub Type";
            $data['numbertypes'] = $this->numbersubtype->get_numbertype_active();
            
            if(isset($_POST['submit'])){
                if ($this->numbersubtype->insert()) {
                    $this->session->set_flashdata('notification', 'Number Sub Type information has been insert successfully.');
                    redirect(ADMINPATH.'numbersubtype');
                }
            // }elseif($this->input->post('cancel')){
            //     redirect('product');
            }else{
                $data['category_addedit'] = TRUE;
                $this->load->view(ADMINPATH.'numbersubtype/add',$data); 
            }
        }

        function edit(){
            $data['numbersubtype_form'] = TRUE;
            $data['action']="edit";
            $data['title']="Numbers Sub Type";
            $data['numbertypes'] = $this->numbersubtype->get_numbertype_active();
            
            if(isset($_POST['submit'])){
                if ($result = $this->numbersubtype->update()) {
                    $this->session->set_flashdata('notification','Number Sub Type information has been update successfully.');
                    redirect(ADMINPATH.'numbersubtype');
                }
            // }elseif($this->input->post('cancel')){
            //         redirect('product');
            }else{
                // echo $this->uri->segment(3);exit;
                $data['form_data'] = $this->numbersubtype->getDataById($this->uri->segment(4));
                $data['category_addedit'] = TRUE;
                $this->load->view(ADMINPATH.'numbersubtype/edit',$data);
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
                $_SESSION['numbersubtype']['filter_date_start'] = $_POST['filter_date_start'];
                $_SESSION['numbersubtype']['filter_date_end'] = $_POST['filter_date_end'];
            }else if($_POST['submit'] == 'Reset'){
                unset($_SESSION['numbersubtype']);
            }

            redirect(ADMINPATH.'numbersubtype');
        }

    }
?>