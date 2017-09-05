<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProfilePicture extends MainController {
	public function index()
	{
		if(isset($_GET['link']))
		{
			$link=$this->function_model->newdecode($this->input->get('link'));
			$pieces = explode(",", $link);
			$id = (int)$pieces[0];
			$name = $pieces[1];
			$FriendPath = $pieces[2];
			$pos=strrpos($name,"/");
			$OrgPicture1=substr($name,0,$pos)."/original".substr($name,$pos);
			$OK=$this->general_model->_delete(array('table'=>'profile_picture'),array('id'=>$id));
			if($OK) {
				unlink($name);
				unlink($OrgPicture1);
				$get=$this->user->_get1(array('owner_path'=>$FriendPath,'profile_picture'=>$name));
				if ($get) {
					$this->general_model->_update(array('table'=>'user','owner_path'=>$FriendPath),array('profile_count'=>($get->profile_count-1),'profile_picture'=>'/images/profile/default_profile.png'));
					$this->session->set_userdata('profile_picture',"/images/profile/default_profile.png");
				}
				else {
					$this->general_model->_update(array('table'=>'user','owner_path'=>$FriendPath),array('profile_count'=>($get->profile_count-1)));
				}
			}
		}

		if(isset($_GET['FriendPath'])) $FriendPath = $this->input->get('FriendPath'); else $FriendPath='Public';
		if(strtolower($FriendPath)!='public') {
			$get1=$this->user->_get1(array('owner_path'=>$FriendPath));
			if($get1) {
				$name= $get1->first_name." ".$get1->last_name."'s Profile Pictures";
				$user_id = $get1->id;
				$get2=$this->general_model->_get(array('table'=>'profile_picture','user_id'=>$get1->id));
				if ($get2){
					foreach($get2 as $row2) {
						$pp_id[]=$row2->id;
						$oldprofile[]=$row2->profile_picture;
						$pos=strrpos($row2->profile_picture,"/");
						if($row2->profile_picture!="/images/profile/default_profile.png") $OrgPicture[]=substr($row2->profile_picture,0,$pos)."/original".substr($row2->profile_picture,$pos);
						else $OrgPicture[]="/images/profile/default_profile.png";
					}
				}
				else {
					$oldprofile[]="/images/profile/default_profile.png";
					$OrgPicture[]="/images/profile/default_profile.png";
				}
			}
		}
		else {
		 $oldprofile[]="/images/profile/default_profile.png";
		 $OrgPicture[]="/images/profile/default_profile.png";
		}
		$this->data['OrgPicture'] = $OrgPicture;
		$this->data['FriendPath'] = $FriendPath;
		if(isset($name)) $this->data['name'] = $name;
		if (isset($oldprofile)) $this->data['oldprofile'] = $oldprofile;
		if (isset($pp_id)) $this->data['pp_id'] = $pp_id;
		$this->load->view('setup_header');
		$this->load->view("header",$this->data);
		$this->load->view("profilePicture_view",$this->data);
	}
}
