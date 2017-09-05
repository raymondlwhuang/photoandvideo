<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddBank extends MY_Controller {
	public function index()
	{
		$cloak_keyword = "raymond".$this->session->userdata('pin');
		$e = new endec(); 
		if(isset($_GET['bank'])) $bank = $this->input->get('bank'); else $bank = '';
		$user_id = $this->session->userdata('id');
		$spender_id = $this->input->get('spender_id');
		if(isset($_GET['adjustment'])) $balance = $this->input->get('adjustment'); else $balance = 0;
		$encodebalance = $e->new_encode("0");
		$adjustment = $e->new_encode("$balance");
		$vRes=$this->general_model->_get(array('table'=>'sp_bank','user_id'=>$user_id,'bank'=>$bank,'sortBy'=>'id','sortDirection'=>'desc','limit'=>1));
		if (!$vRes && $bank != ''){
			$encodebalance = $e->new_encode("$balance");
			$this->general_model->_add(array('table'=>'sp_bank','user_id'=>$user_id,'spender_id'=>$spender_id,'bank'=>$bank,'balance'=>$encodebalance,'allowduplicate'=>true));
		}
		else if($bank != '') {
			foreach($vRes as $option1) {
				$oldBalance = $e->new_decode($option1->balance);
				$newBalance = $balance + (float)$oldBalance;
				$encodebalance = $e->new_encode("$newBalance");
			}	
			$this->general_model->_update(array('table'=>'sp_bank','user_id'=>$user_id,'bank'=>$bank,'sortBy'=>'id','sortDirection'=>'desc','limit'=>1),array('balance'=>$encodebalance));
		}
		$before_insert=$this->general_model->_get(array('table'=>'sp_bank','user_id'=>$user_id,'bank'=>$bank,'sortBy'=>'id','sortDirection'=>'desc','limit'=>1));
		if($before_insert) {
			$bank_id = $before_insert[0]->id;
		}	
		$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'ajust_amount'=>$adjustment,'balance'=>$encodebalance,'description'=>'Adjustment'));
		$sOption = '';
		$dispbalance = 0;
		$getsp_bank=$this->general_model->_get(array('table'=>'sp_bank','user_id'=>$user_id));
		if($getsp_bank){
			foreach($getsp_bank as $option) {
				if($option->bank=="$bank") $sOption .= "<option value='".$option->id."' selected>".$option->bank."</option>";
				else $sOption .= "<option value='".$option->id."'>".$option->bank."</option>";
			}
		}
		echo $sOption;	
	}
}
