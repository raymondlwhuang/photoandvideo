<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FileUploader extends MainController {
	public function index()
	{
		$description = "";
		$viewer_group = "";
		$limit_size=10* 1024*1000;
		$MaxSize= ($limit_size / (1024*1000));
		$browserInf=$this->function_model->getBrowser();
		
		if($_FILES){
			if(isset($_POST['viewer_group']))
			{
				$viewer_group = $this->input->post('viewer_group');
				$description = $this->input->post('description');
			}
			$message =  "<font color='darkblue'>No. files uploaded : ".count($_FILES['infile']['name'])."</font><br>";  
			$public=md5($this->session->userdata('id'));
			$pictures = "/pictures/".$this->session->userdata('owner_path')."/";
			$Orgpictures = "/Orgpictures/".$this->session->userdata('owner_path')."/";
			$Temporary="/Temporary/";
			$temppictures = "/temppictures/";
			@mkdir("/Orgpictures/");
			@mkdir("$pictures");
			@mkdir("$Orgpictures");
			@mkdir("$temppictures");
			@mkdir("$Temporary");
			for ($i = 0; $i < count($_FILES['infile']['name']); $i++) { 
				$name = $_FILES['infile']['name'][$i]; 
				if($viewer_group!='' && $viewer_group !='Public' && $viewer_group !='Temporary')
				{
					@mkdir("/pictures/".$this->session->userdata('owner_path')."/$viewer_group/");
					@mkdir("/Orgpictures/".$this->session->userdata('owner_path')."/$viewer_group/");
				}
				if($viewer_group =='') {
					$targetfilepath= "/Orgpictures/".$this->session->userdata('owner_path')."/" . $name;
					$picturefilepath= "/pictures/".$this->session->userdata('owner_path')."/" . $name;
				}
				elseif($viewer_group =='Public') {
					$targetfilepath= "/Orgpictures/$public" . $name;
					$picturefilepath= "/pictures/$public" . $name;
				}
				elseif($viewer_group =='Temporary') {
					$targetfilepath= "/Orgpictures/$public" . $name;
					$picturefilepath= "/Temporary/$public" . $name;
				}
				else {
					$targetfilepath= "/Orgpictures/".$this->session->userdata('owner_path')."/$viewer_group/" . $name;
					$picturefilepath= "/pictures/".$this->session->userdata('owner_path')."/$viewer_group/" . $name;
				}
				$temppath=$temppictures . $name;
				$file_size=$_FILES['infile']['size']["$i"];
				if($file_size <= $limit_size){
					$get=$this->picture_video->Get1($picturefilepath);
					if (!$get){
						 $result = move_uploaded_file($_FILES['infile']['tmp_name'][$i], $temppath); 
						 if ($result){
							ResizeImg::ResizeImage($temppath,480,360,$picturefilepath);
							ResizeImg::ResizeImage($temppath,1920,1440,$targetfilepath);
//							unlink($temppath);
							$today = date('Y-m-d');
							$options=array(
								'table'=>'upload_infor',
								'user_id'=>$this->session->userdata('id'),
								'viewer_group'=>$viewer_group,
								'description'=>$description,
								'upload_date'=>$today
							);
							$this->general_model->_add($options);
							if(!isset($description)) $description ="";
							$this->load->model('upload_infor');
							$get1=$this->upload_infor->Get1($this->session->userdata('id'),$description,$today,$viewer_group);
							if($get1)
							{
								$upload_id = $get1->id;
								$options=array(
									'table'=>'picture_video',
									'owner_path'=>$this->session->userdata('owner_path'),
									'picture_video'=>'pictures',
									'viewer_group'=>$viewer_group,
									'name'=>$picturefilepath,
									'upload_id'=>$upload_id
								);
								$this->general_model->_add($options);
							}
							$message .= "<font color='blue' size='3'>$name has been Uploaded succefully. </font><br>"; 
						 }
						else $message .= "<font color='red' size='3'>File upload Failed for $name. </font><br>"; 
					}
					else $message .= "<font color='red' size='3'>Duplicate upload not allowed for $name! Uploaded canceled. </font><br>";
				}
				else $message .= "<font color='red' size='3'>File size is over limit! File upload Failed for</font><font color='blue' size='3'> $name. </font><br>";
			}
			$message .= "<font color='red'><b>Upload complete.</b></font><br>";
			$this->session->set_userdata('message', $message);
		}
		$GroupResult=$this->view_permission->Get_group(array('user_id'=>$this->session->userdata('id'),'flag'=>1));
		if($GroupResult){
			foreach($GroupResult as $option) {
				$optionGroup["$option->id"] = $option->viewer_group;
			}
		}
		$this->data['bname']=$browserInf['name'];
		$this->data['MaxSize']=$MaxSize;
		$this->data['description']=$description;
		$this->data['viewer_group']=$viewer_group;
		if(isset($optionGroup)) $this->data['optionGroup']=$optionGroup;
		$this->load->view('setup_header');
		$this->load->view("header",$this->data);
		$this->load->view("fileUploader_view",$this->data);
	}
}
