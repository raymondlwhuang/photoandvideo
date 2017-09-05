<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expensetracker extends MY_Controller {
	public function index()
	{
		$this->load->view("expenseTracker_view");
	}
}
