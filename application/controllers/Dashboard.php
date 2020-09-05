<?php
    class Dashboard extends CI_Controller{
        function __construct(){
            parent::__construct();
            // $this->load->library('administration');
            $this->load->model('api_model','api');
            $this->load->library('upload');
        }

        function index(){
            echo "dash";
            // $data['order_manage'] = TRUE;
            // $data['title']="Order";

            // if($this->input->post('action') == "change_publish"){
            //     if ($result = $this->order->st_update()) {
            //         $this->session->set_flashdata('notification', 'Order status has been update successfully.');
            //         redirect('order');
            //     }
            // }elseif(isset($_POST['action']) && $_POST['action'] == "delete"){
            //     // echo "delete";exit;
            //     if ($result = $this->order->delete()) {
            //         $this->session->set_flashdata('notification', 'Order deleted successfully.');
            //         redirect('order');
            //     }
            // }
            // $data['manage_data'] = $this->order->get_companies();
            // $this->load->view('order/list',$data);
        }

        // function add(){ 
        //     $data['order_form'] = TRUE;
        //     $data['action']='add';
        //     $data['title']="Order";
            
        //     if(isset($_POST['submit'])){
        //         if ($this->order->insert()) {
        //             $this->session->set_flashdata('notification', 'order information has been insert successfully.');
        //             redirect('order');
        //         }
        //     }else{
        //         $data['category_addedit'] = TRUE;
        //         $this->load->view('order/add',$data); 
        //     }
        // }

        // function edit(){
        //     $data['order_form'] = TRUE;
        //     $data['action']="edit";
        //     $data['title']="Order";
            
        //     if(isset($_POST['submit'])){
        //         if ($result = $this->order->update()) {
        //             $this->session->set_flashdata('notification','order information has been update successfully.');
        //             redirect('order');
        //         }
        //     }else{
        //         $data['form_data'] = $this->order->getDataById($this->uri->segment(3));
        //         $this->load->view('order/edit',$data); 
        //     }
        // }

        // function view(){
        //     $data['order_form'] = TRUE;
        //     $data['action']="view";
        //     $data['title']="Order";
        //     $data['view_data'] = $this->order->getDataById($this->uri->segment(3));
        //     $this->load->view('order/view',$data); 
        // }

    }
?>