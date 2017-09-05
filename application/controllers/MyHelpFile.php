<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyHelpFile extends MY_Controller {
	public function index()
	{
		$this->load->view("myHelpFile_view");
	}
}
