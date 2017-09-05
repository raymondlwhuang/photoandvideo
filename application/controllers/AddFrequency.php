<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddFrequency extends MY_Controller {
	public function index()
	{
		if(isset($_GET['frequency'])) $frequency = $this->input->get('frequency'); else $frequency = '';
		$user_id = $this->session->userdata('id');
		if($frequency!='') $this->general_model->_add(array('table'=>'sp_frequency','user_id'=>0,'frequency'=>$frequency));
		$sOption = '';
		$getsp_frequency=$this->general_model->_get(array('table'=>'sp_frequency','user_id'=>0));
		if($getsp_frequency){
			foreach($getsp_frequency as $option) {
				if($option->frequency=="$frequency") $sOption .= "<option value='".$option->id."' selected>".$option->frequency."</option>";
				else $sOption .= "<option value='".$option->id."'>".$option->frequency."</option>";
			}
		}
		echo $sOption;	
	}
}
