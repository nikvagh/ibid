<?php
    class User_model extends CI_Model{
        function __construct(){
            parent::__construct();
            $this->table='users';
        }

        function get_users() {
            $this->db->select('*');
            // $this->db->from('number_types n');
            $this->db->where("user_type !=", "super_admin");

            if(isset($_SESSION['user_filt']['filter_date_start']) && $_SESSION['user_filt']['filter_date_start'] != ""){
                $startDate = date('Y-m-d',strtotime($_SESSION['user_filt']['filter_date_start']));
                $this->db->where('DATE(created_at) >= "'.$startDate.'"');
            }
            if(isset($_SESSION['user_filt']['filter_date_end']) && $_SESSION['user_filt']['filter_date_end'] != ""){
                $endDate = date('Y-m-d',strtotime($_SESSION['user_filt']['filter_date_end']));
                $this->db->where('DATE(created_at) <= "'.$endDate.'"');
            }

            $query = $this->db->get($this->table);

            $result = array();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
            }
            return $result;
        }

        // function get_companies(){
        //     $this->db->select('*');
        //     $this->db->where('status','Y');
        //     $query = $this->db->get('companies');

        //     $result = array();
        //     if ($query->num_rows() > 0) {
        //         $result = $query->result_array();
        //     }
        //     return $result;
        // }

        function getDataById($id){
            $this->db->select('*');
            $this->db->where('id',$id);
            $query = $this->db->get($this->table);
            // echo $this->db->last_query();
            $row = $query->row_array();
            return $row;
        }

        function insert(){

            $accessibility = "";
            if(isset($_POST['access'])){
                $accessibility = json_encode($_POST['access']);
            }
            
            $data=array(
                'name'=>$this->input->post('name'),
                'username'=>$this->input->post('username'),
                'email'=>$this->input->post('email'),
                'phone'=>$this->input->post('phone'),
                'password'=>md5($this->input->post('password')),
                'accessibility'=>$accessibility,
                'status'=>$this->input->post('status')
            );
            if($this->db->insert($this->table,$data)){
                $id=$this->db->insert_id();
                return true;
            }else{
                return false;
            }
        }

        function update(){
            // echo "<pre>";
            // print_r($_POST);
            // exit;

            $accessibility = "";
            if(isset($_POST['access'])){
                $accessibility = json_encode($_POST['access']);
            }

            $date = date('Y-m-d h:i:s');

            $data['name'] = $this->input->post('name');
            $data['username'] = $this->input->post('username');
            $data['email'] = $this->input->post('email');
            $data['phone'] = $this->input->post('phone');
            // $data['profile_pic'] = $profile_name;
            if($_POST['password'] != ""){
                $data['password'] = md5($this->input->post('password'));
            }
            $data['accessibility'] = $accessibility;
            $data['status'] = $this->input->post('status');
            $data['updated_at'] = $date;

            $this->db->where('id',$this->input->post('id'));
            if($this->db->update($this->table,$data)){
                return true;
            }else{
                return false;
            }
        }

        function st_update(){
            $this->db->set('status', $this->input->post('publish'));
            $this->db->where('id', $this->input->post('id'));
            if($this->db->update($this->table)){
                // echo $this->db->last_query();
                // echo "dddd";exit;
               return true;
            }else{
                return false;
            }
        }

        function delete(){
            $this->db->where('id', $this->input->post('id'));
            if ($query = $this->db->delete($this->table))
                return true;
            else
                return false;
        }

        function getPrivileges(){
            $this->db->select('*');
            $this->db->order_by("sort_order","ASC");
            $query = $this->db->get('privileges');

            $result = array();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
            }
            return $result;
        }
        
    }
?>