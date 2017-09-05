<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ChangePin extends MY_Controller {
	public function index()
	{
		if(!$this->session->userdata('pin'))
		{	
			if(isSet($_COOKIE['Pid']))
			{
				parse_str($_COOKIE['Pid']);
				$this->session->set_userdata('pin',$this->function_model->newdecode($pin));
				redirect('/', 'refresh');
			}
		}		
		if(isSet($_POST['submit_x']))
		{
			$Oldpin = $this->input->post('Oldpin');
			$pin = $this->function_model->newencode($this->input->post('pin'));
			$RememberPin = $this->input->post('RememberPin');
			$this->session->set_userdata('pin', $this->input->post('pin'));
			if($RememberPin == 1)
			{
				setcookie ('Pid', 'pin='.$pin, time() + $cookie_time);
			}
			$cloak_keyword = "raymond".$this->input->post('pin');
			$Recloak = "raymond".$Oldpin;
			$Old = new Rendec();
			$New = new endec();
			$Result=$this->general_model->_get(array('table'=>'spending','user_id'=>$this->session->userdata('id')));
			if($Result){
				foreach($Result as $Row){
					$amount1 = $Row->expenses;
					$amount = (float)$Old->new_decode("$amount1");
					$this->general_model->_update(array('table'=>'spending','id'=>$Row->id),array('amount'=>$amount));
				}
			}
			$ResultNew=$this->general_model->_get(array('table'=>'spending','user_id'=>$this->session->userdata('id')));
			if($ResultNew){
				foreach($ResultNew as $Row2){
				$amount2 = $Row2->amount;
				$expenses = $New->new_encode("$amount2");
				$this->general_model->_update(array('table'=>'spending','id'=>$Row2->id),array('expenses'=>$expenses,'amount'=>0));
				}
			}
			redirect('/', 'refresh');
		}
		$this->load->view("changePin_view");
	}
}
