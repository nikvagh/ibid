<?php
    class Coupon_model extends CI_Model{
        function __construct(){
            parent::__construct();
            $this->table='coupons';
        }

        function get_coupons() {
            $this->db->select('*');
            // $this->db->from('number_types n');
            // $this->db->order_by("n.id", "Desc");

            if(isset($_SESSION['coupon']['filter_date_start']) && $_SESSION['coupon']['filter_date_start'] != ""){
                $startDate = date('Y-m-d',strtotime($_SESSION['coupon']['filter_date_start']));
                $this->db->where('DATE(created_at) >= "'.$startDate.'"');
            }
            if(isset($_SESSION['coupon']['filter_date_end']) && $_SESSION['coupon']['filter_date_end'] != ""){
                $endDate = date('Y-m-d',strtotime($_SESSION['coupon']['filter_date_end']));
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
            $data = array();
            $data['code'] = $this->input->post('code');
            $data['amount'] = $this->input->post('amount');
            $data['amount_type'] = $this->input->post('amount_type');
            $data['discount_on'] = $this->input->post('discount_on');
            $data['users'] = $this->input->post('users');
            if($data['users'] == '1'){
                if(isset($_POST['member_id'])){
                    $data['member_id'] = $this->input->post('member_id');
                }
            }else{
                $data['member_id'] = 0;
            }
            $data['expiry_date'] = $this->input->post('expiry_date');
            $data['status'] = $this->input->post('status');

            if($this->db->insert($this->table,$data)){
                $id=$this->db->insert_id();
                return true;
            }else{
                return false;
            }
        }

        function update(){
            $date = date('Y-m-d h:i:s');

            $data = array();
            $data['code'] = $this->input->post('code');
            $data['amount'] = $this->input->post('amount');
            $data['amount_type'] = $this->input->post('amount_type');
            $data['discount_on'] = $this->input->post('discount_on');
            $data['users'] = $this->input->post('users');
            if($data['users'] == '1'){
                if(isset($_POST['member_id'])){
                    $data['member_id'] = $this->input->post('member_id');
                }
            }else{
                $data['member_id'] = 0;
            }
            $data['expiry_date'] = $this->input->post('expiry_date');
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
        
    }
?>