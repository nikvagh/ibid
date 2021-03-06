<?php
class Sp {
		
		var $id = 0;
		var $logged_in = false;
		var $loginData = array();
		var $username = '';
		var $table = 'sp';
		
		function __construct()
		{
			$this->obj =& get_instance();
			$this->_session_to_library();
		}
		
		function _prep_password($password)
		{
			// Salt up the hash pipe
			// Encryption key as suffix.
			
			return $this->obj->encrypt->sha1($password.$this->obj->config->item('encryption_key'));
		}
		
		function _session_to_library()
		{
			// Pulls session data into the library.
			$this->id               = $this->obj->session->userdata('id');
			$this->username			= $this->obj->session->userdata('username');
			$this->logged_in 		= $this->obj->session->userdata('logged_in');
            $this->date_time 		= $this->obj->session->userdata('date_time');
            $this->loginData 		= $this->obj->session->userdata('loginData');
		}
		
		function _start_session($user)
		{
			// $user is an object sent from function login();
			// Let's build an array of data to put in the session.
            $dt= date('Y-m-d H:i:s'); 
                    
			$data = array(
						'id' 			=> $user->sp_id,
						'username' 		=> $user->username,
						'loginData'		=> $user,
						'logged_in'		=> true,
                        'date_time'     => $dt,
                        'user_type'     => 'sp'
					);
					
			$this->obj->session->set_userdata($data);
			$this->_session_to_library();
		}
                
		function _destroy_session()
		{
			$data = array(
						'id' 			=> 0,
						'username' 		=> '',
						'loginData'	    => array(),
						'logged_in'		=> false,
						'date_time'     =>'',
						'user_type'     => ''
					);
					
			$this->obj->session->set_userdata($data);
			
			foreach ($data as $key => $value)
			{
				$this->$key = $value;
			}
		}
		
		function login($username, $password)
		{
//                    echo $username;
//                    echo $password;exit;
			$pass = md5($password);
                        
			// $query = $this->obj->db->get($this->table, 1);
			
			// First up, let's query the DB.
			// Prep the password to make sure we get a match.
			// And only allow active members.
			
//			$this->obj->db->where('admin_name', $username);
//			$this->obj->db->where('admin_password', md5($password));
//			$this->obj->db->where('admin_status', '1');
                        
            $this->obj->db->where("(username='$username' OR email ='$username') AND password = '".$pass."' AND status = 'Enable' ");
			
			$query = $this->obj->db->get($this->table, 1);
			// echo $this->obj->db->last_query();exit;
			if ( $query->num_rows() == 1 )
			{
//                            echo "1";
				// We found a user!
				// Let's save some data in their session/cookie/pocket whatever.
				
				$user = $query->row();

				// echo "<pre>";
				// print_r($user);
				// echo "</pre>";exit;

				$this->_start_session($user);
				
				$this->obj->session->set_flashdata('user', 'Login successful...');
				
				return true;
			} else {   
				// Login failed...
				// Couldn't find the user,
				// Let's destroy everything just to make sure.
				
				$this->_destroy_session();
				
				$this->obj->session->set_flashdata('user', 'Login failed...');
				
				return false;
			}
		}
		function logout()
		{
//                        print_r($this->date_time);exit;
//                        echo $this->obj->session->userdata();exit;
			
			$data=array('last_login' => $this->date_time);
			$this->obj->db->where('sp_id',$this->id);
			$query=$this->obj->db->update($this->table ,$data);
//                        echo $this->obj->db->last_query();
//                        exit;
			$this->_destroy_session();
			$this->obj->session->set_flashdata('user', 'You are now logged out');
		}
		
}
