<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PictureMain extends MY_Controller {
	public function index()
	{
		$this->load->model('upload_infor');
		if($this->session->userdata('private') == "yes")
		{
			$profile_picture=$this->session->userdata('profile_picture');
			$user_id=$this->session->userdata('id');
			$curr_path=$this->session->userdata('owner_path');
			if($this->input->post('comment'))
			{
				$upload_id2 = $this->input->post('upload_id');
				$comment =  $this->input->post('comment');
				if($this->input->post('ViewingID') && $this->input->post('ViewingID') != "" && $this->input->post('ViewingID') != "Public" && $this->input->post('ViewingID') != "Temporary") $ViewingID = $this->input->post('ViewingID');
				else $ViewingID = $this->session->userdata('id');
				$this->general_model->_add(array('table'=>'pv_comment','upload_id'=>$this->input->post('upload_id'),'user_id'=>$ViewingID,'viewer_user_id'=>$this->session->userdata('id'),'comment'=>$this->input->post('comment'),"name"=>$this->session->userdata('name')));
			} 
			elseif($this->input->post('VideoComment'))
			{
				$upload_id2 = $this->input->post('video_id');
				$this->session->set_userdata('video_id',$upload_id2);
				$this->session->set_userdata('video_name',$this->input->post('video_name'));
				if($this->input->post('ViewingID') && $this->input->post('ViewingID') != "" && $this->input->post('ViewingID') != "Public" && $this->input->post('ViewingID') != "Temporary") $ViewingID = $this->input->post('ViewingID');
				else $ViewingID = $this->session->userdata('id');
				$comment =  $this->input->post('VideoComment');
				$get=$this->picture_video->_get1(array('picture_video'=>'videos','upload_id'=>$this->input->post('video_id')));
				if($get)
				{
					$PV_id=$get->id;
					if($comment!="") {
						$this->general_model->_add(array('table'=>'pv_comment','upload_id'=>$this->input->post('video_id'),'viewer_user_id'=>$this->session->userdata('id'),'user_id'=>$ViewingID,'name'=>$this->session->userdata('name'),'comment'=>$this->input->post('VideoComment'),'PV_id'=>$get->id,'type'=>1));
					}		
				};	
			}
		}
		$upload_id=0;
		if($this->session->userdata('id') && ($this->input->get('FriendID') || $this->input->post('ViewingID') || $this->session->userdata('FriendID')) )
		{
			if($this->input->get('FriendID')) {
				$FriendID = $this->input->get('FriendID');
				$this->session->set_userdata('FriendID',$this->input->get('FriendID'));
			}
			elseif($this->input->post('ViewingID')) {
				$FriendID = $this->input->post('ViewingID');
				$this->session->set_userdata('FriendID',$FriendID);
			}
			else $FriendID=$this->session->userdata('FriendID');
			if($this->input->get('show_id') && $this->input->get('show_id')!='')	{
				$show_id=$this->input->get('show_id');
				$this->session->set_userdata('show_id',$show_id);
			}
			
			if(!isset($show_id) && $this->session->userdata('show_id') && $FriendID!="Public" && $FriendID!="SharedPicture") $show_id=$this->session->userdata('show_id');
			if($FriendID == 'Public') {
				$Picked['Public'] = 'Public';
				$GetSomething="both";
				$user_id='Public';
			}
			elseif($FriendID == 'Temporary') {
				$get1=$this->picture_video->_get1(array('picture_video'=>'pictures','viewer_group'=>'Temporary','upload_id'=>$show_id));
				if($get1) {
					$TemporaryPath=$get1->owner_path;
					$get2=$this->user->_get1(array('owner_path'=>$get1->owner_path));
					if($get2){
						$TemporaryUser=$get2->id;
					}
				}
				$Picked['Temporary'] = 'Temporary';
				$GetSomething="both";
				$user_id='Temporary';
			}	
			elseif($FriendID == 'SharedPicture') {
				$Picked['SharedPicture'] = 'SharedPicture';
				$GetSomething="both";
				$user_id='SharedPicture';
			}	
			else if($FriendID == $this->session->userdata('id')) {
					$GetSomething='';
					$user_id=$this->session->userdata('id');
					$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$this->session->userdata('owner_path')));
					if($GetSomething=='' && $FriendID!="Temporary") $Picked['Public'] = 'Public';	
					elseif($GetSomething=='' && $FriendID=="Temporary") $Picked['Temporary'] = 'Temporary';	
					else {
						$get3=$this->view_permission->_get(array('owner_email'=>$this->session->userdata('email_address')));
						if($get3){
							foreach($get3 as $row)
							{
							 $viewer_group = $row->viewer_group;
							 $Picked["$row->owner_path"] = $row->viewer_group;
							}
						}
					}
			}	
			else {
					$GetSomething='';
					$get4=$this->user->_get1(array('id'=>$FriendID));
					if($get4){
						$result_path=$get4->owner_path;
						$curr_path=$get4->owner_path;
						$user_id=$get4->id;
						$profile_picture=$get4->profile_picture;
						$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$get4->owner_path));
					}
					if($GetSomething=='' && $FriendID!="Temporary") $Picked['Public'] = 'Public';	
					elseif($GetSomething=='' && $FriendID=="Temporary") $Picked['Temporary'] = 'Temporary';	
					else {
						$get5=$this->view_permission->_get(array('user_id'=>$FriendID,'viewer_id'=>$this->session->userdata('id')));
						if($get5){
							foreach($get5 as $row)
							{
							 $viewer_group = $row->viewer_group;
							 $owner_path = $row->owner_path;
							 $Picked["$row->owner_path"] = $row->viewer_group;
							}
						}
					}			
				}
		}
		elseif($this->session->userdata('id') && !($this->input->get('FriendID') || $this->input->post('ViewingID') || $this->session->userdata('FriendID'))) {
			$FriendID=$this->session->userdata('id');
			$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$this->session->userdata('owner_path')));
			if($GetSomething=='' && $FriendID!="Temporary") $Picked['Public'] = 'Public';
			elseif($GetSomething=='' && $FriendID=="Temporary") $Picked['Public'] = 'Public';
			else {
				$get6=$this->view_permission->_get(array('owner_email'=>$this->session->userdata('email_address')));
				if($get6){
					foreach($get6 as $row)
					{
					 $viewer_group = $row->viewer_group;
					 $this->session->set_userdata('owner_path',$row->owner_path);
					 $Picked["$row->owner_path"] = $row->viewer_group;
					}
				}
			}
		}
		elseif($this->input->get('FriendID')=="Temporary") {
			if($this->input->get('show_id') && $this->input->get('show_id')!='')	{
				$show_id=$this->input->get('show_id');
				$this->session->set_userdata('show_id',$show_id);
			}
			$get7=$this->picture_video->_get1(array('picture_video'=>'pictures','viewer_group'=>'Temporary','upload_id'=>$show_id));
			if($get7) {
				$TemporaryPath=$get7->owner_path;
				$get8=$this->user->_get1(array('owner_path'=>$get7->owner_path));
				if($get8) {
					$TemporaryUser=$get8->id;
				}
			}
			$FriendID="Temporary";
			$Picked['Temporary'] = 'Temporary';
		}
		else {
			$FriendID="Public";
			$Picked['Public'] = 'Public';
		}
		$Videocount = 0;
		$OwnerComcount=0;
		$PictureComcount=0;
		$VideoComcount=0;
		if(isset($Picked)) {
			foreach ($Picked as $key => $value) {
				if($key =='Public') {
					$get9=$this->upload_infor->get_video('Public');
					if($get9){
						foreach($get9 as $row2)
						{
							$video[$Videocount]=$row2->name.".";
							$VDupload_id[$Videocount]=$row2->id;
							$VideoGroup[$Videocount]=$value;
							$Videocount++;
						};
					}
				}
				elseif($key =='Temporary' && isset($TemporaryUser)) {
					$get10=$this->upload_infor->get_private_video('Temporary',$TemporaryUser);
					if($get10){
						while($row2 = mysql_fetch_array($result))
						{
							$video[$Videocount]=$row2->name.".";
							$VDupload_id[$Videocount]=$row2->id;
							$VideoGroup[$Videocount]=$value;
							$Videocount++;
						};
					}
				}
				elseif($key =='SharedPicture') {
					$get10=$this->general_model->_get(array('table'=>'pv_share','shareto_id'=>$this->session->userdata('id'),'is_video'=>1));
					if($get10){
						foreach($get10 as $row2)
						{
							$pos = strpos($row2->pv_name, ".",3);
							$video[]=substr($row2->pv_name,0,$pos).".";
							$VideoGroup[$Videocount]=$value;
							$Videocount++;
							$get11=$this->picture_video->_get(array('id'=>$row2->pv_id));
							if($get11)
							{
								$VDupload_id[]=$get11->upload_id;
							}				
						};
					}
				}
				else {
					$get12=$this->user->_get1(array('owner_path'=>$key));
					if($get12) {
						$user_id1=$get12->id;
						$get13=$this->upload_infor->get_private_video($value,$get12->id);
						if($get13){
							foreach($get13 as $row2)
							{
								$video[$Videocount]=$row2->name.".";
								$VDupload_id[$Videocount]=$row2->id;
								$VideoGroup[$Videocount]=$value;
								$Videocount++;
							};
						}
					}			
				}
			}
		}
		if(isset($user_id) && $user_id!='Public' && $user_id!='Temporary' && $user_id!='SharedPicture')	{
			$name="";
			$get14=$this->general_model->_get(array('table'=>'pv_comment','viewer_user_id'=>$user_id));
			if($get14){
				foreach($get14 as $row4)
				{
					$get15=$this->user->_get1(array('id'=>$row4->user_id));
					if($get15)
					{		
						$name=$get15->first_name." ".$get15->last_name;
						$profile_picture=$get15->profile_picture;
					}
					$date1= strtotime($row4->comment_date);
					$comment_date= substr(date('r',$date1),0,-15);
					$get16=$this->picture_video->_get1(array('upload_id'=>$row4->upload_id));					
					if($FriendID!='Public' && $FriendID!='SharedPicture' && $FriendID!='Temporary') {
						if($get16)
						{
							$pic_group[]=$get16->viewer_group;
						}
						$comment_tmp=$row4->comment."<font color='darkblue'> on $name"."'s</font> ";
						if($row4->type==1) $comment_tmp.="<font color='blue'>video</font>($comment_date)<br/>";
						else $comment_tmp.="<font color='red'>photo</font>($comment_date)</font><br/>";
						$comments[] = $comment_tmp;
						$pic_upload_id[]=$row4->upload_id;
						$owner_id[]=$row4->user_id;
					}
				}
			}
		}
			
		if($this->input->get('FriendID') || $this->session->userdata('FriendID')) {
			if($this->input->get('FriendID')) {
				$FriendID = $this->input->get('FriendID');
				$show_id = $this->input->get('show_id');
			}
			else $FriendID = $this->session->userdata('FriendID');
			if($FriendID!='Public' && $FriendID!='Temporary' && $FriendID!='SharedPicture') {
				$get17=$this->user->_get1(array('id'=>$FriendID));
				if($get17) {
					$result_path=$get17->owner_path;
					$result_name=ucfirst(strtolower($get17->first_name))." ".ucfirst(strtolower($get17->last_name));
					$result_profile_picture=$get17->profile_picture;
				}
			}
		}
		elseif($this->session->userdata('id')) {
		 $FriendID=$this->session->userdata('id');
		 $result_path = $this->session->userdata('owner_path');
		 $result_name=$this->session->userdata('name');
		 $result_profile_picture=$this->session->userdata('profile_picture');
		}
		else $FriendID="Public";
			$GetSomething="";
			if(isset($result_path))	$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$result_path));
			if(isset($upload_id2)){ $upload_id = $upload_id2;	}
			elseif(isset($show_id)) $upload_id = $show_id;
			if(!isset($upload_id) || $upload_id =="")
			{
				if($GetSomething==""){
					$get18=$this->picture_video->_get1(array('picture_video'=>'pictures','viewer_group'=>'Public'));
				}
				elseif($FriendID=="Temporary") 
					$get18=$this->picture_video->_get1(array('picture_video'=>'pictures','upload_id'=>$upload_id,'viewer_group'=>'Public'));
				else 
					$get18=$this->picture_video->Get_privatepv($result_path);
				if($get18){
					$upload_id = $get18->upload_id;
				}
			}
			if($this->session->userdata('id') && isset($upload_id) && $upload_id!="" && $upload_id!=0 && $upload_id!="SharedPicture")
				$resultShow=$this->picture_video->_get(array('picture_video'=>'pictures','upload_id'=>$upload_id));
			elseif($upload_id=="SharedPicture" && $this->session->userdata('id'))
				$resultShow=$this->general_model->_get(array('table'=>'pv_share','shareto_id'=>$this->session->userdata('id'),'is_video'=>0));
			elseif(!$this->session->userdata('id') && isset($FriendID) && $FriendID=="Temporary")
				$resultShow=$this->picture_video->_get(array('picture_video'=>'pictures','viewer_group'=>'Temporary','upload_id'=>$upload_id));
			else {
				if(isset($upload_id))
					$resultShow=$this->picture_video->_get(array('picture_video'=>'pictures','viewer_group'=>'Public','upload_id'=>$upload_id));
				else
					$resultShow=$this->picture_video->_get(array('picture_video'=>'pictures','viewer_group'=>'Public'));
			}
			$markup = '<div  id="slideshow">';
			$pictureCount=0;
			$markupviewer_group="";
			if($resultShow){
				foreach($resultShow as $row3) {
					$pictureCount++;
					if($upload_id=="SharedPicture") {
						$markup .= "<img class=\"markup\" src=\"$row3->pv_name\" height=\"420\">";
					}
					else {
						$markupviewer_group=$row3->viewer_group;
						$markup .= "<img class=\"markup\" src=\"$row3->name\" height=\"420\">";
					}
				};
			}
			if($pictureCount==0) $markup .= "<img class=\"markup\" src=\"/images/blank.jpg\" height=\"420\">";
			$markup .= '</div>';	
			if(isset($upload_id) && $upload_id!='' && $upload_id!="SharedPicture")	{
				$queryComment2=$this->general_model->_get(array('table'=>'pv_comment','upload_id'=>$upload_id,'type'=>0));
				if($queryComment2){
					foreach($queryComment2 as $row8)
					{	
						$curr_user=$row8->viewer_user_id;
						$queryPV2=$this->picture_video->_get1(array('upload_id'=>$row8->upload_id));
						if($queryPV2) $queryUser=$this->user->_get1(array('id'=>$curr_user));
						if($queryUser)
						{		
							$Currname=$queryUser->first_name." ".$queryUser->last_name;
							$Currprofile_picture[]=$queryUser->profile_picture;
							$date1= strtotime($row8->comment_date);
							$comment_date= substr(date('r',$date1),0,-5);
							$CurrComment[] = "<font size=\"3\">".$row8->comment."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
						}				
					}
				}
			}
			if(isset($video)) {
				foreach ($video as $key => $value) {
					$queryComment3=$this->general_model->_get(array('table'=>'pv_comment','upload_id'=>$VDupload_id["$key"],'type'=>1));
					if($queryComment3){
						if($this->session->userdata('video_id')) {
							if($this->session->userdata('video_id')==$VDupload_id[$key]) $VideoComcount=$VideoComcount+count($queryComment3);
						}
						else if($key==0) $VideoComcount=$VideoComcount+count($queryComment3);
						foreach($queryComment3 as $row13)
						{	
							$curr_user=$row13->viewer_user_id;
							$queryUser=$this->user->_get1(array('id'=>$row13->viewer_user_id));
							if($queryUser)
							{		
								$Currname=$queryUser->first_name." ".$queryUser->last_name;
								$CurrVideoprofile_picture[]=$queryUser->profile_picture;
							}				
							$date1= strtotime($row13->comment_date);
							$comment_date= substr(date('r',$date1),0,-5);
							if($this->session->userdata('video_id')) {
								if($this->session->userdata('video_id')==$VDupload_id[$key]) {
									$CurrVideoComment[] = "<font size=\"3\">".$row13->comment."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
									$Videoupload_id[]=$VDupload_id[$key];
								}
							}
							elseif($key==0)	{
								$CurrVideoComment[] = "<font size=\"3\">".$row13->comment."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
								$Videoupload_id[]=$VDupload_id[$key];
							}
						}
					}
				}
			}
		if($VideoComcount==0)  $height="0px";
		elseif($VideoComcount==1)  $height="39px";
		else $height="78px";			
		$data['height'] = $height; 
		$data['listcount'] = 0; 
		$data['Videopage_rows'] = 1;
		$data['Videocount'] = $Videocount;
		$data['Videolast'] = ceil($Videocount/$data['Videopage_rows'])-1; 
		$data['Videonext'] = $data['Videopagenum']+1;
		if($data['Videonext'] > $Videocount) $data['Videonext']=$Videocount;

		$data['VideoCompagenum'] = 1; 
		$data['VideoCompage_rows'] = 2;  
		$data['VideoComcount'] = $VideoComcount;  
		$data['VideoComlast'] = ceil($VideoComcount/$data['VideoCompage_rows']); 
		if ($data['VideoCompagenum'] < 1) $data['VideoCompagenum'] = 1; elseif ($data['VideoCompagenum'] > $data['VideoComlast']) $data['VideoCompagenum'] = $data['VideoComlast']; 
		$data['VideoComfirst_row']=($data['VideoCompagenum'] -1)* $data['VideoCompage_rows'];
		$data['VideoComprevious'] = $data['VideoCompagenum']-1;
		if($data['VideoComprevious'] <= 0) $data['VideoComprevious']=1;
		$data['VideoComnext'] = $data['VideoCompagenum']+1;
		if($data['VideoComnext'] > $VideoComcount) $data['VideoComnext']=$VideoComcount;

		$data['Ownerpagenum'] = 1; 
		$data['Ownerpage_rows'] = 4;  
		$data['OwnerComcount'] = $OwnerComcount;  
		$data['Ownerlast'] = ceil($OwnerComcount/$data['Ownerpage_rows']); 
		if ($data['Ownerpagenum'] < 1) $data['Ownerpagenum'] = 1; elseif ($data['Ownerpagenum'] > $data['Ownerlast']) $data['Ownerpagenum'] = $data['Ownerlast']; 
		$data['Ownerfirst_row']=($data['Ownerpagenum'] -1)* $data['Ownerpage_rows'];
		$data['Ownerprevious'] = $data['Ownerpagenum']-1;
		if($data['Ownerprevious'] <= 0) $data['Ownerprevious']=1;
		$data['Ownernext'] = $data['Ownerpagenum']+1;
		if($data['Ownernext'] > $OwnerComcount) $data['Ownernext']=$OwnerComcount;
		$data['Ownercount']=0;
			
		$data['Picturepagenum'] = 1; 
		$data['Picturepage_rows'] = 2;  
		$data['PictureComcount'] = $PictureComcount; 
		$data['Picturelast'] = ceil($PictureComcount/$data['Picturepage_rows']); 
		if ($data['Picturepagenum'] < 1) $data['Picturepagenum'] = 1; elseif ($data['Picturepagenum'] > $data['Picturelast']) $data['Picturepagenum'] = $data['Picturelast']; 
		$data['Picturefirst_row']=($data['Picturepagenum'] -1)* $data['Picturepage_rows'];
		$data['Pictureprevious'] = $data['Picturepagenum']-1;
		if($data['Pictureprevious'] <= 0) $data['Pictureprevious']=1;
		$data['Picturenext'] = $data['Picturepagenum']+1;
		if($data['Picturenext'] > $PictureComcount) $data['Picturenext']=$PictureComcount;
		if(isset($video)) $data['video']=$video;
		if(isset($comments)) $data['comments']=$comments;
		if(isset($VDupload_id)) $data['VDupload_id']=$VDupload_id;
		$data['FriendID']=$FriendID;
		if(isset($pic_group)) $data['pic_group']=$pic_group;
		if(isset($upload_id2)) $data['upload_id2']=$upload_id2;
		if(isset($upload_id)) $data['upload_id']=$upload_id;
		if(isset($pic_upload_id)) $data['pic_upload_id']=$pic_upload_id;
		if(isset($owner_id)) $data['owner_id']=$owner_id;
		if(isset($markup)) $data['markup']=$markup;
		if(isset($pictureCount)) $data['pictureCount']=$pictureCount;
		if(isset($markupviewer_group)) $data['markupviewer_group']=$markupviewer_group;
		if(isset($Currprofile_picture)) $data['Currprofile_picture']=$Currprofile_picture;
		if(isset($CurrComment)) $data['CurrComment']=$CurrComment;
		if(isset($CurrVideoprofile_picture)) $data['CurrVideoprofile_picture']=$CurrVideoprofile_picture;
		if(isset($CurrVideoComment)) $data['CurrVideoComment']=$CurrVideoComment;
		if(isset($result_profile_picture)) $data['result_profile_picture']=$result_profile_picture;
		if($FriendID!="Public" && $FriendID!="SharedPicture" && $FriendID!="Temporary") {
			$data['UserScreen']=$this->general_model->_get(array('table'=>'user_screen','user_id'=>$FriendID,'limit'=>1));
			$data['UserBrowser']=$this->general_model->_get(array('table'=>'user_browser','user_id'=>$FriendID,'limit'=>1));
			$data['UserOS']=$this->general_model->_get(array('table'=>'user_os','user_id'=>$FriendID,'limit'=>1));
			$data['UserLocation']=$this->general_model->_get(array('table'=>'user_location','user_id'=>$FriendID,'limit'=>1));
		}
		$this->load->view("pictureMain_view",$data);
	}
}
