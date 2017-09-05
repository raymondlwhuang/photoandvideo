<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SetGroup2 extends MY_Controller {
	public function index()
	{
		if($this->input->post('view_permission_id')!='All') {
			$get=$this->view_permission->Get0($this->input->post('view_permission_id'));
			if($get) {
				$user_id=$get->user_id;
				$options=array(
					'table'=>'view_permission',
					'user_id'=>$get->user_id,
					'owner_email'=>$get->owner_email,
					'owner_path'=>$get->owner_path,
					'viewer_group'=>$this->input->post('viewer_group'),
					'is_active'=>$get->is_active,
					'viewer_email'=>$get->viewer_email,
					'first_name'=>$get->first_name,
					'last_name'=>$get->last_name,
					'viewer_id'=>$get->viewer_id
				);
				$this->general_model->_add($options);			
			}
		}
		else {
			$user_id=$this->input->post('user_id');
			$get=$this->view_permission->Get_viewer_email1($user_id);
			if($get) {
				foreach($get as $row) {
					$options=array(
						'table'=>'view_permission',
						'user_id'=>$row->user_id,
						'owner_email'=>$row->owner_email,
						'owner_path'=>$row->owner_path,
						'viewer_group'=>$this->input->post('viewer_group'),
						'is_active'=>$row->is_active,
						'viewer_email'=>$row->viewer_email,
						'first_name'=>$row->first_name,
						'last_name'=>$row->last_name,
						'viewer_id'=>$row->viewer_id
					);
					$this->general_model->_add($options);			
				}
			}
		}
		$ViewerList = $this->view_permission->Get_viewer_email1($user_id);
		$ViewerList2 =$this->view_permission->Get_viewer_email2($user_id);
		$data['ViewerList']=$ViewerList;
		$data['ViewerList2']=$ViewerList2;
		$this->load->view("setGroup2_view",$data);
	}
}
