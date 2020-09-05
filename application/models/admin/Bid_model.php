<?php
    class Bid_model extends CI_Model{
        function __construct(){
            parent::__construct();
            $this->table='bids';
        }

        function get_bids() {
            // $this->db->select('b.*,m1.name as added_by_name,m2.name as purchaser_name,nt.name as NumberType,nst.sub_name as NumberSubType,d.no_of_days,f.fee_amount');
            $this->db->select('b.*,m1.name as added_by_name,m2.name as purchaser_name');
            $this->db->from('bids b');
            $this->db->join('members m1','m1.id = b.member_id','left');
            $this->db->join('members m2','m2.id = b.purchaser','left');
            // $this->db->join('number_types nt','nt.id = b.number_type','left');
            // $this->db->join('number_subtypes nst','nst.id = b.number_subtype','left');
            // $this->db->join('durations d','d.id = b.duration','left');
            // $this->db->join('fees f','f.id = b.fee','left');

            if(isset($_SESSION['bid']['filter_date_start']) && $_SESSION['bid']['filter_date_start'] != ""){
                $startDate = date('Y-m-d',strtotime($_SESSION['bid']['filter_date_start']));
                $this->db->where('DATE(b.created_at) >= "'.$startDate.'"');
            }
            if(isset($_SESSION['bid']['filter_date_end']) && $_SESSION['bid']['filter_date_end'] != ""){
                $endDate = date('Y-m-d',strtotime($_SESSION['bid']['filter_date_end']));
                $this->db->where('DATE(b.created_at) <= "'.$endDate.'"');
            }
            if(isset($_SESSION['bid']['filter_progress_status']) && $_SESSION['bid']['filter_progress_status'] != ""){
                // $endDate = date('Y-m-d',strtotime($_SESSION['member']['filter_date_end']));
                // $this->db->where('DATE(m.created_at) <= "'.$endDate.'"');

                if($_SESSION['bid']['filter_progress_status'] == "waiting"){
                    $this->db->where('b.status','Disable');
                }
                if($_SESSION['bid']['filter_progress_status'] == "ongoing"){
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.progress_status','0');
                }
                if($_SESSION['bid']['filter_progress_status'] == "completed"){
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.progress_status','1');
                }
            }

            // $this->db->where('b.status','Enable');
            $this->db->where('b.live','Y');
            $this->db->group_by("b.id");
            $this->db->order_by("b.id", "Desc");
            $query = $this->db->get();

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

        function get_all_bids(){
            $this->db->select('*');
            $this->db->where('status','Enable');
            $query = $this->db->get('bids');

            $result = array();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
            }
            return $result;
        }

        function getDataById($id){
            // $this->db->select('b.*,m1.name as added_by_name,m2.name as purchaser_name,nt.name as NumberType,nst.sub_name as NumberSubType,d.no_of_days,f.fee_amount');
            $this->db->select('b.*,m1.name as added_by_name,m2.name as purchaser_name');
            $this->db->from('bids b');
            $this->db->join('members m1','m1.id = b.member_id','left');
            $this->db->join('members m2','m2.id = b.purchaser','left');
            // $this->db->join('number_types nt','nt.id = b.number_type','left');
            // $this->db->join('number_subtypes nst','nst.id = b.number_subtype','left');
            // $this->db->join('durations d','d.id = b.duration','left');
            // $this->db->join('fees f','f.id = b.fee','left');
            $this->db->where('b.id',$id);
            $query = $this->db->get();
            // echo $this->db->last_query();
            $row = $query->row_array();
            $row['max_amount'] = $this->getMaxBid($row['id']);
            $row['no_of_bids'] = $this->getNoOfBid($row['id']);
            return $row;
        }

        function getPlacedBidListById($bid_id){
            $this->db->select('mb.*,m1.name');
            $this->db->from('member_bids mb');
            $this->db->join('members m1','m1.id = mb.member_id','left');

            if(isset($_SESSION['place_bid']['filter_date_start']) && $_SESSION['place_bid']['filter_date_start'] != ""){
                $startDate = date('Y-m-d',strtotime($_SESSION['place_bid']['filter_date_start']));
                $this->db->where('DATE(mb.created_at) >= "'.$startDate.'"');
            }
            if(isset($_SESSION['place_bid']['filter_date_end']) && $_SESSION['place_bid']['filter_date_end'] != ""){
                $endDate = date('Y-m-d',strtotime($_SESSION['place_bid']['filter_date_end']));
                $this->db->where('DATE(mb.created_at) <= "'.$endDate.'"');
            }

            $this->db->where('mb.bid_id',$bid_id);
            $this->db->order_by('mb.id','DESC');
            $query = $this->db->get();
            // echo $this->db->last_query();
            $row = $query->result_array();
            return $row;
        }

        function getMaxBid($bid_id){
            $this->db->select('max(amount) as max_bid_amount');
            $this->db->from('member_bids mb');
            $this->db->where('mb.bid_id',$bid_id);
            $query = $this->db->get();
            $row = $query->row('max_bid_amount');
            return $row;
        }
        function getNoOfBid($bid_id){
            $this->db->select('count(id) as no_of_bids');
            $this->db->from('member_bids mb');
            $this->db->where('mb.bid_id',$bid_id);
            $query = $this->db->get();
            $row = $query->row('no_of_bids');
            return $row;
        }

        function get_bids_by_compnayId($company_id){
            $this->db->select('*');
            $this->db->where('status','Y');
            $this->db->where('company_id',$company_id);
            $query = $this->db->get('bids');

            $result = array();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
            }
            return $result;
        }

        function insert(){
            $date = date('Y-m-d h:i:s');
            $data=array(
                'bids_name'=>$this->input->post('bids_name'),
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
            $data=array(
                'bid_name'=>$this->input->post('bid_name'),
                'company_id'=>$this->input->post('company_id'),
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
            $bid = $this->getDataById($this->input->post('id'));
            // echo "<pre>";
            // print_r($bid);
            // exit;
            $bid_end_datetime = Date('Y-m-d H:i:s', strtotime('+'.$bid['duration_str'].' days'));

            $data = array(
                'bid_end_datetime'=>$bid_end_datetime,
                'status'=>$this->input->post('publish')
            );
            $this->db->where('id', $this->input->post('id'));
            if($this->db->update($this->table,$data)){
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