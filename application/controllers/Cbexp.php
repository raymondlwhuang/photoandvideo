<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cbexp extends MY_Controller {
	public function index()
	{
		$this->load->view("cbexp_demo");
	}
}
