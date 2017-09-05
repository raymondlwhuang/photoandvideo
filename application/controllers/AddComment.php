<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddComment extends MY_Controller {
	public function index()
	{
		if(isset($_GET['comment']))	$comment = $this->input->get('comment'); else $comment = '';
		$category_id = $this->input->get('category_id');
		$item_id = $this->input->get('item_id');
		if($comment!="") $this->general_model->_add(array('table'=>'sp_comment','category_id'=>$category_id,'item_id'=>$item_id,'comment'=>$comment));
		$getComment=$this->general_model->_get(array('table'=>'sp_comment','category_id'=>$category_id,'item_id'=>$item_id));
		IF($comment == '') $sOption = '<option value="0" selected></option>';
		ELSE $sOption = '<option value="0"></option>';
		if($getComment){
			foreach($getComment as $option) {
				if($option->category_id==$category_id && $option->item_id==$item_id && $comment != '') $sOption .= "<option value='".$option->id."' selected>".$option->comment."</option>";
				else $sOption .= "<option value='".$option->id."'>".$option->comment."</option>";
			}
		}
		echo $sOption;	
	}
}
