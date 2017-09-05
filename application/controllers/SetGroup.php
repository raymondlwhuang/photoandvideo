<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SetGroup extends MainController {
	public function index()
	{
		if(isset($_POST['Delete_x']))
		{
			$this->view_permission->Delete2($this->input->post('delete_id'));
		}
		$options=array(
			'table'=>'viewer_group',
			'user_id'=>$this->session->userdata('id'),
			'viewer_group'=>'Friend',
			'owner_path'=>$this->session->userdata('owner_path')
		);
		$this->general_model->_add($options);
		$this->load->model('viewer_group');
		$GroupResult=$this->viewer_group->Get1($this->session->userdata('id'));
		if($GroupResult) {
			foreach($GroupResult as $option){
				$optionGroup["$option->id"] = $option->viewer_group;
			}
		}
		$ViewerList=$this->view_permission->Get_viewer_email1($this->session->userdata('id'));
		if($ViewerList) $data['ViewerList']=$ViewerList;
		$ViewerList2=$this->view_permission->Get_viewer_email2($this->session->userdata('id'));
		if($ViewerList2) $data['ViewerList2']=$ViewerList2;
		if($optionGroup) $data['optionGroup']=$optionGroup;
		$this->load->view("setGroup_view",$data);
	}
}
