<?php
	$this->session->set_userdata('first_name',ucfirst(strtolower($first_name)));
	$this->session->set_userdata('last_name', ucfirst(strtolower($last_name)));
	$this->session->set_userdata('id', $id);
	$this->session->set_userdata('owner_path', $owner_path);
	$this->session->set_userdata('name', ucfirst(strtolower($first_name))." ".ucfirst(strtolower($last_name)));
	$this->session->set_userdata('profile_picture', $profile_picture);
	$this->session->set_userdata('admin', $is_super_admin);
	$this->session->set_userdata('discoverable', $discoverable);
	$this->session->set_userdata('email_address', $email_address);
	$this->session->set_userdata('username', $email_address);
	$this->session->set_userdata('private', "yes");
	$this->session->set_userdata('MsgBox', "no");
	$this->general_model->_update(array('table'=>'user','is_active'=>3),array('email_address'=>strtolower($email_address)));
	$this->view_permission->update($this->input->post('email_address'));
	$login_ok = true;
	session_write_close();
	if($autologin == 1)
	{
		setcookie ($cookie_name, 'usr='.$email_address.'&hash='.$password, time() + $cookie_time);
	}
