<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Endecode extends MY_Controller {
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
		if($this->session->userdata('id'))
		{
			$New = new endec();
			$Result=$this->general_model->_get(array('table'=>'sp_monthly','user_id'=>$this->session->userdata('id')));
			if($Result){
				foreach($Result as $Row){
					$amount1 = $Row->temp_income;
					$amount = $New->new_encode($amount1);
					$this->general_model->_update(array('table'=>'sp_monthly','id'=>$Row->id),array('monthly_income'=>$amount));
				}
			}
			$ResultNew=$this->general_model->_get(array('table'=>'sp_monthly','user_id'=>$this->session->userdata('id')));
			if($ResultNew){
				foreach($ResultNew as $Row2){
				$amount2 = $Row2->temp;
				$expenses = $New->new_encode("$amount2");
				$this->general_model->_update(array('table'=>'sp_monthly','id'=>$Row2->id),array('monthly_expenese'=>$expenses));
				}
			}
		}
	}
}
