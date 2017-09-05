<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddType extends MY_Controller {
	public function index()
	{
		if(isset($_GET['Type'])) $Type = $this->input->get('Type'); else $Type = '';
		$user_id = $this->session->userdata('id');
		if($Type!='') $this->general_model->_add(array('table'=>'sp_payment_type','user_id'=>0,'Type'=>$Type));
		$sOption = '';
		$getsp_payment_type=$this->general_model->_get(array('table'=>'sp_payment_type','user_id'=>0));
		if($getsp_payment_type){
			foreach($getsp_payment_type as $option) {
				if($option->Type=="$Type") $sOption .= "<option value='".$option->id."' selected>".$option->Type."</option>";
				else $sOption .= "<option value='".$option->id."'>".$option->Type."</option>";
			}
		}
		echo $sOption;	
	}
}
