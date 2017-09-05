<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PickFriend extends MainController {
	public function index()
	{
		$get=$this->view_permission->Get1($this->session->userdata('id'));
		if($get){
			foreach($get as $option) {
				$get1=$this->view_permission->Get($this->session->userdata('email_address'),$option->owner_email);
				if($get1) {
					$get2=$this->user->Get($option->owner_email);
					if($get2) {
						$first_name=$get2->first_name;
						$last_name=$get2->last_name;
						$viewer_id=$get2->id;
					}
					$options=array(
						'table'=>'view_permission',
						'user_id'=>$this->session->userdata('id'),
						'owner_email'=>$this->session->userdata('email_address'),
						'owner_path'=>$this->session->userdata('owner_path'),
						'is_active'=>-2,
						'viewer_id'=>$viewer_id,
						'viewer_email'=>$option->owner_email,
						'first_name'=>$first_name,
						'last_name'=>$last_name
					);
					$this->general_model->_add($options);				
				}
				$get3=$this->view_permission->Get2($option->owner_email);
				if($get3){
					foreach($get3 as $option2) {
						$get4=$this->view_permission->Get($this->session->userdata('email_address'),$option2->viewer_email);
						if($get4 && $option2->viewer_email!=$this->session->userdata('email_address')) {
							$get5=$this->user->Get($option2->viewer_email);
							if($get5) {
								$first_name=$get5->first_name;
								$last_name=$get5->last_name;
								$viewer_id=$get5->id;
							}
							$options=array(
								'table'=>'view_permission',
								'user_id'=>$this->session->userdata('id'),
								'owner_email'=>$this->session->userdata('email_address'),
								'owner_path'=>$this->session->userdata('owner_path'),
								'is_active'=>-2,
								'viewer_id'=>$viewer_id,
								'viewer_email'=>"$option->owner_email",
								'first_name'=>"$first_name",
								'last_name'=>"$last_name"
							);
							$this->general_model->_add($options);							
						}
					}
				}
			}
		}
		$count=0;
		$get5=$this->view_permission->Get3($this->session->userdata('email_address'));
		if($get5){
			foreach($get5 as $option4) {
				$get6=$this->user->Get($option4->viewer_email);
				if($get6) {
					$profile_picture=$get6->profile_picture;
					$first_name=$get6->first_name;
					$last_name=$get6->last_name;
				}
					if(!isset($optionViewer_id) && isset($profile_picture)) {
						$optionViewer_id[] =  $option4->viewer_id;
						$optionPicture[] = $profile_picture;
						$FirstName[] = $first_name;
						$LastName[] = $last_name;
						$optionSel[] = $option4->is_active;
						$count++;
					}
					elseif(isset($optionViewer_id)) {
						$duplicated = false;
						foreach($optionViewer_id as $id=>$Viewer_id) {
							if($Viewer_id==$option4->viewer_id) $duplicated = true;
						}
						if($duplicated == false){
							$optionViewer_id[] = $option4->viewer_id;
							$optionPicture[] = $profile_picture;
							$FirstName[] = $first_name;
							$LastName[] = $last_name;
							$optionSel[] = $option4->is_active;
							$count++;
						}
					}
			}
		}
		$data['count']=$count;
		if(isset($optionViewer_id)) $data['optionViewer_id']=$optionViewer_id;
		if(isset($optionSel)) $data['optionSel']=$optionSel;
		if(isset($optionPicture)) $data['optionPicture']=$optionPicture;
		if(isset($FirstName)) $data['FirstName']=$FirstName;
		if(isset($LastName)) $data['LastName']=$LastName;
		if($count==0) {
			$this->load->view("addFriend_view");
		}
		else {	
			$this->load->view("pickFriend_view",$data);
		}
	}
}
