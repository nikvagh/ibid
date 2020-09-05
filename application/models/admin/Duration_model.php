<?php
    class Duration_model extends CI_Model{
        function __construct(){
            parent::__construct();
            $this->table='durations';
        }

        function get_durations() {
            $this->db->select('*');
            // $this->db->from('number_types n');
            // $this->db->order_by("n.id", "Desc");

            if(isset($_SESSION['duration']['filter_date_start']) && $_SESSION['duration']['filter_date_start'] != ""){
                $startDate = date('Y-m-d',strtotime($_SESSION['duration']['filter_date_start']));
                $this->db->where('DATE(created_at) >= "'.$startDate.'"');
            }
            if(isset($_SESSION['duration']['filter_date_end']) && $_SESSION['duration']['filter_date_end'] != ""){
                $endDate = date('Y-m-d',strtotime($_SESSION['duration']['filter_date_end']));
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
            $data=array(
                'no_of_days'=>$this->input->post('no_of_days'),
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
            $date = date('Y-m-d h:i:s');
            $data=array(
                'no_of_days'=>$this->input->post('no_of_days'),
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