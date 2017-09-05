<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LastActivity extends MY_Controller {
	public function index()
	{
		$this->user->Update_last_activity($this->input->get('user_id'));
		$this->user->Active();
	}
}
