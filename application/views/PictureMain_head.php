<?php
if($this->session->userdata('private') && $this->session->userdata('private') == "yes")
{
	$profile_picture=$this->session->userdata('profile_picture');
	$user_id=$this->session->userdata('id');
	$curr_path=$this->session->userdata('owner_path');
	if(isset($_POST['comment']))
	{
		$upload_id2 = (int)$_POST['upload_id'];
		$comment =  $_POST['comment'];
		if(isset($_POST['ViewingID']) && $_POST['ViewingID'] != "" && $_POST['ViewingID'] != "Public" && $_POST['ViewingID'] != "Temporary") $ViewingID = $_POST['ViewingID'];
		else $ViewingID = $this->session->userdata('id');
		$options=array(
			'table'=>'pv_comment',
			'upload_id'=>$upload_id2,
			'user_id'=>$ViewingID,
			'viewer_user_id'=>$this->session->userdata('id'),
			'comment'=>'$comment',
			'type'=>0
		);
		$this->general_model->_add($options);
	} 
	elseif(isset($_POST['VideoComment']))
	{
		$upload_id2 = $this->input->post('video_id');
		$this->session->set_userdata('video_id', $upload_id2);
		$this->session->set_userdata('video_name', $this->input->post('video_name'));
		if($this->input->post('ViewingID') && $this->input->post('ViewingID')!="" && $this->input->post('ViewingID')!="Public" && $this->input->post('ViewingID')!= "Temporary") $ViewingID = $this->input->post('ViewingID');
		else $ViewingID = $this->session->userdata('id');
		$comment = $this->input->post('VideoComment');
		$options=array(
			'table'=>'picture_video',
			'picture_video'=>'videos',
			'upload_id'=>$upload_id2,
			'limit'=>1
		);
		$get=$this->general_model->_get($options);
		$PV_id=$get[0]->id;
		if($comment!="") {
			$options=array(
				'table'=>'pv_comment',
				'upload_id'=>$upload_id2,
				'user_id'=>$ViewingID,
				'viewer_user_id'=>$this->session->userdata('id'),
				'comment'=>'$comment',
				'name'=>$_SESSION['name'],
				'PV_id'=>$PV_id,
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
		$this->session->set_userdata('FriendID', $FriendID);
	}
	elseif(isset($_POST['ViewingID'])) {
		$FriendID = $_POST['ViewingID'];
		$this->session->set_userdata('FriendID', $FriendID);
	}
	else $FriendID=$this->session->userdata('FriendID');
	if(isset($_POST['show_id']) && $_POST['show_id']!='')	{
		$show_id=$_POST['show_id'];
		$this->session->set_userdata('show_id', $show_id);
	}
	
	if(!isset($show_id) && isset($this->session->userdata('show_id')) && $FriendID!="Public" && $FriendID!="SharedPicture") $show_id=$this->session->userdata('show_id');
	if($FriendID == 'Public') {
		$Picked['Public'] = 'Public';
		$GetSomething="both";
		$user_id='Public';
	}
	elseif($FriendID == 'Temporary') {
			$options=array(
				'table'=>'picture_video',
				'picture_video'=> 'pictures',
				'viewer_group'=> 'Temporary',
				'upload_id'=>$show_id,
				'sortBy'=>'upload_id',
				'sortDirection'=>'desc',
				'limit'=>1
			);
			
			$get=$this->general_model->_get($options);		
			$TemporaryPath=$get[0]->owner_path;
			$options=array(
				'table'=>'user',
				'owner_path'=>"$TemporaryPath",
				'limit'=>1
			);
			
			$get=$this->general_model->_get($options);			
			$TemporaryUser=$get[0]->id;
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
			$beforeShow=mysql_query("SELECT * FROM picture_video where owner_path = '$_SESSION[owner_path]' and viewer_group <> 'Public' and viewer_group <> 'Temporary'");  // query string stored in a variable
			while($row3 = mysql_fetch_array($beforeShow)) {
				if($GetSomething=='') $GetSomething=$row3['picture_video'];
				elseif($GetSomething!=$row3['picture_video']) $GetSomething="both";
			}	
			if($GetSomething=='' && $FriendID!="Temporary") $Picked['Public'] = 'Public';	
			elseif($GetSomething=='' && $FriendID=="Temporary") $Picked['Temporary'] = 'Temporary';	
			else {
				$queryPermission="SELECT * FROM view_permission where owner_email = '$_SESSION[email_address]' group by viewer_group";  // query string stored in a variable
				$resultPermission=mysql_query($queryPermission);          // query executed 
				echo mysql_error();              // if any error is there that will be printed to the screen 
				while($row = mysql_fetch_array($resultPermission))
				{
				 $viewer_group = $row['viewer_group'];
				 $Picked[$this->session->userdata('owner_path')] = $viewer_group;
				}
			}
		}	
	else {
			$GetSomething='';
			$getowner1=mysql_query("SELECT * FROM user where id = $FriendID limit 1");  // query string stored in a variable
			while($owneresult1 = mysql_fetch_array($getowner1)) {
				$result_path=$owneresult1['owner_path'];
				$user_id=$owneresult1['id'];
				$curr_path=$result_path;
				$profile_picture=$owneresult1['profile_picture'];
			}
			$beforeShow=mysql_query("SELECT * FROM picture_video where owner_path = '$result_path' and viewer_group <> 'Public' and viewer_group <> 'Temporary'");  // query string stored in a variable
			while($row3 = mysql_fetch_array($beforeShow)) {
				if($GetSomething=='') $GetSomething=$row3['picture_video'];
				elseif($GetSomething!=$row3['picture_video']) $GetSomething="both";
			}	
			if($GetSomething=='' && $FriendID!="Temporary") $Picked['Public'] = 'Public';	
			elseif($GetSomething=='' && $FriendID=="Temporary") $Picked['Temporary'] = 'Temporary';	
			else {
				$queryPermission="SELECT * FROM view_permission where user_id = $FriendID and viewer_id = $this->session->userdata('id') order by owner_path";  // query string stored in a variable
				$resultPermission=mysql_query($queryPermission);          // query executed 
				echo mysql_error();              // if any error is there that will be printed to the screen 
				while($row = mysql_fetch_array($resultPermission))
				{
				 $viewer_group = $row['viewer_group'];
				 $owner_path = $row['owner_path'];
				 $Picked[$owner_path] = $viewer_group;
				}
			}			
		}
}
elseif(isset($this->session->userdata('id')) && !(isset($_POST['FriendID']) || isset($_POST['ViewingID']) || $this->session->userdata('FriendID'))) {
	$FriendID=$this->session->userdata('id');
	$GetSomething='';
	$beforeShow=mysql_query("SELECT * FROM picture_video where owner_path = '$_SESSION[owner_path]' and viewer_group <> 'Public' and viewer_group <> 'Temporary'");  // query string stored in a variable
	while($row3 = mysql_fetch_array($beforeShow)) {
		if($GetSomething=='') $GetSomething=$row3['picture_video'];
		elseif($GetSomething!=$row3['picture_video']) $GetSomething="both";
	}	
	if($GetSomething=='' && $FriendID!="Temporary") $Picked['Public'] = 'Public';
	elseif($GetSomething=='' && $FriendID=="Temporary") $Picked['Public'] = 'Public';
	else {
		$queryPermission="SELECT * FROM view_permission where owner_email = '$_SESSION[email_address]' group by viewer_group";  // query string stored in a variable
		$resultPermission=mysql_query($queryPermission);          // query executed 
		echo mysql_error();              // if any error is there that will be printed to the screen 
		while($row = mysql_fetch_array($resultPermission))
		{
		 $viewer_group = $row['viewer_group'];
		 $this->session->set_userdata('owner_path', $row['owner_path']);
		 $Picked[$this->session->userdata('owner_path')] = $viewer_group;
		}
	}
}
elseif(isset($_POST['FriendID']) && $_POST['FriendID']=="Temporary") {
	if(isset($_POST['show_id']) && $_POST['show_id']!='')	{
		$show_id=$_POST['show_id'];
		$this->session->set_userdata('show_id', $show_id);
	}
	$TemporaryShow=mysql_query("SELECT * FROM picture_video where picture_video = 'pictures' and viewer_group = 'Temporary' and upload_id=$show_id order by upload_id desc,id desc limit 1");  // query string stored in a variable
	while($Temporaryrow = mysql_fetch_array($TemporaryShow)) {
		$TemporaryPath=$Temporaryrow['owner_path'];
	}
	$TemporaryShow2=mysql_query("SELECT * FROM user where owner_path = '$TemporaryPath' limit 1");  // query string stored in a variable
	while($Temporaryrow2 = mysql_fetch_array($TemporaryShow2)) {
		$TemporaryUser=$Temporaryrow2['id'];
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
			$query="SELECT * FROM upload_infor where viewer_group = 'Public' and name <> ''";  // query string stored in a variable
			$result=mysql_query($query);          // query executed 
			echo mysql_error();              // if any error is there that will be printed to the screen 
			while($row2 = mysql_fetch_array($result))
			{
				$video[$Videocount]=$row2['name'].".";
				$VDupload_id[$Videocount]=$row2['id'];
				$VideoGroup[$Videocount]=$value;
				$Videocount++;
			};
		}
		elseif($key =='Temporary') {
			$query="SELECT * FROM upload_infor where viewer_group = 'Temporary' and user_id=$TemporaryUser and name <> ''";  // query string stored in a variable
			$result=mysql_query($query);          // query executed 
			echo mysql_error();              // if any error is there that will be printed to the screen 
			while($row2 = mysql_fetch_array($result))
			{
				$video[$Videocount]=$row2['name'].".";
				$VDupload_id[$Videocount]=$row2['id'];
				$VideoGroup[$Videocount]=$value;
				$Videocount++;
			};
		}
		elseif($key =='SharedPicture') {
			$query="SELECT * FROM pv_share where shareto_id = $this->session->userdata('id') and is_video=1";  // query string stored in a variable
			$result=mysql_query($query);          // query executed 
			echo mysql_error();              // if any error is there that will be printed to the screen 
			while($row2 = mysql_fetch_array($result))
			{
				$pos = strpos($row2['pv_name'], ".",3);
				$video[]=substr($row2['pv_name'],0,$pos).".";
				$VideoGroup[$Videocount]=$value;
				$Videocount++;
				$upload_idresult=mysql_query("SELECT * FROM picture_video where id=$row2[pv_id] limit 1"); 
				while($upload_idrow = mysql_fetch_array($upload_idresult))
				{
					$VDupload_id[]=$upload_idrow['upload_id'];
				}				
			};
		}
		else {
			$getowner2=mysql_query("SELECT * FROM user where owner_path = '$key' limit 1");  // query string stored in a variable
			while($owneresult2 = mysql_fetch_array($getowner2)) {
				$user_id1=$owneresult2['id'];
			}			
		
			$query="SELECT * FROM upload_infor where user_id=$user_id1 and (viewer_group = '$value' or viewer_group = '') and name <> ''";  // query string stored in a variable
			$result=mysql_query($query);          // query executed 
			echo mysql_error();              // if any error is there that will be printed to the screen 
			while($row2 = mysql_fetch_array($result))
			{
				$video[$Videocount]=$row2['name'].".";
				$VDupload_id[$Videocount]=$row2['id'];
				$VideoGroup[$Videocount]=$value;
				$Videocount++;
			};
		}
	}
}
if(isset($user_id) && $user_id!='Public' && $user_id!='Temporary' && $user_id!='SharedPicture')	{
	$name="";
	$queryComment=mysql_query("SELECT * FROM pv_comment where viewer_user_id=$user_id order by id desc");  // query string stored in a variable
	echo mysql_error(); 
	$OwnerComcount=mysql_num_rows($queryComment);
	while($row4 = mysql_fetch_array($queryComment))
	{		
		$queryUser=mysql_query("SELECT * FROM user where id=$row4[user_id] limit 1");
		while($row6 = mysql_fetch_array($queryUser))
		{		
			$name=$row6['first_name']." ".$row6['last_name'];
			$profile_picture=$row6['profile_picture'];
		}				
		$queryPV=mysql_query("SELECT * FROM picture_video where upload_id=$row4[upload_id] limit 1");
		$date1= strtotime($row4['comment_date']);
		$comment_date= substr(date('r',$date1),0,-15);
		if($FriendID!='Public' && $FriendID!='SharedPicture' && $FriendID!='Temporary') {
			while($row5 = mysql_fetch_array($queryPV))
			{
				$pic_group[]=$row5['viewer_group'];
			}
			$comment_tmp=$row4['comment']."<font color='darkblue'> on $name"."'s</font> ";
			if($row4['type']==1) $comment_tmp.="<font color='blue'>video</font>($comment_date)<br/>";
			else $comment_tmp.="<font color='red'>photo</font>($comment_date)</font><br/>";
			$comments[] = $comment_tmp;
			$pic_upload_id[]=$row4['upload_id'];
			$owner_id[]=$row4['user_id'];
		}
	}
}
	
if(isset($_POST['FriendID']) || $this->session->userdata('FriendID')) {
	if(isset($_POST['FriendID'])) $FriendID = $_POST['FriendID'];
	else $FriendID = $this->session->userdata('FriendID');
	if($FriendID!='Public' && $FriendID!='Temporary' && $FriendID!='SharedPicture') {
		$getowner=mysql_query("SELECT * FROM user where id = $FriendID limit 1");  // query string stored in a variable
		while($owneresult = mysql_fetch_array($getowner)) {
			$result_path=$owneresult['owner_path'];
			$result_name=ucfirst(strtolower($owneresult['first_name']))." ".ucfirst(strtolower($owneresult['last_name']));
			$result_profile_picture=$owneresult['profile_picture'];
		}
	}
}
elseif(isset($this->session->userdata('id'))) {
 $FriendID=$this->session->userdata('id');
 $result_path = $this->session->userdata('owner_path');
 $result_name=$_SESSION['name'];
 $result_profile_picture=$this->session->userdata('profile_picture');
}
elseif(isset($FriendID) && $FriendID=="Temporary") {
 $FriendID="Temporary";
}
else $FriendID="Public";
$GetSomething=0;
if(isset($result_path)) {
	$beforeShow=mysql_query("SELECT * FROM picture_video where owner_path = '$result_path' and viewer_group <> 'Public' and viewer_group <> 'Temporary' order by upload_id desc, id desc limit 1");  // query string stored in a variable
	if(mysql_num_rows($beforeShow) != 0) {
		$GetSomething=1;
	}
}
 
	if(isset($upload_id2)){ $upload_id = $upload_id2;	}
	elseif(isset($show_id)) $upload_id = $show_id;
	if(!isset($upload_id) || $upload_id =="")
	{
		if($GetSomething==0){ 
			$beforeShow2=mysql_query("SELECT * FROM picture_video where picture_video = 'pictures' and viewer_group = 'Public' order by upload_id desc,id desc limit 1");  // query string stored in a variable
//			echo "PUBLIC PICTURES";
		}
		elseif($FriendID=="Temporary") 
			$beforeShow2=mysql_query("SELECT * FROM picture_video where upload_id = $upload_id and picture_video = 'pictures' order by upload_id desc limit 1");  // query string stored in a variable
		else 
			$beforeShow2=mysql_query("SELECT * FROM picture_video where owner_path = '$result_path' and picture_video = 'pictures' and viewer_group <> 'Public' and viewer_group <> 'Temporary' order by upload_id desc limit 1");  // query string stored in a variable
		while($row7 = mysql_fetch_array($beforeShow2))
		{
			$upload_id = $row7['upload_id'];
		}
	}
	if(isset($this->session->userdata('id')) && isset($upload_id) && $upload_id!="" && $upload_id!=0 && $upload_id!="SharedPicture") $resultShow=mysql_query("SELECT * FROM picture_video where picture_video = 'pictures' and upload_id = $upload_id order by upload_id desc, id desc");  // query string stored in a variable
	elseif($upload_id=="SharedPicture") $resultShow=mysql_query("SELECT * FROM pv_share where shareto_id = $this->session->userdata('id') and is_video=0 order by id desc");  // query string stored in a variable
	elseif(!isset($this->session->userdata('id')) && isset($FriendID) && $FriendID=="Temporary") $resultShow=mysql_query("SELECT * FROM picture_video where picture_video = 'pictures' and viewer_group = 'Temporary' and upload_id=$upload_id order by upload_id desc, id desc");  // query string stored in a variable
	else $resultShow=mysql_query("SELECT * FROM picture_video where picture_video = 'pictures' and viewer_group = 'Public' order by upload_id desc, id desc");  // query string stored in a variable

	$markup = '<div  id="slideshow">';
	$pictureCount=0;
	$markupviewer_group="";
	while($row3 = mysql_fetch_array($resultShow)) {
		$pictureCount++;
		if($upload_id=="SharedPicture") {
			$markup .= "<img class=\"pointer\" style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"$row3[pv_name]\" height=\"420\">";
		}
		else {
			$markupviewer_group=$row3['viewer_group'];
			$markup .= "<img class=\"pointer\" style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"$row3[name]\" height=\"420\">";
		}
	};
	if($pictureCount==0) $markup .= "<img style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"/images/blank.jpg\" height=\"420\"><img style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"/images/blank.jpg\" height=\"420\">";
	//elseif($pictureCount==1) $markup .= "<img style=\"position: absolute; top: 0px; left: 0px; display: block; z-index: 6; opacity: 1;  height: 420px;\" src=\"/images/blank.jpg\" height=\"420\">";
	$markup .= '</div>';	
	if(isset($upload_id) && $upload_id!='' && $upload_id!="SharedPicture")	{
		$queryComment2=mysql_query("SELECT * FROM pv_comment where upload_id=$upload_id and type=0 order by id desc");  // query string stored in a variable
		echo mysql_error(); 
		$PictureComcount=mysql_num_rows($queryComment2);
		while($row8 = mysql_fetch_array($queryComment2))
		{	
			$curr_user=$row8['viewer_user_id'];
			$queryPV2=mysql_query("SELECT * FROM picture_video where upload_id=$row8[upload_id] limit 1");
			$queryUser=mysql_query("SELECT * FROM user where id=$curr_user limit 1");
			while($row10 = mysql_fetch_array($queryUser))
			{		
				$Currname=$row10['first_name']." ".$row10['last_name'];
				$Currprofile_picture[]=$row10['profile_picture'];
			}				
			$date1= strtotime($row8['comment_date']);
			$comment_date= substr(date('r',$date1),0,-5);
			$CurrComment[] = "<font size=\"3\">".$row8['comment']."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
		}
	}
	if(isset($video)) {
		foreach ($video as $key => $value) {
			$queryComment3=mysql_query("SELECT * FROM pv_comment where upload_id=$VDupload_id[$key] and type=1 order by id desc");  // query string stored in a variable
			echo mysql_error();
			if($this->session->userdata('video_id')) {
				if($this->session->userdata('video_id')==$VDupload_id[$key]) $VideoComcount=$VideoComcount+mysql_num_rows($queryComment3);
			}
			else if($key==0) $VideoComcount=$VideoComcount+mysql_num_rows($queryComment3);
			while($row13 = mysql_fetch_array($queryComment3))
			{	
				$curr_user=$row13['viewer_user_id'];
				$queryUser=mysql_query("SELECT * FROM user where id=$curr_user limit 1");
				while($row15 = mysql_fetch_array($queryUser))
				{		
					$Currname=$row15['first_name']." ".$row15['last_name'];
					$CurrVideoprofile_picture[]=$row15['profile_picture'];
				}				
				$date1= strtotime($row13['comment_date']);
				$comment_date= substr(date('r',$date1),0,-5);
				if($this->session->userdata('video_id')) {
					if($this->session->userdata('video_id')==$VDupload_id[$key]) {
						$CurrVideoComment[] = "<font size=\"3\">".$row13['comment']."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
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
	$UserScreen=mysql_query("SELECT * FROM user_screen where user_id = $FriendID ORDER BY  `id` DESC limit 1 ");  // query string stored in a variable
	echo mysql_error(); 
	$UserBrowser=mysql_query("SELECT * FROM user_browser where user_id = $FriendID ORDER BY  `id` DESC ");  // query string stored in a variable
	echo mysql_error(); 
	$UserOS=mysql_query("SELECT * FROM user_os where user_id = $FriendID ORDER BY  `id` DESC limit 1");  // query string stored in a variable
	echo mysql_error(); 
	$UserLocation=mysql_query("SELECT * FROM user_location where user_id = $FriendID ORDER BY  `id` DESC limit 1");  // query string stored in a variable
	echo mysql_error(); 
}
?>
