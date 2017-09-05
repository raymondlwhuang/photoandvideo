<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Introduction extends MY_Controller {
	public function index()
	{
		$this->load->view("introduction_view");
	}
}
