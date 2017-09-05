<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetBank extends MY_Controller {
	public function index()
	{
		$cloak_keyword = "raymond".$this->session->userdata('pin');
		$e = new endec(); 
		$encodebalance = $e->new_encode("0");
		$user_id = $this->session->userdata('id');
		$category_id = $this->input->get('category_id');
		$item_id = $this->input->get('item_id');
		if(isset($_GET['type_id']))	$type_id = $this->input->get('type_id');
		$bank_id = $this->input->get('bank_id');
		$spender_id = $this->input->get('spender_id');
		if(isset($type_id)) $getBank=$this->general_model->_get(array('table'=>'spending','user_id'=>$user_id,'spender_id'=>$spender_id,'type_id'=>$type_id,'sortBy'=>'id','sortDirection'=>'desc','limit'=>1));
		else $getBank=$this->general_model->_get(array('table'=>'spending','user_id'=>$user_id,'spender_id'=>$spender_id,'category_id'=>$category_id,'item_id'=>$item_id,'sortBy'=>'id','sortDirection'=>'desc','limit'=>1));
		if (!$getBank){
			$getBank=$this->general_model->_get(array('table'=>'spending','user_id'=>$user_id,'spender_id'=>$spender_id,'type_id'=>1,'sortBy'=>'id','sortDirection'=>'desc','limit'=>1));
		}
		if($getBank){
			foreach($getBank as $Row) {
				$bank_id = $Row->bank_id;
				if(!isset($type_id)) $type_id = $Row->type_id;
			}
		}
		$balance = "$0.00";
		$vRes=$this->general_model->_get(array('table'=>'sp_bank','id'=>$bank_id));
		if($vRes) {
			$balance = "$".$e->new_decode($vRes[0]->balance);
		}	
		$sOption = '';
		$dispbalance = 0;
		$getsp_bank=$this->general_model->_get(array('table'=>'sp_bank','user_id'=>$user_id));
		if($getsp_bank){
			foreach($getsp_bank as $option) {
				if($option->id==$bank_id) $sOption .= "<option value='".$option->id."' selected>".$option->bank."</option>";
				else $sOption .= "<option value='".$option->id."'>".$option->bank."</option>";
			}
		}
		if(!isset($type_id)) $type_id='';
		if($category_id==1) {
			if($item_id==77) echo "<font id=\"bank_desc\">From</font>";
			else echo "<font id=\"bank_desc\">To</font>";
		}
		else
		echo "<font id=\"bank_desc\">Will Pay From</font>";
		echo "<input type=\"image\" src=\"/images/add.png\" name=\"AddBank\" value=\"Add Bank\"  onclick=\"SetVisibleDiv('none');SetDialog('Bank',7);\"/>
			<select name=\"bank_id\" id=\"bank_id\" class=\"input\" onChange=\"SetDisp(5);\">
			$sOption
			</select>
			<div id=\"balance\"></div>
			<div>
			Rimind
			<select name=\"reminder\" id=\"reminder\" class=\"input\" onChange=\"SetRminder();\">
				<option value='1' selected>Never</option>
				<option value='2'>Daily</option>
				<option value='3'>Weekly</option>
				<option value='4'>Bi-Weekly</option>
				<option value='5'>Monthly</option>
				<option value='9'>Bi-Monthly</option>
				<option value='6'>Quarterly</option>
				<option value='7'>Semi-Annually</option>
				<option value='8'>Yearly</option>
			</select>
			</div>
			</div>
		";
	}
}
