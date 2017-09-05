<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddSpender extends MY_Controller {
	public function index()
	{
		IF($this->input->get('name'))
		{
			$name = $this->input->get('name');
			$user_id = $this->session->userdata('id');
			$vRes=$this->general_model->_get(array('table'=>'spender','user_id'=>$user_id,'name'=>$name,'limit'=>1));
			if (!$vRes && $name != ''){
				$this->general_model->_add(array('table'=>'spender','user_id'=>$user_id,'name'=>$name));
				$result=$this->general_model->_get(array('table'=>'spender','user_id'=>$user_id,'name'=>$name,'limit'=>1));
				$spender_id = $result[0]->id;
				$this->general_model->_add(array('table'=>'sp_bank','spender_id'=>$spender_id,'bank'=>"Cash On Hand($name)",'pay_now'=>1));
			}	
			$sOption = '';
			$getSpender=$this->general_model->_get(array('table'=>'spender','user_id'=>$user_id));
			if($getSpender){
				foreach($getSpender as $option) {
					if($option->name=="$name") $sOption .= "<option value='".$option->id."' selected>".$option->name."</option>";
					else $sOption .= "<option value='".$option->id."'>".$option->name."</option>";
				}
			}
			echo $sOption;	
		}
	}
}
