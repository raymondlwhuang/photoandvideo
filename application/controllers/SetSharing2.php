<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SetSharing2 extends MY_Controller {
	public function index()
	{
		$user_id=$this->input->post('user_id');
		$share_flag = $this->input->post('share_flag');
		$viewer_id = $this->input->post('viewer_id');
		$get=$this->general_model->_get(array('table'=>'user','id'=>$viewer_id));
		if($get)
		{
			$name=$get[0]->first_name . " " . $get[0]->last_name;
			$this->view_permission->Update_share($share_flag,$user_id,$viewer_id);
			if($share_flag==0) echo "This is to confirmed that\n$name will not allowe for sharing pictures/videos!";
			elseif($share_flag==1) echo "This is to confirmed that\n$name need to ask before sharing pictures/videos!";
			elseif($share_flag==2) echo "Pictures/videos sharing permit has been set up for $name!";
		}
	}
}
