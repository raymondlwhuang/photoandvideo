<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PictureVideoCheck extends MY_Controller {
	public function index()
	{
		if($this->input->get('FriendID'))
		{	
			$FriendID = $this->input->get('FriendID');
			$ViewerID = $this->input->get('ViewerID');
		}
		if($FriendID!="Public") {
			if($this->session->userdata('admin')!=1){
				if($ViewerID != $FriendID){
					$get=$this->view_permission->_get(array('user_id'=>$FriendID,'viewer_id'=>$viewer_id));
				}
				else {
					$get=$this->view_permission->_get(array('user_id'=>$ViewerID));
				}
			}
			else $get=$this->view_permission->_get(array('user_id'=>$FriendID));
			$passed=0;
			if ($get){
				foreach($get as $row)
				{	
					$curr_path = $row->owner_path;
					$permit[$curr_path][] = $row->viewer_group;
				}
				foreach ($permit as $key => $value) {
					foreach ($value as $key2 => $value2) {
						$get1=$this->picture_video->_get(array('owner_path'=>$key,'viewer_group'=>$value2));
						if($get1){
							$passed=1;
						}
					}
				}
			}
			echo $passed;
		}
		else echo 1;
	}
}
