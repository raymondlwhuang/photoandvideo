<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PictureMain extends MY_Controller {
	public function index()
	{
		if(isset($this->session->userdata('private')))
		{
			$data['profile_picture']=$this->session->userdata('profile_picture');
			$user_id=$this->session->userdata('id');
			$curr_path=$this->session->userdata('owner_path');
			if(isset($_POST['comment']))
			{
				$upload_id2 = (int)$this->input->post('upload_id');
				$data['comment'] =  $this->input->post('comment');
				if(isset($_POST['ViewingID']) && $this->input->post('ViewingID') != "" && $this->input->post('ViewingID') != "Public" && $this->input->post('ViewingID') != "Temporary") $data['ViewingID'] = $this->input->post('ViewingID');
				else $data['ViewingID'] = $this->session->userdata('id');
				$options=array(
					'table'=>'pv_comment',
					'upload_id'=>$upload_id2,
					'user_id'=>$data['ViewingID'],
					'viewer_user_id'=>$this->session->userdata('id'),
					'comment'=>$this->input->post('comment'),
					'type'=>0
				);
				$this->general_model->_add($options);				
			} 
			elseif(isset($_POST['VideoComment']))
			{
				$upload_id2 = $this->input->post('video_id');
				$this->session->userdata('video_id')=$this->input->post('video_id');
				$this->session->userdata('video_name')=$this->input->post('video_name');
				if(isset($_POST['ViewingID']) && $this->input->post('ViewingID') != "" && $this->input->post('ViewingID') != "Public" && $this->input->post('ViewingID') != "Temporary") $data['ViewingID'] = $this->input->post('ViewingID');
				else $data['ViewingID'] = $this->session->userdata('id');
				$data['comment'] = $this->input->post('VideoComment');
				$options=array(
					'table'=>'picture_video',
					'picture_video'=>'videos',
					'upload_id'=>$this->input->post('video_id'),
					'limit'=> 1
				);
				$get=$this->general_model->_get($options);				
				$data['PV_id']=$get[0]->id;
				if($this->input->post('VideoComment')!="") {
					$options=array(
						'table'=>'pv_comment',
						'PV_id'=>$get[0]->id,
						'upload_id'=>$upload_id2,
						'user_id'=>$data['ViewingID'],
						'viewer_user_id'=>$this->session->userdata('id'),
						'comment'=>$this->input->post('VideoComment'),
						'type'=>1
					);
					$this->general_model->_add($options);
				}		
			}
		}
		$data['upload_id']=0;
		if($this->session->userdata('id') && (isset($_POST['FriendID']) || isset($_POST['ViewingID']) || $this->session->userdata('FriendID')) )
		{
			if(isset($_POST['FriendID'])) {
				$FriendID = $_POST['FriendID'];
				$this->session->userdata('FriendID')=$FriendID;
			}
			elseif(isset($_POST['ViewingID'])) {
				$FriendID = $this->input->post('ViewingID');
				$this->session->userdata('FriendID')=$FriendID;
			}
			else $FriendID=$this->session->userdata('FriendID');
			if(isset($_POST['show_id']) && $_POST['show_id']!='')	{
				$data['show_id']=$_POST['show_id'];
				$this->session->userdata('show_id')=$data['show_id'];
			}
			
			if(!isset($data['show_id']) && $this->session->userdata('show_id') && $FriendID!="Public" && $FriendID!="SharedPicture") $data['show_id']=$this->session->userdata('show_id');
			if($FriendID == 'Public') {
				$Picked['Public'] = 'Public';
				$GetSomething="both";
				$user_id='Public';
			}
			elseif($FriendID == 'Temporary') {
				$options=array(
					'table'=>'picture_video',
					'picture_video'=>'pictures',
					'viewer_group'=>'Temporary',
					'upload_id'=>$data['show_id'],
					'sortBy'=>'upload_id',
					'sortDirection'=>'desc',
					'limit'=>1
				);
				$get=$this->general_model->_get($options);
				$options=array(
					'table'=>'user',
					'owner_path'=>$get[0]->owner_path,
					'limit'=>1
				);
				$get1=$this->general_model->_get($options);				
				$data['TemporaryUser']=$get1[0]->id;
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
					$user_id=$this->session->userdata('id');
					$GetSomething=$this->picture_video->GetPV($this->session->userdata('owner_path'));
					if($GetSomething=='' && $FriendID!="Temporary") $Picked['Public'] = 'Public';	
					else {
						$Picked[$this->session->userdata('owner_path')] = "";
						/*
						$queryPermission="SELECT * FROM view_permission where owner_email = '$GV_email_address' group by viewer_group";  // query string stored in a variable
						$resultPermission=mysql_query($queryPermission);          // query executed 
						echo mysql_error();              // if any error is there that will be printed to the screen 
						while($row = mysql_fetch_array($resultPermission))
						{
						 $viewer_group = $row['viewer_group'];
						 $Picked[$GV_owner_path] = $viewer_group;
						}					
					*/
					
					}
				}	
			else {
					$GetSomething=$this->picture_video->GetPV($this->session->userdata('owner_path'));
					$options=array(
						'table'=>'user',
						'id'=>$FriendID,
						'limit'=>1
					);
					$getowner1=$this->general_model->_get($options);
					if($getowner1){
						foreach($owneresult1 as $row) {
							$user_id=$row->id;
							$curr_path=$row->owner_path;
							$data['profile_picture']=$row->profile_picture;
						}
					}
					if($GetSomething=='' && $FriendID!="Temporary") $Picked['Public'] = 'Public';	
					elseif($GetSomething=='' && $FriendID=="Temporary") $Picked['Temporary'] = 'Temporary';	
					else {
						$options=array(
							'table'=>'view_permission',
							'user_id'=>$FriendID,
							'viewer_id'=>$this->session->userdata('id'),
							'sortBy'=>'owner_path'
						);
						$get=$this->general_model->_get($options);
						if($get){
							foreach($get as $row)
							{
							 $Picked[$row->owner_path] = $row->viewer_group;
							}
						}
					}			
				}
		}
		elseif($this->session->userdata('id') && !(isset($_POST['FriendID']) || isset($_POST['ViewingID']) || $this->session->userdata('FriendID'))) {
			$FriendID=$this->session->userdata('id');
			$GetSomething=$this->picture_video->GetPV($this->session->userdata('owner_path'));
			if($GetSomething=='' && $FriendID!="Temporary") $Picked['Public'] = 'Public';
			elseif($GetSomething=='' && $FriendID=="Temporary") $Picked['Public'] = 'Public';
			else {
				$Picked[$this->session->userdata('owner_path')] = "";
			/*
				$queryPermission="SELECT * FROM view_permission where owner_email = '$GV_email_address' group by viewer_group";  // query string stored in a variable
				$resultPermission=mysql_query($queryPermission);          // query executed 
				echo mysql_error();              // if any error is there that will be printed to the screen 
				while($row = mysql_fetch_array($resultPermission))
				{
				 $data['viewer_group'] = $row['viewer_group'];
				 $this->session->userdata('owner_path') = $row['owner_path'];
				 $Picked[$this->session->userdata('owner_path')] = $data['viewer_group'];
				}
				*/
			}
		}
		elseif(isset($_POST['FriendID']) && $_POST['FriendID']=="Temporary") {
			if(isset($_POST['show_id']) && $_POST['show_id']!='')	{
				$data['show_id']=$this->input->post('show_id');
				$this->session->userdata('show_id')=$this->input->post('show_id');
			}
			
			$options=array(
				'table'=>'picture_video',
				'picture_video'=>'pictures',
				'viewer_group'=>'Temporary',
				'upload_id'=>$this->input->post('show_id'),
				'sortBy'=>'upload_id',
				'sortDirection'=>'desc',
				'limit'=>1
			);
			$get=$this->general_model->_get($options);
			$options=array(
				'table'=>'user',
				'owner_path'=>$get[0]->owner_path,
				'limit'=>1
			);
			$get1=$this->general_model->_get($options);				
			$data['TemporaryUser']=$get1[0]->id;			
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
					$this->load->model('upload_infor');
					$get=$this->upload_infor->get_video('Public');
					if($get){
						foreach($get as $row) {
							$video[$Videocount]=$row->name.".";
							$VDupload_id[$Videocount]=$row->id;
							$VideoGroup[$Videocount]=$value;
							$Videocount++;
						}
					}
				}
				elseif($key =='Temporary') {
					$this->load->model('upload_infor');
					$get=$this->upload_infor->get_video('Temporary');
					if($get){
						foreach($get as $row) {
							$video[$Videocount]=$row->name.".";
							$VDupload_id[$Videocount]=$row->id;
							$VideoGroup[$Videocount]=$value;
							$Videocount++;
						}
					}					
				}
				elseif($key =='SharedPicture') {
					$options=array(
						'table'=>'pv_share',
						'shareto_id'=> $this->session->userdata('id'),
						'is_video'=>1
					);
					$get=$this->general_model->_get($options);
					if($get){
						foreach($get as $row)
						{
							$pos = strpos($row->pv_name, ".",3);
							$video[]=substr($row->pv_name,0,$pos).".";
							$VideoGroup[$Videocount]=$value;
							$Videocount++;
							$options=array(
								'table'=>'picture_video',
								'id'=>$row->pv_id,
								'limit'=> 1
							);
							$get1=$this->general_model->_get($options);						
							$VDupload_id[]=$get1[0]->upload_id;
						};
					}
				}
				else {
					$options=array(
						'table'=>'user',
						'owner_path'=>$key,
						'limit'=> 1
					);
					$get=$this->general_model->_get($options);						
					$user_id1=$get[0]->id;
					$this->load->model('upload_infor');
					$get1=$this->upload_infor->get_private_video($value,$user_id1);
					if($get1){
						foreach($get1 as $row)
						{
							$video[$Videocount]=$row->name.".";
							$VDupload_id[$Videocount]=$row->id;
							$VideoGroup[$Videocount]=$value;
							$Videocount++;
						};
					}
				}
			}
		}
		if(isset($user_id) && $user_id!='Public' && $user_id!='Temporary' && $user_id!='SharedPicture')	{
			$name="";
			$options=array(
				'table'=>'pv_comment',
				'viewer_user_id'=>$user_id,
				'sortBy'=>'id',
				'sortDirection'=>'desc'
			);
			$get=$this->general_model->_get($options);
			if($get){
				foreach($get as $row4)
				{
					$options=array(
						'table'=>'user',
						'id'=>$row4->user_id,
						'limit'=>1
					);
					$get1=$this->general_model->_get($options);
					$name=$get1[0]->first_name." ".$get1[0]->last_name;
					$data['profile_picture']=$get1[0]->profile_picture;
					$options=array(
						'table'=>'picture_video',
						'upload_id'=>$row4->upload_id,
						'limit'=>1
					);
					$get2=$this->general_model->_get($options);
					$date1= strtotime($row4->comment_date);
					$comment_date= substr(date('r',$date1),0,-15);
					if($FriendID!='Public' && $FriendID!='SharedPicture' && $FriendID!='Temporary') {
						if($get2){
							foreach($get2 as $row5)
							{
								$pic_group[]=$row5->viewer_group;
							}
						}
						$comment_tmp=$row4->comment."<font color='darkblue'> on $name"."'s</font> ";
						if($row4['type']==1) $comment_tmp.="<font color='blue'>video</font>($comment_date)<br/>";
						else $comment_tmp.="<font color='red'>photo</font>($comment_date)</font><br/>";
						$comments[] = $comment_tmp;
						$pic_upload_id[]=$row4['upload_id'];
						$owner_id[]=$row4['user_id'];
					}
					$data['comments']=$comments;
				}
			}
		}
			
		if(isset($_POST['FriendID']) || $this->session->userdata('FriendID')) {
			if(isset($_POST['FriendID'])) $FriendID = $_POST['FriendID'];
			else $FriendID = $this->session->userdata('FriendID');
			if($FriendID!='Public' && $FriendID!='Temporary' && $FriendID!='SharedPicture') {
				$options=array(
					'table'=>'user',
					'id'=>$FriendID,
					'limit'=>1
				);
				$get=$this->general_model->_get($options);
				$result_path=$get[0]->owner_path;
				$result_name=ucfirst(strtolower($get[0]->first_name))." ".ucfirst(strtolower($get[0]->last_name));
				$result_profile_picture=$get[0]->profile_picture;
			}
		}
		elseif($this->session->userdata('id')) {
		 $FriendID=$this->session->userdata('id');
		 $result_path = $this->session->userdata('owner_path');
		 $result_name=$this->session->userdata('name');
		 $result_profile_picture=$this->session->userdata('profile_picture');
		}
		elseif(isset($FriendID) && $FriendID=="Temporary") {
		 $FriendID="Temporary";
		}
		else $FriendID="Public";
		$GetSomething="";
		if(isset($result_path)) $GetSomething=$this->picture_video->GetPV($result_path);
		if(isset($upload_id2)){ $data['upload_id'] = $upload_id2;	}
		elseif(isset($data['show_id'])) $data['upload_id'] = $data['show_id'];
		if(!isset($data['upload_id']) || $data['upload_id'] =="")
		{
			if($GetSomething==""){
				$options=array(
					'table'=>'picture_video',
					'picture_video'=>'pictures',
					'viewer_group'=>'Public',
					'sortBy'=>'upload_id',
					'sortDirection'=>'desc',
					'limit'=>1
				);
				$get=$this->general_model->_get($options);
			}
			elseif($FriendID=="Temporary"){
				$options=array(
					'table'=>'picture_video',
					'upload_id'=>$data['upload_id'],
					'picture_video'=>'pictures',
					'sortBy'=>'upload_id',
					'sortDirection'=>'desc',
					'limit'=>1
				);
				$get=$this->general_model->_get($options);
			}
			else {
				$get=$this->picture_video->Get_privatepv($result_path);
			}
			if($get) $data['upload_id'] = $get[0]->upload_id;
		}
		if($this->session->userdata('id') && isset($data['upload_id']) && $data['upload_id']!="" && $data['upload_id']!=0 && $data['upload_id']!="SharedPicture"){
			$options=array(
				'table'=>'picture_video',
				'upload_id'=>$data['upload_id'],
				'picture_video'=>'pictures',
				'sortBy'=>'upload_id',
				'sortDirection'=>'desc',
				'limit'=>1
			);
			$get=$this->general_model->_get($options);
		}
		elseif($data['upload_id']=="SharedPicture") {
			$options=array(
				'table'=>'pv_share',
				'shareto_id'=>$this->session->userdata('id'),
				'is_video'=>0,
				'sortBy'=>'id',
				'sortDirection'=>'desc'
			);
			$get=$this->general_model->_get($options);
		}
		elseif(!$this->session->userdata('id') && isset($FriendID) && $FriendID=="Temporary"){
			$options=array(
				'table'=>'picture_video',
				'upload_id'=>$data['upload_id'],
				'viewer_group'=>'Temporary',
				'picture_video'=>'pictures',
				'sortBy'=>'upload_id',
				'sortDirection'=>'desc'
			);
			$get=$this->general_model->_get($options);
		}
		else {
			$options=array(
				'table'=>'picture_video',
				'viewer_group'=>'Public',
				'picture_video'=>'pictures',
				'sortBy'=>'upload_id',
				'sortDirection'=>'desc'
			);
			$get=$this->general_model->_get($options);
		}

		$data['markup'] = '<div  id="slideshow">';
		$pictureCount=0;
		$data['markupviewer_group']="";
		if($get){
			foreach($get as $row3) {
				$pictureCount++;
				if($data['upload_id']=="SharedPicture") {
					$data['markup'] .= "<img class=\"pointer\" style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"".$row3->pv_name."\" height=\"420\">";
				}
				else {
					$data['markupviewer_group']=$row3->viewer_group;
					$data['markup'] .= "<img class=\"pointer\" style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"".$row3->name."\" height=\"420\">";
				}
			};
		}
		if($pictureCount==0) $data['markup'] .= "<img style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"../images/blank.jpg\" height=\"420\"><img style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"../images/blank.jpg\" height=\"420\">";
		//elseif($pictureCount==1) $data['markup'] .= "<img style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"../images/blank.jpg\" height=\"420\">";
		$data['markup'] .= '</div>';	
		if(isset($data['upload_id']) && $data['upload_id']!='' && $data['upload_id']!="SharedPicture")	{
			$options=array(
				'table'=>'pv_comment',
				'upload_id'=>$data['upload_id'],
				'type'=>0,
				'sortBy'=>'id',
				'sortDirection'=>'desc'
			);
			$get=$this->general_model->_get($options);
			if($get){
			    $PictureComcount=count($get);
				foreach($get as $row8)
				{	
					$curr_user=$row8->viewer_user_id;
					$options=array(
						'table'=>'picture_video',
						'upload_id'=>$row8->upload_id,
						'limit'=>1
					);
					$get1=$this->general_model->_get($options);
					$options=array(
						'table'=>'user',
						'id'=>$curr_user,
						'limit'=>1
					);
					$get2=$this->general_model->_get($options);
					$Currname=$get2[0]->first_name." ".$get2[0]->last_name;
					$Currprofile_picture[]=$get2[0]->profile_picture;
					$date1= strtotime($row8->comment_date);
					$comment_date= substr(date('r',$date1),0,-5);
					$CurrComment[] = "<font size=\"3\">".$row8->comment."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
				}
			}
		}
		if(isset($video)) {
			foreach ($video as $key => $value) {
				$options=array(
					'table'=>'pv_comment',
					'upload_id'=>$VDupload_id[$key],
					'type'=>1,
					'sortBy'=>'id',
					'sortDirection'=>'desc'
				);
				$get=$this->general_model->_get($options);				
				if(isset($this->session->userdata('video_id')) && $get) {
					if($this->session->userdata('video_id')==$VDupload_id[$key])
					$VideoComcount=$VideoComcount+count($get);
				}
				else if($key==0 && $get) $VideoComcount=$VideoComcount+count($get);
				if($get){
					foreach($get as $row13)
					{	
						$curr_user=$row13->viewer_user_id;
						$options=array(
							'table'=>'user',
							'id'=>$row13->viewer_user_id,
							'limit'=>1
						);
						$get1=$this->general_model->_get($options);
						if($get1){
							foreach($get1 as $row15)
							{		
								$Currname=$row15->first_name." ".$row15->last_name;
								$CurrVideoprofile_picture[]=$row15->profile_picture;
							}
						}						
						$date1= strtotime($row13->comment_date);
						$comment_date= substr(date('r',$date1),0,-5);
						if(isset($this->session->userdata('video_id'))) {
							if($this->session->userdata('video_id')==$VDupload_id[$key]) {
								$CurrVideoComment[] = "<font size=\"3\">".$row13->comment."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
								$Videoupload_id[]=$VDupload_id[$key];
							}
						}
						elseif($key==0)	{
							$CurrVideoComment[] = "<font size=\"3\">".$row13['comment']."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
							$Videoupload_id[]=$VDupload_id[$key];
						}
					}
				}
			}
		}
		$Videopagenum = 1; 
		$Videopage_rows = 4;
		$Videolast = ceil($Videocount/$Videopage_rows); 
		if ($Videopagenum < 1) $Videopagenum = 1; elseif ($Videopagenum > $Videolast) $Videopagenum = $Videolast; 
		$Videofirst_row=($Videopagenum -1)* $Videopage_rows;
		$Videoprevious = $Videopagenum-1;
		if($Videoprevious <= 0) $Videoprevious=1;
		$Videonext = $Videopagenum+1;
		if($Videonext > $Videocount) $Videonext=$Videocount;

		$VideoCompagenum = 1; 
		$VideoCompage_rows = 2;  
		$VideoComlast = ceil($VideoComcount/$VideoCompage_rows); 
		if ($VideoCompagenum < 1) $VideoCompagenum = 1; elseif ($VideoCompagenum > $VideoComlast) $VideoCompagenum = $VideoComlast; 
		$VideoComfirst_row=($VideoCompagenum -1)* $VideoCompage_rows;
		$VideoComprevious = $VideoCompagenum-1;
		if($VideoComprevious <= 0) $VideoComprevious=1;
		$VideoComnext = $VideoCompagenum+1;
		if($VideoComnext > $VideoComcount) $VideoComnext=$VideoComcount;

		$Ownerpagenum = 1; 
		$Ownerpage_rows = 4;  
		$Ownerlast = ceil($OwnerComcount/$Ownerpage_rows); 
		if ($Ownerpagenum < 1) $Ownerpagenum = 1; elseif ($Ownerpagenum > $Ownerlast) $Ownerpagenum = $Ownerlast; 
		$Ownerfirst_row=($Ownerpagenum -1)* $Ownerpage_rows;
		$Ownerprevious = $Ownerpagenum-1;
		if($Ownerprevious <= 0) $Ownerprevious=1;
		$Ownernext = $Ownerpagenum+1;
		if($Ownernext > $OwnerComcount) $Ownernext=$OwnerComcount;
		$Ownercount=0;
			
		$Picturepagenum = 1; 
		$Picturepage_rows = 2;  
		$Picturelast = ceil($PictureComcount/$Picturepage_rows); 
		if ($Picturepagenum < 1) $Picturepagenum = 1; elseif ($Picturepagenum > $Picturelast) $Picturepagenum = $Picturelast; 
		$Picturefirst_row=($Picturepagenum -1)* $Picturepage_rows;
		$Pictureprevious = $Picturepagenum-1;
		if($Pictureprevious <= 0) $Pictureprevious=1;
		$Picturenext = $Picturepagenum+1;
		if($Picturenext > $PictureComcount) $Picturenext=$PictureComcount;
		if($FriendID!="Public" && $FriendID!="SharedPicture" && $FriendID!="Temporary") {
			$options=array(
				'table'=>'user_screen',
				'user_id'=>$FriendID,
				'sortBy'=>'id',
				'sortDirection'=>'desc',
				'limit'=>1
			);
			$UserScreen=$this->general_model->_get($options);
			$options=array(
				'table'=>'user_browser',
				'user_id'=>$FriendID,
				'sortBy'=>'id',
				'sortDirection'=>'desc'
			);
			$UserBrowser=$this->general_model->_get($options);
			$options=array(
				'table'=>'user_os',
				'user_id'=>$FriendID,
				'sortBy'=>'id',
				'sortDirection'=>'desc',
				'limit'=>1
			);
			$UserOS=$this->general_model->_get($options);
			$options=array(
				'table'=>'user_location',
				'user_id'=>$FriendID,
				'sortBy'=>'id',
				'sortDirection'=>'desc',
				'limit'=>1
			);
			$UserLocation=$this->general_model->_get($options);
			$data['UserScreen']=$UserScreen;
			$data['UserBrowser']=$UserBrowser;
			$data['UserOS']=$UserOS;
			$data['UserLocation']=$UserLocation;
		}
	$data['pictureCount']=$pictureCount;
	$data['FriendID']=$FriendID;
	if(isset($user_id)) $data['user_id']=$user_id;
//	$data['curr_path']=$curr_path;
//	$data['upload_id2']=$upload_id2;
	$this->load->view("PictureMain_view",$data);
	}
	public function signout() {
		setcookie('member_name', '', time() - 96 * 3600, '/');
		setcookie('user_name', '', time() - 96 * 3600, '/');
		setcookie('member_pass', '', time() - 96 * 3600, '/');
		setcookie ('siteAuth', '', time() - 96 * 3600);
		setcookie ('Pid', '', time() - 96 * 3600);
		setcookie ('greeting', '', time() - 96 * 3600);
		$this->user->Update_last_activity($this->session->userdata('id'));
		$this->view_permission->Set_activity($this->session->userdata('email_address'));
		
		foreach($_COOKIE as $name=>$value) {
			unset($_COOKIE["$name"]);
		}
		$this->session->sess_destroy();
//		echo "this is testing";
//		$this->load->view('main_header');
//		$this->load->view("header2",$this->data);
//		$this->load->view("index",$this->data);			
		redirect('/', 'refresh'); 


	}

}

