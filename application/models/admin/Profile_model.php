<?php
class Profile_model extends CI_Model{
	function __construct(){
                parent::__construct();
                $this->table = 'users';
                $this->profile_thumb = array('50'=>'50', '120'=>'120');
        }
        
	function get_user() {
                $this->db->select('*');
                $this->db->where('id',$this->session->userdata('id'));	
                $query = $this->db->get($this->table);
                $result = array();
                if ($query->num_rows() > 0) {
                        $result = $query->row_array();
                }
                return $result;
	}
	
	function update_profile()
	{
                // echo "<pre>";
                // print_r($_POST);
                // print_r($_FILES);
                // echo "</pre>";
                // exit;

                if(isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] != ""){
                        if(file_exists(PROFILE_PATH.$this->input->post('profile_pic_old')))
                        {
                                @unlink(PROFILE_PATH.$this->input->post('profile_pic_old'));
                                foreach ($this->profile_thumb as $key => $val) {
                                        if (PROFILE_PATH ."thumb/" . $key. "x" . $val."_".$this->input->post('profile_pic_old'))
                                        {
                                                @unlink(PROFILE_PATH ."thumb/" . $key . "x" . $val."_".$this->input->post('profile_pic_old'));
                                        }
                                }
                        }

                        $profile_name = time() .'_'.preg_replace("/\s+/", "_", $_FILES['profile_pic']['name']);

                        $config['file_name'] = $profile_name;
                        $config['upload_path'] = PROFILE_PATH;
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';

                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload('profile_pic')) {
                                $data['error'] = array('error' => $this->upload->display_errors());
                                // echo "<pre>";
                                // print_r($data['error']);
                        }else{
                                $data['upload_data'] = $this->upload->data();
                                $this->load->library('image_lib');
                                foreach ($this->profile_thumb as $key => $val) {
                                        $config['image_library'] = 'gd2';
                                        $config['source_image'] = $_FILES['profile_pic']['tmp_name'];
                                        $config['create_thumb'] = false;
                                        $config['maintain_ratio'] = false;
                                        $config['width'] = $key;
                                        $config['height'] = $val;
                                        $config['new_image'] = PROFILE_PATH . "thumb/" . $config['width'] . "x" . $config['height'] . "_" . $profile_name;
                                        $this->image_lib->clear();
                                        $this->image_lib->initialize($config);
                                        $this->image_lib->resize();
                                }
                        }
                }else{
                        $profile_name = $this->input->post('profile_pic_old');
                }

                // echo "<pre>";print_r($_POST);
                // exit;

                $data = array();
                $data['name'] = $this->input->post('name');
                $data['username'] = $this->input->post('username');
                $data['email'] = $this->input->post('email');
                $data['phone'] = $this->input->post('phone');
                $data['profile_pic'] = $profile_name;
                if($_POST['password'] != ""){
                        $data['password'] = md5($this->input->post('password'));
                }

                // echo "<pre>";
                // print_r($data);
                // exit;

                $this->db->where('id',$this->session->userdata('id'));
                $query=$this->db->update($this->table,$data);
                // echo $this->db->last_query();
                // exit;
                if($query){
                        $user = $this->get_user();
                        $_SESSION['loginData'] = (object) $user;
                        return true;
                }else{
                        return false;
                }
	}
}
?>