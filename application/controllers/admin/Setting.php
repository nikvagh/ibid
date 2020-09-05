<?php
	class Setting extends CI_Controller {
		function __construct()
		{
			parent::__construct();
			checkLogin('admin');
			$this->accessibility->check_access('setting');
		}		

		function index()
		{	
        	$data['setting'] = TRUE;
			$data['title'] = "Settings";
			$this->load->view(ADMINPATH.'setting', $data);
		}

		function update()
		{	
			$data['title'] = "Settings";
			if(isset($_POST['submit'])){
				$settings_arr = array("company_name", "company_address", "company_mobile", "company_email", "site_name", "company_copyright", "from_email_address","terms_condition");

				$success = "N";
				for($i=0; $i < count($settings_arr); $i++)
				{
					$settings_data = array('config_value' =>  $this->input->post($settings_arr[$i]));
					$this->db->where('config_name', $settings_arr[$i]);	
					if($query = $this->db->update('web_config', $settings_data)){
						$success = "Y";
					}
				}

				if($success == "Y"){
					$this->session->set_flashdata('success', 'Settings has been updated successfully.');
				}
			}
			redirect(ADMINPATH.'setting');
		}

	}
?>