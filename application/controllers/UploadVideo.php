<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UploadVideo extends MainController {
	public function index()
	{
		if ($_FILES){
			$viewer_group = "";
			$description = "";
			if(isset($_POST['viewer_group']))
			{
				$viewer_group = $this->input->post('viewer_group');
				$description = $this->input->post('description');
				$this->session->set_userdata('viewer_group', $viewer_group);
				$this->session->set_userdata('description', $description);
			}
			$message =  "";
			$public=md5($this->session->userdata('id'));
				$name = $_FILES['infile']['name']; 
				$videos = "/videos/".$this->session->userdata('owner_path')."/";
				@mkdir("$videos");
				if($viewer_group!='' && $viewer_group !='Public' && $viewer_group !='Temporary')
				{
					@mkdir("/videos/".$this->session->userdata('owner_path')."/$viewer_group/");
				}
				$pos=strrpos($name,".");
				$ext=strtolower(substr($name,($pos+1)));
				$name1=substr($name,0,$pos);
				$OKformat=0;
				if($ext=='mp4' || $ext=='m4v' || $ext=='ogg' || $ext=='ogv' || $ext=='webm') {
					$OKformat=1;
					if($ext=='m4v') $name=$name1.".mp4";
				}
				
				if($viewer_group =='')
					$targetfilepath= "/$picture_video/".$this->session->userdata('owner_path')."/" . $name;
				elseif($viewer_group =='Public')
					$targetfilepath= "/$picture_video/$public" . $name;
				elseif($viewer_group =='Temporary')
					$targetfilepath= "/Temporary/" . $name;
				else
					$targetfilepath= "/$picture_video/".$this->session->userdata('owner_path')."/$viewer_group/" . $name;
				$file_size=$_FILES['infile']['size'];
				$pos=strrpos($targetfilepath,".");
				$pos1=strpos($targetfilepath,".",4);
				$VideoName=substr($targetfilepath,0,$pos1);
				if($file_size <= $limit_size && $OKformat==1){
					$get1=$this->picture_video->_get1(array('name'=>$targetfilepath));
					if (!$get1){
						 $result = move_uploaded_file($_FILES['infile']['tmp_name'], $targetfilepath); 
						 if ($result){
								$today = substr($now,0,10);
								$this->general_model->_add(array('table'=>'upload_infor','user_id'=>$this->session->userdata('id'),'description'=>$description,'upload_date'=>$today,'viewer_group'=>$viewer_group,'name'=>$VideoName));
								if(!isset($description)) $description ="";
								$get2=$this->general_model->_get(array('table'=>'upload_infor','user_id'=>$this->session->userdata('id'),'description'=>$description,'upload_date'=>$today,'viewer_group'=>$viewer_group,'name'=>$VideoName));
								$upload_id = $get2[0]->id;
								$this_name=$VideoName.substr($targetfilepath,$pos);
								rename($targetfilepath,$this_name);
								$this->general_model->_add(array('table'=>'picture_video','owner_path'=>$this->session->userdata('owner_path'),'picture_video'=>$picture_video,'viewer_group'=>$viewer_group,'name'=>$this_name,'upload_id'=>$upload_id));
								$message .= "<font color='darkblue' size='3'>$name</font> has been Uploaded succefully. <br>"; 
						 }
						else $message .= "<font color='red' size='3'>File upload Failed for $name. </font><br>"; 
					}
					else $message .= "<font color='red' size='3'>Duplicate upload not allowed for $name! Uploaded canceled. </font><br>";
				}
				else{
					if($OKformat==0) $message .= "<font color='red' size='3'>Invalid file format<font color='blue' size='3'> $name. </font><br>";
					else
						$message .= "<font color='red' size='3'>File size is over limit! File upload Failed for</font><font color='blue' size='3'> $name. </font><br>";
				}
			if($OKformat==1) {
				$mp4_count=0;
				$webm_count=0;
				$other_count=0;
				$get3=$this->picture_video->_get(array('owner_path'=>$this->session->userdata('owner_path'),'picture_video'=>'videos'),array('name'=>$VideoName));
				if($get3){
					foreach($get3 as $row2)
					{
						$pos=strrpos($row2->name,".")+1;
						$ext=strtolower(substr($row2->name,$pos));
						if($ext=="mp4") $mp4_count=1;
						elseif($ext=="webm") $webm_count=1;
						else $other_count=1;
					}
				}
				if($mp4_count==0) $message .= "Missing <font color='red'><b>MP4</b></font> file for this video <br>";
				elseif($webm_count==0) $message .= "Missing <font color='red'><b>WEBM</b></font> file for this video <br>";
			}
			$this->session->set_userdata('message', $message);
			echo "
				<script type=\"text/javascript\">
					window.open('UploadVideo',target='_top');
				</script>";
			exit();
		}
		$get=$this->view_permission->Get_group(array('user_id'=>$this->session->userdata('id')));
		if($get){
			foreach($get as $option) {
				$optionGroup["$option->id"] = $option->viewer_group;
			}
		}
		$picture_video = "videos";
		$limit_size=20* 1024*1000;
		$MaxSize= ($limit_size / (1024*1000));
		$this->data['picture_video']=$picture_video;
		if(isset($optionGroup)) $this->data['optionGroup']=$optionGroup;
		$this->data['MaxSize']=$MaxSize;
		if(isset($viewer_group)) $this->data['viewer_group']=$viewer_group;
		$this->load->view('setup_header');
		$this->load->view("header",$this->data);
		$this->load->view("uploadVideo_view",$this->data);
	}
}
