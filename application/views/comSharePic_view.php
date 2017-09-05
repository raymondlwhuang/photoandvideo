<?php
session_start();
include("../config.php");
include("../inc/GlobalVar.inc.php");

if(isset($_REQUEST['comment']))
{
	$PV_id = (int)$_REQUEST['pv_id'];
	$_SESSION['sharefm_id']=(int)$_REQUEST['sharefm_id'];
	$_SESSION['this_id']=$PV_id;
	$comment =  mysql_real_escape_string($_REQUEST['comment']);
	$queryPV=mysql_query("SELECT * FROM picture_video where id = $PV_id limit 1");  // query string stored in a variable
	while($rowPV = mysql_fetch_array($queryPV)) {
		$_SESSION['thisPicture'] = str_replace("/pictures/", "/Orgpictures/", $rowPV['name']);
		$upload_id=$rowPV['upload_id'];
		$queryupload_infor=mysql_query("SELECT * FROM upload_infor where id = $upload_id limit 1"); 
		while($rowupload_infor = mysql_fetch_array($queryupload_infor)) {
			$owner_id1=$rowupload_infor['user_id'];
		}
	}	
	mysql_query("INSERT INTO pv_comment(`PV_id`, `upload_id`, `user_id`, `viewer_user_id`, `name`, `comment`) VALUES($PV_id, $upload_id, $owner_id1, $GV_id, '$GV_name', '$comment')"); /* I am a viewer, the name is my name for display purpose*/
//	echo mysql_error(); 
echo <<<_END
<script type="text/javascript">
window.open('ComSharePic.php',target='_top');
</script>
_END;
		
exit();
}

$beforeShow=mysql_query("SELECT * FROM pv_share where shareto_id = $GV_id and is_video=0 order by id desc");  // query string stored in a variable
while($row3 = mysql_fetch_array($beforeShow)) {
	$pictures[]=$row3['pv_name'];
	$OrgPicture[] = str_replace("/pictures/", "/Orgpictures/", $row3['pv_name']);
	if(!isset($_SESSION['sharefm_id']) && !isset($pv_id)) $_SESSION['sharefm_id']=$row3['sharefm_id'];
	$pv_id[]=$row3['pv_id'];
}
	
$name="";
if(!isset($_SESSION['this_id'])) $_SESSION['this_id']=$pv_id[0];
$queryComment=mysql_query("SELECT * FROM pv_comment where pv_id=$_SESSION[this_id] order by id desc");  // query string stored in a variable
echo mysql_error(); 
$Comcount=0;
while($row4 = mysql_fetch_array($queryComment))
{	
	if($row4['PV_id']==$_SESSION['this_id']) {
		$Comcount++;
		$queryUser=mysql_query("SELECT * FROM user where id=$row4[viewer_user_id] limit 1");
		while($row6 = mysql_fetch_array($queryUser))
		{		
			$name=$row6['first_name']." ".$row6['last_name'];
			$result_profile_picture[]=$row6['profile_picture'];
		}				
		$date1= strtotime($row4['comment_date']);
		$comment_date= substr(date('r',$date1),0,-6);
		$comments[] = "<div style=\"display:inline-block;vertical-align:top;text-align:left;\">".$row4['comment']."<font size='3'>($comment_date)</font></div>";
		$pic_upload_id[]=$row4['upload_id'];
		$owner_id[]=$row4['user_id'];
	}
}
$pagenum = 1; 
$page_rows = 6;  
$last = ceil($Comcount/$page_rows); 
if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
$first_row=($pagenum -1)* $page_rows;
$previous = $pagenum-1;
if($previous <= 0) $previous=1;
$next = $pagenum+1;
if($next > $Comcount) $next=$Comcount;
$Sharerows=0;
$beforeList1 = mysql_query("SELECT * FROM picture_video WHERE id=$_SESSION[this_id] limit 1");
while($rowB1 = mysql_fetch_array($beforeList1)) {
	$owner_path=$rowB1['owner_path'];
}
$beforeList2 = mysql_query("SELECT * FROM user WHERE owner_path='$owner_path' limit 1");
while($rowB2 = mysql_fetch_array($beforeList2)) {
	$Curowner_id=$rowB2['id'];
}
$beforeList3 = mysql_query("SELECT * FROM pv_share WHERE pv_id=$_SESSION[this_id] and sharefm_id <> $GV_id");
while($rowB3 = mysql_fetch_array($beforeList3)) {
	if(!isset($skip_id)) $skip_id[]=$rowB3['sharefm_id'];
	$found=false;
	foreach ($skip_id as $key=>$id) {
		if($rowB3['sharefm_id']==$id) $found=true;
	}
	if($found==false && $rowB3['sharefm_id']!=$GV_id)  $skip_id[]=$rowB3['sharefm_id'];
	$found=false;
	foreach ($skip_id as $key=>$id) {
		if($rowB3['shareto_id']==$id) $found=true;
	}
	if($found==false && $rowB3['shareto_id']!=$GV_id)  $skip_id[]=$rowB3['shareto_id'];
}
$FriendResult = mysql_query("SELECT * FROM view_permission WHERE user_id=$GV_id and viewer_id<> $Curowner_id and viewer_id<> $_SESSION[sharefm_id] and is_active>0 group by viewer_id");
while($option = mysql_fetch_array($FriendResult)) {
	$found=false;
	if(isset($skip_id)) {
		if (in_array($option['viewer_id'], $skip_id)) {
			$found=true;
		}
	}	
	if($found==false) {
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
			$shareResult = mysql_query("SELECT * FROM pv_share WHERE pv_id=$_SESSION[this_id] and shareto_id=$option[viewer_id] limit 1");
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
				$shareResult = mysql_query("SELECT * FROM pv_share WHERE pv_id=$_SESSION[this_id] and shareto_id=$option[viewer_id] limit 1");
				if(mysql_num_rows($shareResult) != 0) $shareFlag[]="checked='checked'";
				else $shareFlag[]="";
			}
		}
	}
}
$Sharepagenum=1;
$Sharepage_rows = 12;  
$Sharelast = ceil($Sharerows/$Sharepage_rows); 
if ($Sharepagenum < 1) $Sharepagenum = 1; elseif ($Sharepagenum > $Sharelast) $Sharepagenum = $Sharelast; 
$Sharefirst_row=($Sharepagenum -1) * $Sharepage_rows;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Shared Picture</title>
	<script type="text/javascript" src="../scripts/slider.js"></script>
	<link href="../css/default.css" rel="stylesheet" type="text/css">
	
<style type="text/css">
 img {border: 5px grey double;}
 .pointer { cursor: pointer }
	/* Stylesheet for sliders (only properties that differ from default) */
	#horizontal_slider_1,#horizontal_slider_2,#horizontal_slider_3,#horizontal_slider_4,
	#horizontal_slider_5,#horizontal_slider_6,#horizontal_slider_7,#horizontal_slider_8,#horizontal_slider_9,
	#horizontal_slider_10,#horizontal_slider_11,#horizontal_slider_12,#horizontal_slider_13,#horizontal_slider_14,#horizontal_slider_15 {
		background-color: #696;
		border-color: #9c9 #363 #363 #9c9;
		}
	#horizontal_track_1,#horizontal_track_2,#horizontal_track_3,#horizontal_track_4,#horizontal_track_5,#horizontal_track_6,#horizontal_track_7,#horizontal_track_8,#horizontal_track_9,#horizontal_track_10,#horizontal_track_11,#horizontal_track_12,#horizontal_track_13,#horizontal_track_14,#horizontal_track_15,
	#display_holder_1,#display_holder_2,#display_holder_3,#display_holder_4,#display_holder_5,#display_holder_6,#display_holder_7,#display_holder_8,#display_holder_9,#display_holder_10,#display_holder_11,#display_holder_12,#display_holder_13,#display_holder_14,#display_holder_15 {
		background-color: #bdb;
		border-color: #ded #9a9 #9a9 #ded;
		}
	#horizontal_slit_1,#horizontal_slit_2,#horizontal_slit_3,#horizontal_slit_4,#horizontal_slit_5,#horizontal_slit_6,#horizontal_slit_7,#horizontal_slit_8,#horizontal_slit_9,#horizontal_slit_10,#horizontal_slit_11,#horizontal_slit_12,#horizontal_slit_13,#horizontal_slit_14,#horizontal_slit_15 {
		background-color: #232;
		border-color: #9a9 #ded #ded #9a9;
		}
	#Contrast, #Smoothvalue, #Brightness, #Pixelate,#red,#green,#blue,#resize,#degrees,#radius,#fontsize,#textrotate,#FontR,#FontG,#FontB {
		background-color: #bdb;
		color: #363;
		}
	.pointer { cursor: pointer } 
</style>

</head>
<body>
<center>
<?php //if(isset($name)) echo strtoupper($name); ?>
<?php include("../PHP/Header.php"); ?>
<div style="width:160px;display:inline-block;vertical-align:top;">
<table name="mytable" style="border-style: solid;border-color:#0000ff;border-width: 3px;" id="Result">
<tr>
	<td colspan="3" style="border-style: solid;border-color:#0000ff;border-width: 3px;text-align:center;" >
		<?php
		echo "<input type='image' src=\"../images/first2.png\" id='Sharefirst' onClick=\"SharingList('first');\">";
		echo " ";
		echo "<input type='image' src=\"../images/previous2.png\" id='Shareprevious'  onClick=\"SharingList('previous');\">";
		echo "<input type='image' src=\"../images/next1.png\" id='Sharenext' onClick=\"SharingList('next');\">";
		echo " ";
		echo "<input type='image' src=\"../images/last.png\" id='Sharelast'  onClick=\"SharingList('last');\">";
		?>	
	</td>
</tr>
<tr>
	<td colspan="3" style="border-style: solid;border-color:#0000ff;border-width: 3px;" >
	<input type="checkbox" name="All" id="All" value="All"  onChange="Share(this.id,1);">Share to All
	</td>
</tr>
<tr>
<td>
<table width="100%" id="ShareList">
<?php
if(isset($optionViewer_id)) {
	$listcount=0;
	foreach ($optionViewer_id as $id => $friendeID) {
	$listcount++;
	if($listcount > $Sharefirst_row && $listcount <= ($Sharefirst_row+$Sharepage_rows)){
		$name=substr($FirstName[$id]." ".$LastName[$id],0,15);
		echo "<tr>
			<td  style='width:15px;'>";
		echo "<input type='checkbox' value='$id' id='$friendeID' $shareFlag[$id] onChange='Share(this.id,0);' />";
		echo "
			</td>
			<td  style='border-style: solid;border-width: 1px;text-align:center;width:50px;'>
			<img src=\"$optionPicture[$id]\" width='40px' /> 
			</td>";
		echo "	
			<td  style='font-size:10px;border-style: solid;border-width: 1px;text-align:center;'>
			$name
			</td></tr>";
		}
	}
}
if(!isset($_SESSION['thisPicture'])) $_SESSION['thisPicture']=$OrgPicture[0];
?>
</table>
</td>
</tr>
</table>
</div>
<div style="width:650px;display:inline-block;text-align:right;">
<div id="loadimg">
<img src="<?php echo $_SESSION['thisPicture']; ?>" width="640" id="profile">
</div>
<form name='MyForm' action="" enctype='application/x-www-form-urlencoded' method='post'>
<?php
		foreach($_REQUEST as $name => $value) {
			if($name!="pv_id" && $name!="comment")  echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
		}
?>
<input type="hidden" name="thisPicture" id="thisPicture" size="300" value="<?php echo $_SESSION['thisPicture']; ?>">
<input type="hidden" name="pv_id" id="pv_id" value="<?php echo $_SESSION['this_id']; ?>">
<input type="hidden" name="sharefm_id" id="sharefm_id" value="<?php echo $_SESSION['sharefm_id']; ?>">
Comment: <input type='text' name='comment' value=''  style="font-size:18px;width:560px;border-color:#5050FF;border-width: 3px;" onfocus="stop_show();" onblur="start_show();">
</form>
<?php
	echo "<div id=\"ComMain\" style=\"width:650px;background-color:#E9FFFF;height:270px;text-align:left;\">";
		echo "<div id=\"ComNav\" style=\"width:17px;display:inline-block;\">";
			echo "<img src=\"../images/first_up2.png\" id='first' onClick=\"CommentList('first',\"SharedPicture\");\" style=\"border:none;\">";
			echo "<img src=\"../images/previous_up2.png\" id='previous'  onClick=\"CommentList('previous',\"SharedPicture\");\" style=\"border:none;\">";
			echo "<img src=\"../images/next_up.png\" id='next' onClick=\"CommentList('next',\"SharedPicture\");\" style=\"border:none;\">";
			echo "<img src=\"../images/last_up.png\" id='last'  onClick=\"CommentList('last',\"SharedPicture\");\" style=\"border:none;\">";
		echo "</div>";
		echo '<div id="comments" style="width:580px;display:inline-block;vertical-align:top;font-size:28px;text-align:left;">';
		if(isset($comments)) {
			$listcount=0;
			foreach ($comments as $key => $comment) {
				$listcount++;
				if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
					echo "<div>";
						echo '<div style="display:inline-block;">';
							echo "<img src=\"$result_profile_picture[$key]\" height=\"38\" style=\"border:none;\">";
						echo "</div>";
						echo $comment;
					echo "</div>";
				}
			}
		}
		echo "</div>";
	echo "</div>";

?>	
</div>
<div style="width:180px;display:inline-block;vertical-align:top;text-align:right;">
<?php require_once 'FilterSel.php'; ?>
 <?php
 if (isset($pictures)){
	foreach($pictures as $key=>$picture) {
	   echo "<img src='$picture' width='80' onClick='Action(\"$OrgPicture[$key]\",$pv_id[$key],\"SharedPicture\")' class='pointer'/>";
	}
}
unset($_SESSION['this_id']);
unset($_SESSION['thisPicture']);
?>
</div>

</center>
<div id='BlankMsg' style="display:none;"></div>
<iframe src="chat.php" height="380" width="645" id="ChatFrame" frameborder=0 SCROLLING=no allowTransparency="false" style="position:fixed;bottom:0px;right:0px;z-index:3;background-color:#FFFFFF;display:block;">
  <p>Your browser does not support iframes.</p>
</iframe>
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script type="text/javascript" src="../scripts/ImgFilter.js"></script>
<script type="text/javascript">
var user_id = "<?php echo $GV_id; ?>";
var Sharepagenum = 1;
var Sharelast = <?php echo $Sharelast; ?>;
var Sharerows = <?php echo $Sharerows; ?>;
var Sharepage_rows = <?php echo $Sharepage_rows; ?>;

function Action(picture,pv_id,upload_id) {
//	document.getElementById("filename").value=picture;
	document.getElementById("profile").src=picture;
	document.getElementById("pv_id").value=pv_id;
	document.getElementById("thisPicture").value=picture;
	document.getElementById("All").checked=false;
	var url = 'PicCommentList2.php?pv_id='+pv_id+'&upload_id='+upload_id+'&pagenum=1';
	$(document).ready(function() {
	   $("#ComMain").load(url);
	   $.ajaxSetup({ cache: false });
	});
	Sharepagenum=1;
	var pv_id=document.getElementById('pv_id').value;
	var url = 'ShareList.php?user_id='+user_id+'&Sharepagenum='+Sharepagenum+'&pv_id='+pv_id;
	$(document).ready(function() {
	   $("#ShareList").load(url);
	   $.ajaxSetup({ cache: false });
	});	
}
function Share(shareto_id,flag) {
	var answer = confirm("Please click \"OK\" to confirm")
	var success = true;
	if (answer){
		var pv_id=document.getElementById("pv_id").value;
		if(document.getElementById(shareto_id).checked)	var share=1;
		else var share=0;
		$.ajax({ 
		   type: "POST", 
		   url: "UpdateShare.php",
		   data: "pv_id="+pv_id+"&user_id="+user_id+"&shareto_id="+shareto_id+"&flag="+flag+"&share="+share, 
			error:function (xhr, ajaxOptions, thrownError){ 
				alert(xhr.status); 
				alert(thrownError);
				success	=false;
			}     	   
		 });
		if(success){
		alert( "Required have been successfully set" );
		Sharepagenum=1;
		var pv_id=document.getElementById('pv_id').value;
		var url = 'ShareList.php?user_id='+user_id+'&Sharepagenum='+Sharepagenum+'&pv_id='+pv_id;
		$(document).ready(function() {
		   $("#ShareList").load(url);
		   $.ajaxSetup({ cache: false });
		});	
		}
	}
	else{
		document.getElementById(shareto_id).checked=!document.getElementById(shareto_id).checked;
	}

}

if(Sharerows<=Sharepage_rows) {
	document.getElementById('Sharefirst').style.display = "none";
	document.getElementById('Shareprevious').style.display = "none";
	document.getElementById('Sharenext').style.display = "none";
	document.getElementById('Sharelast').style.display = "none";
}
function SharingList(require)  
{  
	if(require=='first') Sharepagenum=1;
	else if(require=='previous') {
	  Sharepagenum = Sharepagenum - 1;
	} 
	else if(require=='next') {
	  Sharepagenum = Sharepagenum + 1;
	} 
	else if(require=='last') Sharepagenum=Sharelast;
	if(Sharepagenum > 1 && Sharepagenum < Sharelast) {
		document.getElementById('Sharefirst').src = "../images/first.png";
		document.getElementById('Shareprevious').src = "../images/previous.png";
		document.getElementById('Sharenext').src = "../images/next1.png";
		document.getElementById('Sharelast').src = "../images/last.png";
	}
	else if(Sharepagenum<=1) {
		Sharepagenum = 1;
		document.getElementById('Sharefirst').src = "../images/first2.png";
		document.getElementById('Shareprevious').src = "../images/previous2.png";
		document.getElementById('Sharenext').src = "../images/next1.png";
		document.getElementById('Sharelast').src = "../images/last.png";
	}
	 else if(Sharepagenum>=Sharelast) {
		Sharepagenum = Sharelast;
		document.getElementById('Sharefirst').src = "../images/first.png";
		document.getElementById('Shareprevious').src = "../images/previous.png";
		document.getElementById('Sharenext').src = "../images/next2.png";
		document.getElementById('Sharelast').src = "../images/last2.png";
	 }
	var pv_id=document.getElementById('pv_id').value;
	var url = 'ShareList.php?user_id='+user_id+'&Sharepagenum='+Sharepagenum+'&pv_id='+pv_id;
	$(document).ready(function() {
	   $("#ShareList").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}

var pagenum = 1;
var last = <?php echo $last; ?>;
var Comcount = <?php echo $Comcount; ?>;
var page_rows = <?php echo $page_rows; ?>;

if(Comcount<=page_rows) {
	if(document.getElementById('ComNav')) document.getElementById('ComNav').style.display = "none";
	if(document.getElementById('first')) document.getElementById('first').style.display = "none";
	if(document.getElementById('previous')) document.getElementById('previous').style.display = "none";
	if(document.getElementById('next')) document.getElementById('next').style.display = "none";
	if(document.getElementById('last')) document.getElementById('last').style.display = "none";
}

function CommentList(require,upload_id)  
{  
	var viewer_id =  <?php echo $GV_id ?>;
	var pv_id = document.getElementById('pv_id').value;
	if(require=='first') pagenum=1;
	else if(require=='previous') {
	  pagenum = pagenum - 1;
	} 
	else if(require=='next') {
	  pagenum = pagenum + 1;
	} 
	else if(require=='last') pagenum=last;

	if(pagenum > 1 && pagenum < last) {
		document.getElementById('first').src = "../images/first_up.png";
		document.getElementById('previous').src = "../images/previous_up.png";
		document.getElementById('next').src = "../images/next_up.png";
		document.getElementById('last').src = "../images/last_up.png";
	}
	else if(pagenum<=1) {
		pagenum = 1;
		document.getElementById('first').src = "../images/first_up2.png";
		document.getElementById('previous').src = "../images/previous_up2.png";
		document.getElementById('next').src = "../images/next_up.png";
		document.getElementById('last').src = "../images/last_up.png";
	}
	 else if(pagenum>=last) {
		pagenum = last;
		document.getElementById('first').src = "../images/first_up.png";
		document.getElementById('previous').src = "../images/previous_up.png";
		document.getElementById('next').src = "../images/next_up2.png";
		document.getElementById('last').src = "../images/last_up2.png";
	 }
	var url = 'PicCommentList.php?pv_id='+pv_id+'&upload_id='+upload_id+'&pagenum='+pagenum;
	$(document).ready(function() {
	   $("#comments").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}
</script>
<script src="../scripts/chat.js"></script>

</body>
</html>