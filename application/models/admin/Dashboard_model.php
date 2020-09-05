<?php
class Dashboard_model extends CI_Model{
    function __construct(){
        parent::__construct();
        $this->table='user';
    }
    function udata(){
        $this->db->select('*');
        $this->db->where("user_id",$this->user->id);
        $query = $this->db->get($this->table);
        if($query->num_rows()>0)
        {
            $udata=$query->row_array();
            return $udata;
        }
    }
    function get_admin(){
        $this->db->select('*');
        $this->db->where('admin_id',$this->session->userdata('id'));	
        $query = $this->db->get('admin');
        $admin = array();

        if ($query->num_rows() > 0) {
                $admin = $query->row_array();
        }
        return $admin;
    }

    function get_total_members(){
        $this->db->select('count(id) as total');	
        $this->db->where('otp_varified','Y');	
        $query = $this->db->get('members');
        $total = "0";
        if ($query->num_rows() > 0) {
            $total = $query->row('total');
        }
        return $total;
    }

    function get_total_bids(){
        $this->db->select('count(id) as total');	
        $this->db->where('live','Y');	
        $this->db->where('status','Enable');
        $query = $this->db->get('bids');
        $total = "0";
        if ($query->num_rows() > 0) {
            $total = $query->row('total');
        }
        return $total;
    }

    function get_total_ongoing_bids(){
        $this->db->select('count(id) as total');	
        $this->db->where('live','Y');	
        $this->db->where('status','Enable');	
        $this->db->where('progress_status','0');
        $query = $this->db->get('bids');
        $total = "0";
        if ($query->num_rows() > 0) {
            $total = $query->row('total');
        }
        return $total;
    }

    function get_total_completed_bids(){
        $this->db->select('count(id) as total');	
        $this->db->where('live','Y');	
        $this->db->where('status','Enable');	
        $this->db->where('progress_status','1');
        $query = $this->db->get('bids');
        $total = "0";
        if ($query->num_rows() > 0) {
            $total = $query->row('total');
        }
        return $total;
    }

}
?>