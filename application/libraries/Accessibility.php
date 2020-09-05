<?php
	class Accessibility {
		function __construct()
		{
			$this->obj =& get_instance();
			// $this->check_access();
		}
		
		function check_access($module)
		{
			// echo "<pre>";
			// print_r($_SESSION);
			// echo "</pre>";
			$access = "N";
			if($this->obj->session->userdata('loginData')->user_type == 'admin'){

				$this->obj->db->select('accessibility');
				$this->obj->db->where('id',$this->obj->session->userdata('loginData')->id);
				$query = $this->obj->db->get('users');
				$accessibility = $query->row('accessibility');

				if($accessibility != ""){
					$access_array = json_decode($accessibility);
					if(array_key_exists($module,$access_array)){
						$access = "Y";
					}
				}

			}else if($this->obj->session->userdata('loginData')->user_type == 'super_admin'){
				$access = "Y";
			}

			if($access == "N"){
				redirect(ADMINPATH.'dashboard');
			}
		}

		function check_access1($module)
		{
			$access = "N";
			if($this->obj->session->userdata('loginData')->user_type == 'admin'){

				$this->obj->db->select('accessibility');
				$this->obj->db->where('id',$this->obj->session->userdata('loginData')->id);
				$query = $this->obj->db->get('users');
				$accessibility = $query->row('accessibility');

				if($accessibility != ""){
					$access_array = json_decode($accessibility);
					if(array_key_exists($module,$access_array)){
						$access = "Y";
					}
				}

			}else if($this->obj->session->userdata('loginData')->user_type == 'super_admin'){
				$access = "Y";
			}

			if($access == "N"){
				return false;
			}else{
				return true;
			}
		}

	}
?>