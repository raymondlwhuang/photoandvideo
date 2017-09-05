<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ItemComment extends MY_Controller {
	public function index()
	{
		$category_id = $this->input->get('category_id');
		$item_id = $this->input->get('item_id');
		$user_id = $this->session->userdata('id');
		$sOption = '';
		$this->load->model('category_item');
		$getcategory_item=$this->category_item->_get(array('user_id'=>$user_id,'category_id'=>$category_id));
		if($getcategory_item){
			foreach($getcategory_item as $option) {
				$sOption .= "<option value='".$option->id."'>".$option->category_item."</option>";
			}
		}
		echo "
		Description <input type=\"image\" src='/images/add.png' name='AddItem' value='Add Item'  onclick=\"SetVisibleDiv('none');SetDialog('Description',3);\" />
		<select name='item_id' id='item_id' class='input' onChange='SetDisp(6);'>
		$sOption
		</select>";
		
	}
}
