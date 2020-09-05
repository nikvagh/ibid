<?php
	class Administration {
		
		function __construct()
		{
			$this->obj =& get_instance();

			// if ( !$this->obj->user->logged_in && $this->obj->uri->segment(1) != 'login' )
			// {
			// 	redirect('login');
			// 	exit;
			// }
		}
		
	}

?>