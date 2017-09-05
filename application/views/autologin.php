<?php
if(isSet($cookie_name)) {
	// Check if the cookie exists
	if(isSet($_COOKIE[$cookie_name])) {
		parse_str($_COOKIE[$cookie_name]);
		//	$email_address = $usr;
		$options=array('table'=>'user','email_address'=>$usr,'password'=>$hash);
		$record=$this->general_model->_get($options);
		if ($record){
			$config_username = $usr;
			$config_password = $hash;
			foreach($record as $name=>$value){
				$this->session->set_userdata("$name", $value);
			} 	  
			$this->session->set_userdata('username', $usr);
			$this->session->set_userdata('private', "yes");
			$this->session->set_userdata('MsgBox', "no");
			$options=array('table'=>'user','is_active'=>3);
			$where=array('email_address' =>$email_address);
			$this->general_model->_update($options,$where);
			$options=array('table'=>'view_permission','is_active'=>3);
			$where=array('owner_email' =>$email_address,'is_active >'=>1,'is_active <'=>9);
			$this->general_model->_update($options,$where);
			session_write_close();
		}
	}
}
?>