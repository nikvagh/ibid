<?php

	class Functions {
		
		function __construct()
		{
			$this->obj =& get_instance();
		}
		
		function alphalist($alpha)
		{
			$alphabets = array("All","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z") ;
			$alpha_str = '';
			
			if($alpha == '')
				$alpha = 'All';
			
			for($i=0; $i<count($alphabets); $i++)
			{
				if($alpha == $alphabets[$i])
				{
					$alpha_str .= '<span class="element"><font color="#FF0000">'.$alphabets[$i].'</font></span>&nbsp;' ;
				}
				else
				{
					$alpha_str .= '<span class="element"><a href="alpha-'.$alphabets[$i].'" class=pageLink>'.$alphabets[$i].'</a></span>&nbsp;' ;
				}
			}
			return $alpha_str;
		}

		function generate_randomnumber($len)
		{
			$r_str = "";
				$chars = "0123456789";
				for($i=0; $i<$len; $i++) 
					$r_str .= substr($chars,rand(0,strlen($chars)),1);
				return $r_str;
		}
                
                function generate_randomnumber1($len)
		{
			$r_str = "";
                        $chars = "0123456789_";
                        for($i=0; $i<$len; $i++) 
                                $r_str .= substr($chars,rand(0,strlen($chars)),1);
                        return $r_str;
		}
        
		function GenerateRandomString()
		{
			$use_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' ;
			$num_usable = strlen($use_chars) - 1;
			$string     = '';

			for($i = 0; $i < 10; $i++)
			{
				$rand_char = rand(0, $num_usable);

				$string .= $use_chars{$rand_char};
			}

			return $string;
		}
		function GenerateUniqueFilePrefix()
		{
			list($usec, $sec) = explode(" ",microtime());
			list($trash, $usec) = explode(".",$usec);
			return (date("YmdHis").substr(($sec + $usec), -10).'_');
		}
		function neat_trim($str, $n, $delim='...') 
		{
		   $len = strlen($str);
		   if ($len > $n) 
		   {
			   preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
			   return rtrim($matches[1]) . $delim;
		   }
		   else 
		   {
			   return $str;
		   }
		}
		
		function MySqlDate($entrydate)
		{
			if($entrydate)
			{
				$arrdate = explode('/', $entrydate);
				$mysqldate = date("Y-m-d", mktime(0, 0, 0, $arrdate[1], $arrdate[0], $arrdate[2]));
				return $mysqldate;
			}
			else
			{
				return;
			}
		}
		function EntryDate($mysqldate, $format='d/m/Y')
		{
			if($mysqldate)
			{
				$arrdate = explode('-',$mysqldate);
				$entrydate = date($format, mktime(0, 0, 0, $arrdate[1], $arrdate[2], $arrdate[0]));
				return $entrydate;
			}
			else
			{
				return;
			}
		}
		function getUserIP()
		{
			$client_ip = '';

			if( getenv('HTTP_X_FORWARDED_FOR') != '' )
			{
				$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );
			
				if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip_list) )
				{
					$private_ip	= array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10.\.*/', '/^224.\.*/', '/^240.\.*/');
					$client_ip	= preg_replace($private_ip, $client_ip, $ip_list[1]);
				}
			}
			else
			{
				$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );
			}
			
			return $client_ip;
		}
               
                function SendHTMLMail($to,$subject,$mailcontent,$from1,$cc="")
                {
                        $limite = "_parties_".md5 (uniqid (rand()));
                        $headers  = "MIME-Version: 1.0\r\n";
                        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                        if ($cc != "")
                        {
                                                        $headers .= "Cc: $cc\r\n";
                        }
                        $headers .= "From: $from1\r\n";
                        @mail($to,$subject,$mailcontent,$headers);
                }
                
                

                function fix_keys($array) {
                    foreach ($array as $k => $val) {
                        if (is_array($val))
                            $array[$k] = $this->fix_keys($val); //recurse
                    }
                    return array_values($array);
                }

}
?>