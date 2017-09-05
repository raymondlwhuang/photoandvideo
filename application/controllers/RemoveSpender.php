<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RemoveSpender extends MY_Controller {
	public function index()
	{
		IF(isset($_GET['spender_id']))
		{
			$user_id = $this->session->userdata('id');
			$spender_id = $this->input->get('spender_id');
			$this->general_model->_delete(array('table'=>'sp_bank','user_id'=>$user_id,'spender_id'=>$spender_id));
			$this->general_model->_delete(array('table'=>'spender','user_id'=>$user_id,'id'=>$spender_id));
			$getSpender=$this->general_model->_get(array('table'=>'spender','user_id'=>$user_id));
			$sOption = '';
			if($getSpender){
				foreach($getSpender as $option) {
					$sOption .= "<option value='".$option->id."'>".$option->name."</option>";
				}
			}
			echo $sOption;
		}
	}
}
