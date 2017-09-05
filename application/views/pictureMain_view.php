<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html class="cufon-active cufon-ready">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Main</title>
<?php echo link_tag('css/picturemain.css'); ?>
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.js"></script>
</head>
<body onload="parent.alertsize($(document).height(),1);">
<div id="pictures">
		<?php
		if($upload_id!="SharedPicture" && $FriendID!="Temporary") echo "<div id=\"show\" onClick=\"window.open('".base_url()."CommentPicture?owner_id=$FriendID&viewer_group=$markupviewer_group&upload_id=$upload_id&viewingOn=$FriendID',target='_top');\">";
		elseif($pictureCount>0 && $FriendID=="Temporary")  echo "<div id=\"show\" >";
		elseif($pictureCount>0 && $FriendID!="Temporary")  echo "<div id=\"show\" onClick=\"window.open('".base_url()."ComSharePic',target='_top');\">";
		echo $markup."</div>"; 
		?>
		
		<?php if($FriendID!="Temporary") : ?>
		<div id="PicCtrl">
		<a id="prev2" href="#" onclick="backforward2();return false;"><input type="image" src="/images/backward.png" id="backward"></a>
		<input type="image" src="/images/play.png" id='play'  onClick="start_show();">
		<?php 
			if($pictureCount>0) {
				if($upload_id!="SharedPicture") echo "<input type=\"image\" src=\"/images/to_view.png\" id='to_view' onClick=\"window.open('".base_url()."CommentPicture?owner_id=$FriendID&viewer_group=$markupviewer_group&upload_id=$upload_id&viewingOn=$FriendID',target='_top');\">";
				else echo "<input type=\"image\" src=\"/images/to_view.png\" id='to_view' onClick=\"window.open('ComSharePic',target='_top');\">";
			}
		?>
		<input type="image" src="/images/stop.png" id='stop' onClick="stop_show();">
		<a id="next2" href="#" onclick="backforward2();return false;"><input type="image" src="/images/forward.png" id="forward"></a>
		</div>		
		<?php endif; ?>
		<?php if(isset($CurrComment) && $upload_id!="SharedPicture" && $FriendID!="Temporary") : ?>
			<div id="PicNav">
			<input type='image' src="/images/first_up2.png" id='Picturefirst' onClick="CommentList2('first',<?php echo $upload_id;?>,0);"><br/>
			<input type='image' src="/images/previous_up2.png" id='Pictureprevious'  onClick="CommentList2('previous',<?php echo $upload_id;?>,0);"><br/>
			<input type='image' src="/images/next_up.png" id='Picturenext' onClick="CommentList2('next',<?php echo $upload_id;?>,0);"><br/>
			<input type='image' src="/images/last_up.png" id='Picturelast'  onClick="CommentList2('last',<?php echo $upload_id;?>,0);">
			</div>
			<div class="Comment">
			<div id="CurrComment">
			<?php
					$listcount=0;
					foreach ($CurrComment as $key2 => $comment2) {
						$listcount++;
						if($listcount > $Picturefirst_row && $listcount <= ($Picturefirst_row+$Picturepage_rows)){
							echo "<div id=\"profile\"><img src=\"$Currprofile_picture[$key2]\" height=\"37\"></div>";
							echo "<div id=\"comment2\">".$comment2."</div><br/>";
						}
					}
			?>
			</div>
			</div>
		<?php endif;
		if($pictureCount!=0 && $upload_id!="SharedPicture" && $FriendID!="Temporary"){
			if($this->session->userdata('id')) echo "<div>";
			else  echo "<div class=\"nodisp\">";
			echo "<form name=\"MyForm\" enctype=\"application/x-www-form-urlencoded\" method=\"post\" onsubmit=\"if(document.getElementById('comment').value=='') return false;\">";
			echo "<input type=\"hidden\" name=\"ViewingID\" value=\"$FriendID\">";
			echo "<input type=\"hidden\" name=\"upload_id\" value=\"$upload_id;\">";
			echo "<font class=\"whitefont\">Comment:</font> <input type=\"text\" name=\"comment\" id=\"comment\" size=\"80\" value=\"\" onfocus=\"stop_show();\" onblur=\"start_show();\">";
			echo "</form></div>";
		}
		?>
</div>
	<div id="PictureOwnerComment">
	<?php if(isset($comments) && $FriendID!='Temporary'): ?>
		<div class="combg">
		<div class="block">
		<img src="$result_profile_picture" height="78"></div>
		<div id="OwnerComNav"><input type='image' src="/images/first_up2.png" id='Ownerfirst' onClick="CommentList('first',$FriendID,0);"><br/>
		<input type='image' src="/images/previous_up2.png" id='Ownerprevious'  onClick="CommentList('previous',$FriendID,0);"><br/>
		<input type='image' src="/images/next_up.png" id='Ownernext' onClick="CommentList('next',$FriendID,0);"><br/>
		<input type='image' src="/images/last_up.png" id='Ownerlast'  onClick="CommentList('last',$FriendID,0);"></div>
		<div id="comments">
		<?php
		$listcount=0;
		foreach ($comments as $key => $comment) {
			$listcount++;
			if($listcount > $Ownerfirst_row && $listcount <= ($Ownerfirst_row+$Ownerpage_rows)){
				if($pic_group[$key]!='Public' && $owner_id[$key]!=$this->session->userdata('id')) {
					$get=$this->view_permission->_get1(array('user_id'=>$owner_id["$key"],'viewer_id'=>$this->session->userdata('id'),'viewer_group'=>$pic_group["$key"]));
					if($get) {
						echo "<input type=\"image\" src=\"/images/view.png\" name=\"view\" value=\"view\" width=\"16\" onClick=\"window.open('".base_url()."CommentPicture?owner_id=$owner_id[$key]&viewer_group=$pic_group[$key]&upload_id=$pic_upload_id[$key]&viewingOn=$FriendID',target='_top');\">";
					}
					else echo "&nbsp;&nbsp;&nbsp;&nbsp; ";

				}
				else {
					echo "<input type=\"image\" src=\"/images/view.png\" name=\"view\" value=\"view\" width=\"16\" onClick=\"window.open('".base_url()."CommentPicture?owner_id=$owner_id[$key]&viewer_group=$pic_group[$key]&upload_id=$pic_upload_id[$key]&viewingOn=$FriendID',target='_top');\">";
				}
				echo $comment;
			}
		}
		echo "</div></div>";
	endif;?>

	</div>

<?php
if($FriendID != 'Public' && $FriendID != 'Temporary') {
	if(isset($video)){
	echo '
	<div class="block">';
		if($upload_id!="SharedPicture")	echo "<input type='image' src=\"/images/videoShare.jpg\" width='40' onClick=\"window.open('CommentVideo?FriendID=$FriendID',target='_top');\"/>";
		else echo "<input type='image' src=\"/images/videoShare.jpg\" width='40' onClick=\"window.open('CommentVideo?FriendID=SharedPicture',target='_top');\"/>";
	}
	echo '
	</div>';
	}
?>
<div id="NextVideos">
<?php
if(isset($video)) {
	$listcount=0;
	foreach ($video as $key => $value) {
		$listcount++;
		if($listcount > $Videofirst_row && $listcount <= ($Videofirst_row+$Videopage_rows)){
			echo "
			<video id='OnShow' width='320' controls='controls' onClick='VideoShow($VDupload_id[$key]);'>
				<source id='mp4' src='$video[$key]mp4' type='video/mp4' />
				<source id='ogg' src='$video[$key]ogg' type='video/ogg' />
				<source id='ogv' src='$video[$key]ogv' type='video/ogv' />
				<source id='webm' src='$video[$key]webm' type='video/webm' />
				<OBJECT id=NSPlay classid=CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95 align=middle width=320 codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0'>
				<PARAM NAME='fileName'  id='object' VALUE='$video[$key]swf'> 
				<PARAM NAME='autoStart' VALUE='0'>
				<PARAM NAME='BufferingTime' value='0'>
				<PARAM NAME='CaptioningID' value=''> 
				<PARAM NAME='ShowControls' value='1'>
				<PARAM NAME='ShowAudioControls' value='1'>
				<PARAM NAME='ShowGotoBar' value='0'>
				<PARAM NAME='ShowPositionControls' value='0'>
				<PARAM NAME='ShowStatusBar' value='-1'>
				<PARAM NAME='EnableContextMenu' value='0'>
				<embed  id='embed' src='$video[$key]swf' menu='true' quality='high' bgcolor='#FFFFFF' width='320' name='player' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'/> 
				</OBJECT>
			</video>";
		}	
	}    
}
?>
</div>	
<div id="VideoNav">
<input type='image' src="/images/first2.png" id='Videofirst' onClick="VideoList('first','<?php echo $FriendID; ?>');">
<input type='image' src="/images/previous2.png" id='Videoprevious'  onClick="VideoList('previous','<?php echo $FriendID; ?>');">
<input type='image' src="/images/next1.png" id='Videonext' onClick="VideoList('next','<?php echo $FriendID; ?>');">
<input type='image' src="/images/last.png" id='Videolast'  onClick="VideoList('last','<?php echo $FriendID; ?>');"><br/>
</div>
<div id="VDComment">
<?php if(isset($CurrVideoComment)) : ?>
	<div class="PicNav" style="height:<?php echo $height; ?>">
	<input type='image' src="/images/first_up2.png" id='VideoComfirst' onClick="CommentList3('first',video_id,1,0);"><br/>
	<input type='image' src="/images/previous_up2.png" id='VideoComprevious'  onClick="CommentList3('previous',video_id,1,0);"><br/>
	<input type='image' src="/images/next_up.png" id='VideoComnext' onClick="CommentList3('next',video_id,1,0);"><br/>
	<input type='image' src="/images/last_up.png" id='VideoComlast'  onClick="CommentList3('last',video_id,1,0);">
	</div>
	<div class="PicNavbg" style="height:<?php echo $height; ?>">
		<div id="CurrVideoComment">
				<?php
				foreach ($CurrVideoComment as $key2 => $comment2) {
					$listcount++;
					if($listcount > $VideoComfirst_row && $listcount <= ($VideoComfirst_row+$VideoCompage_rows)){
						echo "<div class=\"block\"><img src=\"$CurrVideoprofile_picture[$key2]\" height=\"37\"></div>";
						echo "<div class=\"block\">".$comment2."</div><br/>";
					}
				}				
		echo "</div>";
	echo "</div>";
 endif; ?>
</div>
<?php
if(isset($video)) {
	if(!isset($video_id))  $video_id="";
	if($this->session->userdata('id')) echo "<div>";
	else  echo "<div class='nodisp'>";
	echo "<form name=\"VideoForm\" id=\"VideoForm\" action=\"PictureMain\" enctype='application/x-www-form-urlencoded' method='post' onsubmit=\"if(document.getElementById('VideoComment').value=='') return false;\">";
	echo "<input type='hidden' name='ViewingID' value=\"$FriendID\">";
	echo "<input type='hidden' name='video_id' id='video_id' size='80' value=\"$video_id\"><br/>";
	if($this->session->userdata('video_name')) echo "<input type='hidden' name='video_name' id='video_name' size='80' value=\"$this->session->userdata('video_name')\"><br/>";
	else echo "<input type='hidden' name='video_name' id='video_name' size='80' value=\"$video[0]\"><br/>";
	echo "<font class=\"greenfont\">Comment:</font> <input type='text' name='VideoComment' id='VideoComment' size='80' value=''>";
	echo "</form></div>";
}	
if($FriendID!="Public" && $FriendID!="SharedPicture" && $FriendID!="Temporary") {
	echo "<div id=\"info\">";
	echo "SOME INFORMATION ABOUT THIS PERSON<br/>Screen:";
	foreach($UserScreen as $rowScreen) {
		echo "<font color='blue'>$rowScreen->screen_width X $rowScreen->screen_height</font>";
		echo " available: <font color='blue'>$rowScreen->screen_availWidth X $rowScreen->screen_availHeight</font>";
		echo " colorDepth: <font color='blue'>$rowScreen->screen_colorDepth</font>";
		echo " pixelDepth: <font color='blue'>$rowScreen->screen_pixelDepth</font><br/>";
	}
	echo "Browser been used:";
	foreach($UserBrowser as $rowBrowser) {
		echo "<font color='blue'>$rowBrowser->browser</font>, ";
	}
	foreach($UserOS as $rowOS) {
		echo " and run on <font color='blue'>$rowOS->os or up</font>";
	}
	if($UserLocation) {
		foreach($UserLocation as $rowLocation) {
			echo "<br/>Last login location: <font color='blue'>$rowLocation->city,$rowLocation->region,</font> with IP address: <font color='blue'>$rowLocation->ip_address</font>";
		}
	}
	echo "</div>";
}
if($this->session->userdata('video_id')) $this->session->unset_userdata('video_id');
if($this->session->userdata('video_name')) $this->session->unset_userdata('video_name');
?>

<!-- include Cycle plugin -->
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.cycle.all.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.easing.1.3.js"></script>
<script type="text/javascript">
var video=<?php echo json_encode($video); ?>;
var markup = '<?php echo $markup; ?>';
var pictureCount =<?php echo $pictureCount; ?>;
var Videopagenum = 0;
var Videolast = <?php echo $Videolast; ?>;
var Videocount = <?php echo $Videocount; ?>;
var Videopage_rows = <?php echo $Videopage_rows; ?>;

var VideoCompagenum = 1;
var VideoComlast = <?php echo $VideoComlast; ?>;
var VideoComcount = <?php echo $VideoComcount; ?>;
var VideoCompage_rows = <?php echo $VideoCompage_rows; ?>;

var Ownerpagenum = 1;
var Ownerlast = <?php echo $Ownerlast; ?>;
var OwnerComcount = <?php echo $OwnerComcount; ?>;
var Ownerpage_rows = <?php echo $Ownerpage_rows; ?>;

var Picturepagenum = 1;
var Picturelast = <?php echo $Picturelast; ?>;
var PictureComcount = <?php echo $PictureComcount; ?>;
var Picturepage_rows = <?php echo $Picturepage_rows; ?>;
var pictureCount = <?php echo $pictureCount; ?>;
var VideoWidth=320;
var video_id = "<?php if(isset($video_id)) echo $video_id; ?>";
var viewer_id =  "<?php if($this->session->userdata('id')) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary"; else echo "Public"; ?>";
var base_url="<?php echo base_url();?>";
var FriendID="<?php echo $FriendID; ?>";
if(pictureCount==0) {
	document.getElementById('PicCtrl').style.display = "none";
	<?php if(isset($video_id)){
		echo "VideoShow($video_id)";
	}
	?> 
}
<?php if($this->session->userdata('id')) : ?>
window.onload=parent.Set_OrgFriendID(FriendID);
<?php endif; ?>

</script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/picslide.js"></script>
</body>
</html>
		