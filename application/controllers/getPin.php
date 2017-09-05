<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetPin extends MY_Controller {
	public function index()
	{
		if(!$this->session->userdata('pin'))
		{	
			if(isSet($_COOKIE['Pid']))
			{
				parse_str($_COOKIE['Pid']);
				$this->session->set_userdata('pin',$this->function_model->newdecode($pin));
				redirect('/HERecorder', 'refresh');
			}
		}		
		elseif($this->input->post('submit_x'))
		{
			$pin = $this->function_model->newencode($this->input->post('pin'));
			$RememberPin = $this->input->post('RememberPin');
			$this->session->set_userdata('pin',$this->function_model->newdecode($pin));
			if($RememberPin == 1)
			{
				setcookie ('Pid', 'pin='.$pin, time() + 2592000);
			}
			redirect('/HERecorder', 'refresh');
		}
		$this->load->view("getPin_view");
	}
}
