<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends MY_Controller {
	public function index()
	{
		$this->load->view("help_view");
	}
}
