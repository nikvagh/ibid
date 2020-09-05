<?php
    class Api_model extends CI_Model{
        function __construct(){
            parent::__construct();
        }

        function get_companies() {
            $this->db->select('o.*,c.company_name');
            $this->db->from('orders o');
            $this->db->join('companies c','c.id=o.company_id','left');
            $this->db->order_by("o.id", "Desc");
            // $this->db->where('status', 'Y');
            $query = $this->db->get();

            $result = array();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
            }
            return $result;
        }

        function getDataById($id){
            $this->db->select('o.*,c.company_name');
            $this->db->from('orders o');
            $this->db->join('companies c','c.id=o.company_id','left');
            $this->db->where('o.id',$id);
            $query=$this->db->get();
            $row=$query->row_array();
            return $row;
        }

        function insert(){
            $date = date('Y-m-d h:i:s');
            $data=array(
                'company_name'=>$this->input->post('company_name'),
                'api_url'=>$this->input->post('api_url'),
                'authentication_key'=>$this->input->post('authentication_key'),
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
            $data=array(
                'company_name'=>$this->input->post('company_name'),
                'api_url'=>$this->input->post('api_url'),
                'authentication_key'=>$this->input->post('authentication_key'),
                'status'=>$this->input->post('status'),
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