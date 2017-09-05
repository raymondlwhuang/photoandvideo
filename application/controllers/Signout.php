<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signout extends MY_Controller {
	public function index() {
		setcookie('member_name', '', time() - 96 * 3600, '/');
		setcookie('user_name', '', time() - 96 * 3600, '/');
		setcookie('member_pass', '', time() - 96 * 3600, '/');
		setcookie ('siteAuth', '', time() - 96 * 3600);
		setcookie ('Pid', '', time() - 96 * 3600);
//			setcookie ('greeting', '', time() - 96 * 3600);
		$this->user->Update_last_activity($this->session->userdata('id'));
		$this->view_permission->Set_activity($this->session->userdata('email_address'));
		
		foreach($_COOKIE as $name=>$value) {
			unset($_COOKIE["$name"]);
		}
		$this->session->sess_destroy();

		redirect('/', 'refresh');
		exit();
	}
	
}
