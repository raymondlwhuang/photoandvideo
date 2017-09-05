<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddGroup extends MY_Controller {
	public function index()
	{
		IF(isset($_POST['viewer_group']))
		{
			$get=$this->user->Get1($this->input->post('user_id'));
			if($get) {
				$options=array(
					'table'=>'viewer_group',
					'user_id'=>$this->input->post('user_id'),
					'viewer_group'=>$this->input->post('viewer_group'),
					'owner_path'=>$get->owner_path
				);
				$this->general_model->_add($options);
			}
			$this->load->model('viewer_group');
			$get2=$this->viewer_group->Get1($this->input->post('user_id'));
			$sOption = '';
			if($get2){
				foreach($get2 as $option) {
					if($option->viewer_group == $this->input->post('viewer_group')) $sOption .= "<option value='$option->id' selected>$option->viewer_group</option>";
					else $sOption .= "<option value='$option->id'>$option->viewer_group</option>";
				}
			}
			echo $sOption;	
		}

	}
}
