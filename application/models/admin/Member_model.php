<?php
    class Member_model extends CI_Model{
        function __construct(){
            parent::__construct();
            $this->table='members';
        }

        function get_members() {
            $this->db->select('m.*');
            $this->db->from('members m');
            $this->db->where('otp_varified','Y');

            if(isset($_SESSION['member']['filter_date_start']) && $_SESSION['member']['filter_date_start'] != ""){
                $startDate = date('Y-m-d',strtotime($_SESSION['member']['filter_date_start']));
                $this->db->where('DATE(m.created_at) >= "'.$startDate.'"');
            }
            if(isset($_SESSION['member']['filter_date_end']) && $_SESSION['member']['filter_date_end'] != ""){
                $endDate = date('Y-m-d',strtotime($_SESSION['member']['filter_date_end']));
                $this->db->where('DATE(m.created_at) <= "'.$endDate.'"');
            }
            
            $this->db->order_by("m.id", "Desc");
            $query = $this->db->get();
            // echo $this->db->last_query();

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

        function get_all_members(){
            $this->db->select('*');
            $this->db->where('status','Enable');
            $query = $this->db->get('members');

            $result = array();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
            }
            return $result;
        }

        function getDataById($id){
            $this->db->select('*');
            $this->db->where('id',$id);
            $query = $this->db->get($this->table);
            // echo $this->db->last_query();
            $row = $query->row_array();
            return $row;
        }

        function get_members_by_compnayId($company_id){
            $this->db->select('*');
            $this->db->where('status','Y');
            $this->db->where('company_id',$company_id);
            $query = $this->db->get('members');

            $result = array();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
            }
            return $result;
        }

        function insert(){
            $date = date('Y-m-d h:i:s');
            $data=array(
                'members_name'=>$this->input->post('members_name'),
                'company_id'=>$this->input->post('company_id'),
                'status'=>$this->input->post('status'),
                'created_at'=>$date
            );
            if($this->db->insert($this->table,$data)){
                $id=$this->db->insert_id();
                return true;
            }else{
                return false;
            }
        }

        function update(){
            $date = date('Y-m-d h:i:s');
            $data = array(
                'password'=>$this->input->post('password'),
                'updated_at'=>$date
            );
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
        
    }
?>