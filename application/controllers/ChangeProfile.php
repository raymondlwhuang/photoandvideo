<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ChangeProfile extends MainController {
	public function index()
	{
		$get=$this->user->_get1(array('id'=>$this->session->userdata('id')));
		if($get) {
			$profile=$get->profile_picture;
			$profile_count = $get->profile_count + 1;
		}
		$get1=$this->general_model->_get(array('table'=>'profile_picture','user_id'=>$this->session->userdata('id')));
		if($get1){
			foreach($get1 as $row2) {
				$oldprofile["$row2->id"]=$row2->profile_picture;
			}
		}
		$profile3=$get->profile_picture;
		$pos=strrpos($profile3,"/");
		if($profile3!="/images/profile/default_profile.png") $OrgPicture=substr($profile3,0,$pos)."/original".substr($profile3,$pos);
		else $OrgPicture=$profile3;
		if ($_FILES){
			$limit_size=10* 1024*1000;
			$name = $_FILES['infile']['name']; 
			$Orgpictures = "/images/profile/".$this->session->userdata('owner_path')."/original/";
			$pictures = "/images/profile/".$this->session->userdata('owner_path')."/";
			$temppictures = "/temppictures/";
			@mkdir("$pictures");
			@mkdir("$Orgpictures");
			@mkdir("$temppictures");
			
			$targetfilepath= $Orgpictures . $name;
			$picturefilepath= $pictures . $name;
			$temppath=$temppictures . $name;
			$file_size=$_FILES['infile']['size'];
			$message='';
			if($file_size <= $limit_size){
				$result = move_uploaded_file($_FILES['infile']['tmp_name'], $temppath); 
				 
				if ($result){ 
					ResizeImg::ResizeImage($temppath,240,180,$picturefilepath);
					ResizeImg::ResizeImage($temppath,1920,1440,$targetfilepath);
					unlink($temppath);
					$message .= "<font color='red' size='3'>Your profile picture $name has been Uploaded succefully. </font><br>"; 
					$get1=$this->general_model->_get(array('table'=>'profile_picture','user_id'=>$this->session->userdata('id'),'profile_picture'=>$picturefilepath));
					if (!$get1){
						$this->general_model->_add(array('table'=>'profile_picture','id'=>$profile_count,'user_id'=>$this->session->userdata('id'),'profile_picture'=>$picturefilepath));
					}
					else $profile_count = $profile_count - 1;
					$this->session->set_userdata('profile_picture',$picturefilepath);
					$this->general_model->_update(array('table'=>'profile_picture','email_address'=>$this->session->userdata('email_address')),array('profile_picture'=>$picturefilepath,'profile_count'=>$profile_count));
				}
				else $message .= "<font color='red' size='3'>Profile picture upload Failed! </font><br>"; 
			}
			else $message .= "<font color='red' size='3'>File size is over limit! Profile picture upload Failed! </font><br>";
			$this->session->set_userdata('message', $message);
		}
		if(isset($_GET['link']))
		{
			$link=$this->function_model->newdecode($this->input->get('link'));
			$pieces = explode(",", $link);
			$id = (int)$pieces[0];
			$name = $pieces[1];
			$pos=strrpos($name,"/");
			$OrgPicture=substr($name,0,$pos)."/original".substr($name,$pos);
			$OK=$this->general_model->_delete(array('table'=>'profile_picture','id'=>$id,'user_id'=>$this->session->userdata('id')));
			if($OK) {
				unlink($name);
				unlink($OrgPicture);
				$get1=$this->user->_get1(array('id'=>$this->session->userdata('id'),'profile_picture'=>$name));
				if ($get1) {
					$this->general_model->_update(array('table'=>'user','id'=>$this->session->userdata('id')),array('profile_count'=>($get1->profile_count - 1),'profile_picture'=>'/images/profile/default_profile.png'));
					$this->session->set_userdata('profile_picture', "/images/profile/default_profile.png");
				}
				else {
					$this->general_model->_update(array('table'=>'user','id'=>$this->session->userdata('id')),array('profile_count'=>($get1->profile_count - 1)));
				}
			}
		}
		elseif(isset($_GET['link2']))
		{
			$link2=$this->function_model->newdecode($this->input->get('link2'));
			$pieces = explode(",", $link2);
			$id = (int)$pieces[0];
			$name = $pieces[1];
			$this->general_model->_update(array('table'=>'user','id'=>$this->session->userdata('id')),array('profile_picture'=>$name));
			$this->session->set_userdata('profile_picture', $name);
		}
		$this->data['OrgPicture'] = $OrgPicture;
		if (isset($oldprofile)) $this->data['oldprofile'] = $oldprofile;

		$this->load->view('setup_header');
		$this->load->view("header",$this->data);
		$this->load->view("changeProfile_view",$this->data);
	}
}
