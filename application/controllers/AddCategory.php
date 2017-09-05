<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddCategory extends MY_Controller {
	public function index()
	{
		$this->load->model('spending_category');
		$category = $this->input->get('category');
		$user_id = $this->session->userdata('id');
		$vRes=$this->spending_category->_get(array('user_id'=>$user_id,'category'=>$category));
		if (!$vRes && $category != ''){
			$this->general_model->_add(array('table'=>'spending_category','user_id'=>$user_id,'category'=>$category));
			$get=$this->general_model->_get(array('table'=>'spending_category','category'=>$category,'limit'=>1));
			if($get){
				foreach($get as $row){
					$this->general_model->_add(array('table'=>'category_item','user_id'=>$user_id,'category_id'=>$row->id,'category_item'=>'Other'));
				}
			}
		}	
		$sOption = '';
		$getspending_category=$this->spending_category->_get(array('user_id'=>$user_id,'sortBy'=>'category'));
		if($getspending_category){
			foreach($getspending_category as $option) {
				if($option->category == "$category") $sOption .= "<option value='".$option->id."' selected>".$option->category."</option>";
				else $sOption .= "<option value='".$option->id."'>".$option->category."</option>";
			}
		}
		echo $sOption;	
	}
}
