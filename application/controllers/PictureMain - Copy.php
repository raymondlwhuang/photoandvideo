<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PictureMain extends MY_Controller {
	public function index()
	{
		if($this->session->userdata('private') == "yes")
		{
			$profile_picture=$this->session->userdata('profile_picture');
			$user_id=$this->session->userdata('id');
			$curr_path=$this->session->userdata('owner_path');
			if(isset($_POST['comment']))
			{
				$data['upload_id2'] = $this->input->post('upload_id');
				$comment =  $this->input->post('comment');
				if(isset($_POST['ViewingID']) && $_POST['ViewingID'] != "" && $_POST['ViewingID'] != "Public" && $_POST['ViewingID'] != "Temporary") $ViewingID = $this->input->post('ViewingID');
				else $ViewingID = $this->session->userdata('id');
				$this->general_model->_add(array('table'=>'pv_comment','upload_id'=>$this->input->post('upload_id'),'user_id'=>$ViewingID,'viewer_user_id'=>$this->session->userdata('id'),'comment'=>$this->input->post('comment'),"name"=>$this->session->userdata('name')));
			} 
			elseif(isset($_POST['VideoComment']))
			{
				$data['upload_id2'] = $this->input->post('video_id');
				$this->session->set_userdata('video_id',$data['upload_id2']);
				$this->session->set_userdata('video_name',$this->input->post('video_name'));
				if(isset($_POST['ViewingID']) && $_POST['ViewingID'] != "" && $_POST['ViewingID'] != "Public" && $_POST['ViewingID'] != "Temporary") $ViewingID = $this->input->post('ViewingID');
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
		$data['upload_id']=0;
		if($this->session->userdata('id') && (isset($_POST['FriendID']) || isset($_POST['ViewingID']) || $this->session->userdata('FriendID')) )
		{
			if(isset($_POST['FriendID'])) {
				$data['FriendID'] = $this->input->post('FriendID');
				$this->session->set_userdata('FriendID',$this->input->post('FriendID'));
			}
			elseif(isset($_POST['ViewingID'])) {
				$data['FriendID'] = $this->input->post('ViewingID');
				$this->session->set_userdata('FriendID',$data['FriendID']);
			}
			else $data['FriendID']=$this->session->userdata('FriendID');
			if(isset($_POST['show_id']) && $_POST['show_id']!='')	{
				$show_id=$this->input->post('show_id');
				$this->session->set_userdata('show_id',$show_id);
			}
			
			if(!isset($show_id) && $this->session->userdata('show_id') && $data['FriendID']!="Public" && $data['FriendID']!="SharedPicture") $show_id=$this->session->userdata('show_id');
			if($data['FriendID'] == 'Public') {
				$Picked['Public'] = 'Public';
				$GetSomething="both";
				$user_id='Public';
			}
			elseif($data['FriendID'] == 'Temporary') {
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
			elseif($data['FriendID'] == 'SharedPicture') {
				$Picked['SharedPicture'] = 'SharedPicture';
				$GetSomething="both";
				$user_id='SharedPicture';
			}	
			else if($data['FriendID'] == $this->session->userdata('id')) {
					$GetSomething='';
					$user_id=$this->session->userdata('id');
					$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$this->session->userdata('owner_path')));
					if($GetSomething=='' && $data['FriendID']!="Temporary") $Picked['Public'] = 'Public';	
					elseif($GetSomething=='' && $data['FriendID']=="Temporary") $Picked['Temporary'] = 'Temporary';	
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
					$get4=$this->user->_get1(array('id'=>$data['FriendID']));
					if($get4){
						$result_path=$get4->owner_path;
						$curr_path=$get4->owner_path;
						$user_id=$get4->id;
						$profile_picture=$get4->profile_picture;
						$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$get4->owner_path));
					}
					if($GetSomething=='' && $data['FriendID']!="Temporary") $Picked['Public'] = 'Public';	
					elseif($GetSomething=='' && $data['FriendID']=="Temporary") $Picked['Temporary'] = 'Temporary';	
					else {
						$get5=$this->view_permission->_get(array('user_id'=>$data['FriendID'],'viewer_id'=>$this->session->userdata('id')));
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
		elseif($this->session->userdata('id') && !(isset($_POST['FriendID']) || isset($_POST['ViewingID']) || $this->session->userdata('FriendID'))) {
			$data['FriendID']=$this->session->userdata('id');
			$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$this->session->userdata('owner_path')));
			if($GetSomething=='' && $data['FriendID']!="Temporary") $Picked['Public'] = 'Public';
			elseif($GetSomething=='' && $data['FriendID']=="Temporary") $Picked['Public'] = 'Public';
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
		elseif(isset($_POST['FriendID']) && $_POST['FriendID']=="Temporary") {
			if(isset($_POST['show_id']) && $_POST['show_id']!='')	{
				$show_id=$_POST['show_id'];
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
			$data['FriendID']="Temporary";
			$Picked['Temporary'] = 'Temporary';
		}
		else {
			$data['FriendID']="Public";
			$Picked['Public'] = 'Public';
		}
		$data['Videocount'] = 0;
		$OwnerComcount=0;
		$PictureComcount=0;
		$data['VideoComcount']=0;

		if(isset($Picked)) {
			foreach ($Picked as $key => $value) {
				if($key =='Public') {
					$this->load->model('upload_infor');
					$get9=$this->upload_infor->get_video('Public');
					if($get9){
						foreach($get9 as $row2)
						{
							$data['video'][$data['Videocount']]=$row2->name.".";
							$data['VDupload_id'][$data['Videocount']]=$row2->id;
							$VideoGroup[$data['Videocount']]=$value;
							$data['Videocount']++;
						};
					}
				}
				elseif($key =='Temporary') {
					$get10=$this->upload_infor->get_private_video('Temporary',$TemporaryUser);
					if($get10){
						while($row2 = mysql_fetch_array($result))
						{
							$data['video'][$data['Videocount']]=$row2->name.".";
							$data['VDupload_id'][$data['Videocount']]=$row2->id;
							$VideoGroup[$data['Videocount']]=$value;
							$data['Videocount']++;
						};
					}
				}
				elseif($key =='SharedPicture') {
					$get10=$this->general_model->_get(array('table'=>'pv_share','shareto_id'=>$this->session->userdata('id'),'is_video'=>1));
					if($get10){
						foreach($get10 as $row2)
						{
							$pos = strpos($row2->pv_name, ".",3);
							$data['video'][]=substr($row2->pv_name,0,$pos).".";
							$VideoGroup[$data['Videocount']]=$value;
							$data['Videocount']++;
							$get11=$this->picture_video->_get(array('id'=>$row2->pv_id));
							if($get11)
							{
								$data['VDupload_id'][]=$get11->upload_id;
							}				
						};
					}
				}
				else {
					$get12=$this->user->_get1(array('owner_path'=>$key));
					if($get12) {
						$user_id1=$get12->id;
					}			
					$get13=$this->upload_infor->get_private_video($value,$get12->id);
					if($get13){
						foreach($get13 as $row2)
						{
							$data['video'][$data['Videocount']]=$row2->name.".";
							$data['VDupload_id'][$data['Videocount']]=$row2->id;
							$VideoGroup[$data['Videocount']]=$value;
							$data['Videocount']++;
						};
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
					if($data['FriendID']!='Public' && $data['FriendID']!='SharedPicture' && $data['FriendID']!='Temporary') {
						if($get16)
						{
							$data['pic_group'][]=$get16->viewer_group;
						}
						$comment_tmp=$row4->comment."<font color='darkblue'> on $name"."'s</font> ";
						if($row4->type==1) $comment_tmp.="<font color='blue'>video</font>($comment_date)<br/>";
						else $comment_tmp.="<font color='red'>photo</font>($comment_date)</font><br/>";
						$data['comments'][] = $comment_tmp;
						$data['pic_upload_id'][]=$row4->upload_id;
						$data['owner_id'][]=$row4->user_id;
					}
				}
			}
		}
			
		if(isset($_POST['FriendID']) || $this->session->userdata('FriendID')) {
			if(isset($_POST['FriendID'])) $data['FriendID'] = $_POST['FriendID'];
			else $data['FriendID'] = $this->session->userdata('FriendID');
			if($data['FriendID']!='Public' && $data['FriendID']!='Temporary' && $data['FriendID']!='SharedPicture') {
				$get17=$this->user->_get1(array('id'=>$data['FriendID']));
				if($get17) {
					$result_path=$get17->owner_path;
					$result_name=ucfirst(strtolower($get17->first_name))." ".ucfirst(strtolower($get17->last_name));
					$data['result_profile_picture']=$get17->profile_picture;
				}
			}
		}
		elseif($this->session->userdata('id')) {
		 $data['FriendID']=$this->session->userdata('id');
		 $result_path = $this->session->userdata('owner_path');
		 $result_name=$this->session->userdata('name');
		 $data['result_profile_picture']=$this->session->userdata('profile_picture');
		}
		elseif(isset($data['FriendID']) && $data['FriendID']=="Temporary") {
		 $data['FriendID']="Temporary";
		}
		else $data['FriendID']="Public";
			$GetSomething="";
			if(isset($result_path))	$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$result_path));
			if(isset($data['upload_id2'])){ $data['upload_id'] = $data['upload_id2'];	}
			elseif(isset($show_id)) $data['upload_id'] = $show_id;
			if(!isset($data['upload_id']) || $data['upload_id'] =="")
			{
				if($GetSomething==""){
					$get18=$this->picture_video->_get1(array('picture_video'=>'pictures','viewer_group'=>'Public'));
				}
				elseif($data['FriendID']=="Temporary") 
					$get18=$this->picture_video->_get1(array('picture_video'=>'pictures','upload_id'=>$data['upload_id'],'viewer_group'=>'Public'));
				else 
					$get18=$this->picture_video->Get_privatepv($result_path);
				if($get18){
					foreach($get18 as $row7)
					{
						$data['upload_id'] = $row7->upload_id;
					}
				}
			}
			if($this->session->userdata('id') && isset($data['upload_id']) && $data['upload_id']!="" && $data['upload_id']!=0 && $data['upload_id']!="SharedPicture")
				$resultShow=$this->picture_video->_get(array('picture_video'=>'pictures','upload_id'=>$data['upload_id']));
			elseif($data['upload_id']=="SharedPicture" && $this->session->userdata('id'))
				$resultShow=$this->general_model->_get(array('table'=>'pv_share','shareto_id'=>$this->session->userdata('id'),'is_video'=>0));
			elseif(!$this->session->userdata('id') && isset($data['FriendID']) && $data['FriendID']=="Temporary")
				$resultShow=$this->picture_video->_get(array('picture_video'=>'pictures','viewer_group'=>'Temporary','upload_id'=>$data['upload_id']));
			else $resultShow=$this->picture_video->_get(array('picture_video'=>'pictures','viewer_group'=>'Public'));

			$data['markup'] = '<div  id="slideshow">';
			$data['pictureCount']=0;
			$data['markupviewer_group']="";
			if($resultShow){
				foreach($resultShow as $row3) {
					$data['pictureCount']++;
					if($data['upload_id']=="SharedPicture") {
						$data['markup'] .= "<img class=\"pointer\" style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"$row3->pv_name\" height=\"420\">";
					}
					else {
						$data['markupviewer_group']=$row3->viewer_group;
						$data['markup'] .= "<img class=\"pointer\" style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"$row3->name\" height=\"420\">";
					}
				};
			}
			if($data['pictureCount']==0) $data['markup'] .= "<img style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"/images/blank.jpg\" height=\"420\"><img style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"/images/blank.jpg\" height=\"420\">";
			$data['markup'] .= '</div>';	
			if(isset($data['upload_id']) && $data['upload_id']!='' && $data['upload_id']!="SharedPicture")	{
				$queryComment2=$this->general_model->_get(array('table'=>'pv_comment','upload_id'=>$data['upload_id'],'type'=>0));
				if($queryComment2){
					foreach($queryComment2 as $row8)
					{	
						$curr_user=$row8->viewer_user_id;
						$queryPV2=$this->picture_video->_get1(array('upload_id'=>$row8->upload_id));
						if($queryPV2) $queryUser=$this->user->_get1(array('id'=>$queryPV2->id));
						if($queryUser)
						{		
							$Currname=$queryUser->first_name." ".$queryUser->last_name;
							$data['Currprofile_picture'][]=$queryUser->profile_picture;
						}				
						$date1= strtotime($row8->comment_date);
						$comment_date= substr(date('r',$date1),0,-5);
						$data['CurrComment'][] = "<font size=\"3\">".$row8->comment."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
					}
				}
			}
			if(isset($data['video'])) {
				foreach ($data['video'] as $key => $value) {
					$queryComment3=$this->general_model->_get(array('table'=>'pv_comment','upload_id'=>$data['VDupload_id']["$key"],'type'=>1));
					if($queryComment3){
						if($this->session->userdata('video_id')) {
							if($this->session->userdata('video_id')==$data['VDupload_id'][$key]) $data['VideoComcount']=$data['VideoComcount']+count($queryComment3);
						}
						else if($key==0) $data['VideoComcount']=$data['VideoComcount']+count($queryComment3);
						foreach($queryComment3 as $row13)
						{	
							$curr_user=$row13->viewer_user_id;
							$queryUser=$this->user->_get1(array('id'=>$row13->viewer_user_id));
							if($queryUser)
							{		
								$Currname=$queryUser->first_name." ".$queryUser->last_name;
								$data['CurrVideoprofile_picture'][]=$queryUser->profile_picture;
							}				
							$date1= strtotime($row13->comment_date);
							$comment_date= substr(date('r',$date1),0,-5);
							if($this->session->userdata('video_id')) {
								if($this->session->userdata('video_id')==$data['VDupload_id'][$key]) {
									$data['CurrVideoComment'][] = "<font size=\"3\">".$row13->comment."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
									$Videoupload_id[]=$data['VDupload_id'][$key];
								}
							}
							elseif($key==0)	{
								$data['CurrVideoComment'][] = "<font size=\"3\">".$row13->comment."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
								$Videoupload_id[]=$data['VDupload_id'][$key];
							}
						}
					}
				}
			}
		$data['Videopagenum'] = 1; 
		$data['Videopage_rows'] = 4;
		$data['Videolast'] = ceil($data['Videocount']/$data['Videopage_rows']); 
		if ($data['Videopagenum'] < 1) $data['Videopagenum'] = 1; elseif ($data['Videopagenum'] > $data['Videolast']) $data['Videopagenum'] = $data['Videolast']; 
		$data['Videofirst_row']=($data['Videopagenum'] -1)* $data['Videopage_rows'];
		$data['Videoprevious'] = $data['Videopagenum']-1;
		if($data['Videoprevious'] <= 0) $data['Videoprevious']=1;
		$data['Videonext'] = $data['Videopagenum']+1;
		if($data['Videonext'] > $data['Videocount']) $data['Videonext']=$data['Videocount'];

		$data['VideoCompagenum'] = 1; 
		$data['VideoCompage_rows'] = 2;  
		$data['VideoComlast'] = ceil($data['VideoComcount']/$data['VideoCompage_rows']); 
		if ($data['VideoCompagenum'] < 1) $data['VideoCompagenum'] = 1; elseif ($data['VideoCompagenum'] > $data['VideoComlast']) $data['VideoCompagenum'] = $data['VideoComlast']; 
		$data['VideoComfirst_row']=($data['VideoCompagenum'] -1)* $data['VideoCompage_rows'];
		$data['VideoComprevious'] = $data['VideoCompagenum']-1;
		if($data['VideoComprevious'] <= 0) $data['VideoComprevious']=1;
		$data['VideoComnext'] = $data['VideoCompagenum']+1;
		if($data['VideoComnext'] > $data['VideoComcount']) $data['VideoComnext']=$data['VideoComcount'];

		$data['Ownerpagenum'] = 1; 
		$data['Ownerpage_rows'] = 4;  
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
		$data['Picturelast'] = ceil($PictureComcount/$data['Picturepage_rows']); 
		if ($data['Picturepagenum'] < 1) $data['Picturepagenum'] = 1; elseif ($data['Picturepagenum'] > $data['Picturelast']) $data['Picturepagenum'] = $data['Picturelast']; 
		$data['Picturefirst_row']=($data['Picturepagenum'] -1)* $data['Picturepage_rows'];
		$data['Pictureprevious'] = $data['Picturepagenum']-1;
		if($data['Pictureprevious'] <= 0) $data['Pictureprevious']=1;
		$data['Picturenext'] = $data['Picturepagenum']+1;
		if($data['Picturenext'] > $PictureComcount) $data['Picturenext']=$PictureComcount;
		if($data['FriendID']!="Public" && $data['FriendID']!="SharedPicture" && $data['FriendID']!="Temporary") {
			$data['UserScreen']=$this->general_model->_get(array('table'=>'user_screen','user_id'=>$data['FriendID']));
			$data['UserBrowser']=$this->general_model->_get(array('table'=>'user_browser','user_id'=>$data['FriendID']));
			$data['UserOS']=$this->general_model->_get(array('table'=>'user_os','user_id'=>$data['FriendID']));
			$data['UserLocation']=$this->general_model->_get(array('table'=>'user_location','user_id'=>$data['FriendID']));
		}
		$this->load->view("pictureMain_view",$data);
	}
}
