<?php
    class Ad_model extends CI_Model{
        function __construct(){
            parent::__construct();
            $this->table='ads';
            $this->banner_thumb = array('50'=>'50', '120'=>'120');
        }

        function get_ads() {
            $this->db->select('*');
            // $this->db->from('number_types n');
            if(isset($_SESSION['ad']['filter_date_start']) && $_SESSION['ad']['filter_date_start'] != ""){
                $startDate = date('Y-m-d',strtotime($_SESSION['ad']['filter_date_start']));
                $this->db->where('DATE(created_at) >= "'.$startDate.'"');
            }
            if(isset($_SESSION['ad']['filter_date_end']) && $_SESSION['ad']['filter_date_end'] != ""){
                $endDate = date('Y-m-d',strtotime($_SESSION['ad']['filter_date_end']));
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
            $pic_name = "";

            if(isset($_FILES['pic']['name']) && $_FILES['pic']['name'] != ""){

                $pic_name = time() .'_'.preg_replace("/\s+/", "_", $_FILES['pic']['name']);

                $config['file_name'] = $pic_name;
                $config['upload_path'] = ADBNR_PATH;
                $config['allowed_types'] = 'gif|jpg|png|jpeg';

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('pic')) {
                        $data['error'] = array('error' => $this->upload->display_errors());
                        // echo "<pre>";
                        // print_r($data['error']);
                }else{
                        $data['upload_data'] = $this->upload->data();
                        $this->load->library('image_lib');
                        foreach ($this->banner_thumb as $key => $val) {
                                $config['image_library'] = 'gd2';
                                $config['source_image'] = $_FILES['pic']['tmp_name'];
                                $config['create_thumb'] = false;
                                $config['maintain_ratio'] = false;
                                $config['width'] = $key;
                                $config['height'] = $val;
                                $config['new_image'] = ADBNR_PATH . "thumb/" . $config['width'] . "x" . $config['height'] . "_" . $pic_name;
                                $this->image_lib->clear();
                                $this->image_lib->initialize($config);
                                $this->image_lib->resize();
                        }
                }
            }

            $data=array(
                'title'=>$this->input->post('title'),
                'pic'=>$pic_name,
                'target'=>$this->input->post('target'),
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

            if(isset($_FILES['pic']['name']) && $_FILES['pic']['name'] != ""){
                if(file_exists(ADBNR_PATH.$this->input->post('pic_old')))
                {
                        @unlink(ADBNR_PATH.$this->input->post('pic_old'));
                        foreach ($this->banner_thumb as $key => $val) {
                                if (ADBNR_PATH ."thumb/" . $key. "x" . $val."_".$this->input->post('pic_old'))
                                {
                                        @unlink(ADBNR_PATH ."thumb/" . $key . "x" . $val."_".$this->input->post('pic_old'));
                                }
                        }
                }

                $pic_name = time() .'_'.preg_replace("/\s+/", "_", $_FILES['pic']['name']);

                $config['file_name'] = $pic_name;
                $config['upload_path'] = ADBNR_PATH;
                $config['allowed_types'] = 'gif|jpg|png|jpeg';

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('pic')) {
                        $data['error'] = array('error' => $this->upload->display_errors());
                        // echo "<pre>";
                        // print_r($data['error']);
                }else{
                        $data['upload_data'] = $this->upload->data();
                        $this->load->library('image_lib');
                        foreach ($this->banner_thumb as $key => $val) {
                                $config['image_library'] = 'gd2';
                                $config['source_image'] = $_FILES['pic']['tmp_name'];
                                $config['create_thumb'] = false;
                                $config['maintain_ratio'] = false;
                                $config['width'] = $key;
                                $config['height'] = $val;
                                $config['new_image'] = ADBNR_PATH . "thumb/" . $config['width'] . "x" . $config['height'] . "_" . $pic_name;
                                $this->image_lib->clear();
                                $this->image_lib->initialize($config);
                                $this->image_lib->resize();
                        }
                }
            }else{
                $pic_name = $this->input->post('pic_old');
            }

            $data = array();
            $date = date('Y-m-d h:i:s');
            $data['title'] = $this->input->post('title');
            $data['pic'] = $pic_name;
            $data['target'] = $this->input->post('target');
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