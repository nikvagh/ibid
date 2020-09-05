<?php
	class System {
		
		function __construct()
		{
			$this->obj =& get_instance();
			$this->get_config();
		}
		
		function get_config()
		{
			$query = $this->obj->db->get('web_config');
		
			foreach ($query->result() as $row)
			{
				$var = $row->config_name;
				//print "<pre>";print_r($row);print "</pre>";
				//print $row->config_name . ' = '. $row->config_value .'<br>';
				$this->$var = $row->config_value ;
			  // 	$this->$row = $row->site_name;
			}
		}
	}
?>