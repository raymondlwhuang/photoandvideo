<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddItem extends MY_Controller {
	public function index()
	{
		$this->load->model('category_item');
		$category_item = $this->input->get('category_item');
		$user_id = $this->session->userdata('id');
		$category_id = $this->input->get('category_id');
		$vRes=$this->general_model->_get(array('table'=>'category_item','category_id'=>$category_id,'category_item'=>$category_item));
		if(!$vRes && $category_item!='')$this->general_model->_add(array('table'=>'category_item','user_id'=>$user_id,'category_id'=>$category_id,'category_item'=>$category_item));
		$sOption = '';
		$getcategory_item=$this->category_item->_get(array('user_id'=>$user_id,'category_id'=>$category_id));
		if($getcategory_item){
			foreach($getcategory_item as $option) {
				if($option->category_id == $category_id && $option->category_item=="$category_item") $sOption .= "<option value='".$option->id."' selected>".$option->category_item."</option>";
				else $sOption .= "<option value='".$option->id."'>".$option->category_item."</option>";
			}
		}
		echo $sOption;
	}
}
