<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CommentVideo extends MY_Controller {
	public function index()
	{
		<?php
		session_start();
		include("../config.php");
		include("../inc/GlobalVar.inc.php");
		if(isset($_REQUEST['FriendID'])) $FriendID = $_REQUEST['FriendID'];
		if(isset($_REQUEST['VideoComment']))
		{
			$upload_id = (int)$_REQUEST['upload_id'];
			$_SESSION["upload_id"]=$upload_id;
			$comment =  mysql_real_escape_string($_REQUEST['VideoComment']);
			$pv_idresult=mysql_query("SELECT * FROM picture_video where picture_video='videos' and upload_id=$upload_id limit 1");  // query string stored in a variable
			echo mysql_error();              // if any error is there that will be printed to the screen 
			while($pv_idrow = mysql_fetch_array($pv_idresult))
			{
				$PV_id=$pv_idrow['id'];
				$_SESSION["pv_id"]=$PV_id;
				$pos=strpos($pv_idrow['name'],".",3)+1;
				$_SESSION["curr_video"]=substr($pv_idrow['name'],0,$pos);
				$owner_path=$pv_idrow['owner_path'];
			};	
			$owneridResult = mysql_query("SELECT * FROM user WHERE owner_path = '$owner_path' limit 1"); /* get his/her friend infor */
			while($owneridrow = mysql_fetch_array($owneridResult)) {
				$owner_id1=$owneridrow['id'];
			}
			if($comment!="") {
				$beforeIns=mysql_query("SELECT * FROM pv_comment where PV_id = $PV_id and upload_id=$upload_id and user_id=$owner_id1 and viewer_user_id=$GV_id and comment='$comment' and type=1 limit 1");  // query string stored in a variable
				if(mysql_num_rows($beforeIns)==0) mysql_query("INSERT INTO pv_comment(upload_id,viewer_user_id,user_id,name,comment,PV_id,type) VALUES($upload_id,$GV_id,$owner_id1,'$GV_name','$comment',$PV_id,1)"); /* I am a viewer, the name is my name for display purpose*/
				echo mysql_error();
			}	
		}
		if($FriendID==$GV_id || $FriendID=="SharedPicture") $Permissionresult=mysql_query("SELECT * FROM view_permission where user_id=$GV_id group by viewer_group");  // query string stored in a variable
		else $Permissionresult=mysql_query("SELECT * FROM view_permission where user_id=$FriendID and viewer_id=$GV_id group by viewer_group");  // query string stored in a variable
		echo mysql_error();              // if any error is there that will be printed to the screen 
		while($row3 = mysql_fetch_array($Permissionresult))
		{
			$group[]=$row3['viewer_group'];
		};
		$VideoComcount=0;

		$Videocount=0;
		if($FriendID=="SharedPicture") {
			$pv_idresult2=mysql_query("SELECT * FROM pv_share where shareto_id=$GV_id and is_video=1"); 
			while($pv_idrow2 = mysql_fetch_array($pv_idresult2))
			{
				$pos = strpos($pv_idrow2['pv_name'], ".",3);
				$video[]=substr($pv_idrow2['pv_name'],0,$pos).".";
				$pv_id[]=$pv_idrow2['pv_id'];
				$Videocount++;	
				$upload_idresult=mysql_query("SELECT * FROM picture_video where id=$pv_idrow2[pv_id] limit 1"); 
				while($upload_idrow = mysql_fetch_array($upload_idresult))
				{
					$VDupload_id[]=$upload_idrow['upload_id'];
				}	
			}
		}
		else {
			$result=mysql_query("SELECT * FROM upload_infor where user_id=$FriendID and name<>''");  // query string stored in a variable
			echo mysql_error();              // if any error is there that will be printed to the screen 
			while($row2 = mysql_fetch_array($result))
			{
				$pv_idresult3 = mysql_query("SELECT * FROM picture_video WHERE upload_id=$row2[id] limit 1");
				echo mysql_error(); 
				while($pv_idrow3 = mysql_fetch_array($pv_idresult3)) {
					$curr_pv_id=$pv_idrow3['id'];
				}	
				if($row2['viewer_group']=="") {
					$video[]=$row2['name'].".";
					$VDupload_id[]=$row2['id'];
					$pv_id[]=$curr_pv_id;
					$Videocount++;
				}
				else {
					foreach ($group as $key => $this_group) {
						if($row2['viewer_group']==$this_group) {
							$video[]=$row2['name'].".";
							$VDupload_id[]=$row2['id'];
							$pv_id[]=$curr_pv_id;
							$Videocount++;
						}
					}
				}
			};
		}
		if(!isset($_SESSION["pv_id"])) $_SESSION["pv_id"]=$pv_id[0];
		$Sharerows=0;
		$FriendResult = mysql_query("SELECT * FROM view_permission WHERE user_id=$GV_id and is_active>0 group by viewer_id");
		while($option = mysql_fetch_array($FriendResult)) {
			$PictureResult = mysql_query("SELECT * FROM user WHERE email_address = '$option[viewer_email]' limit 1"); /* get his/her friend infor */
			while($row = mysql_fetch_array($PictureResult)) {
				$profile_picture=$row['profile_picture'];
				$first_name=$row['first_name'];
				$last_name=$row['last_name'];
			}
			if(!isset($optionViewer_id) && isset($profile_picture)) {
				$optionViewer_id[] =  $option['viewer_id'];
				$optionPicture[] = $profile_picture;
				$FirstName[] = $first_name;
				$LastName[] = $last_name;
				$optionSel[] = $option['share_flag'];
				$Sharerows++;
				$shareResult = mysql_query("SELECT * FROM pv_share WHERE pv_id=$_SESSION[pv_id] and shareto_id=$option[viewer_id] limit 1");
				echo mysql_error(); 
				if(mysql_num_rows($shareResult) != 0) $shareFlag[]="checked='checked'";
				else $shareFlag[]="";
			}
			elseif(isset($optionViewer_id)) {
				$duplicated = false;
				foreach($optionViewer_id as $id=>$Viewer_id) {
					if($Viewer_id==$option['viewer_id']) $duplicated = true;
				}
				if($duplicated == false){
					$optionViewer_id[] = $option['viewer_id'];
					$optionPicture[] = $profile_picture;
					$FirstName[] = $first_name;
					$LastName[] = $last_name;
					$optionSel[] = $option['share_flag'];
					$Sharerows++;
					$shareResult = mysql_query("SELECT * FROM pv_share WHERE pv_id=$_SESSION[pv_id] and shareto_id=$option[viewer_id] limit 1");
					if(mysql_num_rows($shareResult) != 0) $shareFlag[]="checked='checked'";
					else $shareFlag[]="";
				}
			}
		}	

		if(isset($video)) {
			foreach ($video as $key => $value) {
				$queryComment3=mysql_query("SELECT * FROM pv_comment where upload_id=$VDupload_id[$key] and type=1 order by id desc");  // query string stored in a variable
				echo mysql_error();
				if($key==0)	$VideoComcount=$VideoComcount+mysql_num_rows($queryComment3);
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
					if(isset($_SESSION["upload_id"])) {
						if($_SESSION["upload_id"]==$VDupload_id[$key]) {
							$CurrVideoComment[] = "<font size=\"3\">".$row13['comment']."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
							$Videoupload_id[]=$VDupload_id[$key];
						}
					}
					elseif($key==0)	{
						$CurrVideoComment[] = "<font size=\"3\">".$row13['comment']."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
						$Videoupload_id[]=$VDupload_id[$key];
						$_SESSION["upload_id"]=$VDupload_id[$key];
					}
				}
			}
		}
		$Sharepagenum=1;
		$Sharepage_rows = 12;  
		$Sharelast = ceil($Sharerows/$Sharepage_rows); 
		if ($Sharepagenum < 1) $Sharepagenum = 1; elseif ($Sharepagenum > $Sharelast) $Sharepagenum = $Sharelast; 
		$Sharefirst_row=($Sharepagenum -1) * $Sharepage_rows;
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
		?>
	
		$this->load->view("commentvideo_view");
	}
}
