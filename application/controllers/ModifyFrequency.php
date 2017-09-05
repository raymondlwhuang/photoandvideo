<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModifyFrequency extends MY_Controller {
	public function index()
	{
		$item_frequency_id = $this->input->get('item_frequency_id');
		$user_id = $this->session->userdata('id');
		$delete = $this->input->get('delete');
		$cloak_keyword = "raymond".$this->session->userdata('pin');
		$e = new endec(); 
		$zero = $e->new_encode("0");
			if($delete ==1) $this->general_model->_delete(array('table'=>'item_frequency','user_id'=>$user_id,'id'=>$item_frequency_id));
			else {
				$amount = $this->input->get('amount');
				$newamount = $e->new_encode($amount);
				$this->general_model->_update(array('table'=>'item_frequency','user_id'=>$user_id,'id'=>$item_frequency_id),array('amount'=>$newamount));
			}
			$getitem_frequency=$this->general_model->_get(array('table'=>'item_frequency','user_id'=>$user_id));
			$sOption = '';
			if($getitem_frequency){
				foreach($getitem_frequency as $option) {
					$get_desc=$this->general_model->_get(array('table'=>'sp_frequency','id'=>$option->frequency_id));
					if($get_desc){
						foreach($get_desc as $descResult) {
							$description = $descResult->frequency;
						}
					}
					$get_spender=$this->general_model->_get(array('table'=>'spender','user_id'=>$user_id,'id'=>$option->spender_id));
					if($get_spender){
						foreach($get_spender as $spenderResult) {
							$name = $spenderResult->name;
						}
					}
					$get_category=$this->general_model->_get(array('table'=>'spending_category','user_id'=>0,'id'=>$option->category_id));
					if($get_category){
						foreach($get_category as $categoryResult) {
							$category = $categoryResult->category;
						}
					}
					$get_category_item=$this->general_model->_get(array('table'=>'category_item','user_id'=>0,'id'=>$option->item_id));
					if($get_category_item){
						foreach($get_category_item as $itemResult) {
							$category_item = $itemResult->category_item;
						}
					}
					$get_bank=$this->general_model->_get(array('table'=>'sp_bank','user_id'=>$user_id,'id'=>$option->bank_id));
					if($get_bank){
						foreach($get_bank as $bankResult) {
							$bank = $bankResult->bank;
						}
					}
					$amount = $e->new_decode($option->amount);
					$disp = "$name($category=>$category_item) \$$amount will recorded $description starting at $".$option->start_date."=>$bank.";
					if($item_frequency_id==$option->id) $sOption .= "<option value='".$option->id."' selected='selected'>$disp</option>";
					else $sOption .= "<option value='".$option->id."'>$disp</option>";
				}
			}
			echo $sOption;
	}
}
