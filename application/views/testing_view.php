<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html class="cufon-active cufon-ready">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Main</title>
<?php echo link_tag('css/picturemain.css'); ?>
<script type="text/javascript" src="<?php echo base_url();?>/scripts/jquery.js"></script>
<script type="text/javascript">
var video=<?php echo json_encode($video); ?>;
var markup = '<?php echo $markup; ?>';
var pictureCount =<?php echo $pictureCount; ?>;
var Videopagenum = 1;
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
function VideoList(require,FriendID)  
{  
	if(require=='first') Videopagenum=1;
	else if(require=='previous') {
	  Videopagenum = Videopagenum - 1;
	} 
	else if(require=='next') {
	  Videopagenum = Videopagenum + 1;
	} 
	else if(require=='last') Videopagenum=Videolast;
	if(Videopagenum > 1 && Videopagenum < Videolast) {
		document.getElementById('Videofirst').src = "/images/first.png";
		document.getElementById('Videoprevious').src = "/images/previous.png";
		document.getElementById('Videonext').src = "/images/next1.png";
		document.getElementById('Videolast').src = "/images/last.png";
	}
	else if(Videopagenum<=1) {
		Videopagenum = 1;
		document.getElementById('Videofirst').src = "/images/first2.png";
		document.getElementById('Videoprevious').src = "/images/previous2.png";
		document.getElementById('Videonext').src = "/images/next1.png";
		document.getElementById('Videolast').src = "/images/last.png";
	}
	 else if(Videopagenum>=Videolast) {
		Videopagenum = Videolast;
		document.getElementById('Videofirst').src = "/images/first.png";
		document.getElementById('Videoprevious').src = "/images/previous.png";
		document.getElementById('Videonext').src = "/images/next2.png";
		document.getElementById('Videolast').src = "/images/last2.png";
	 }		
/*
	var url = base_url+'VideoList?FriendID='+FriendID+'&viewer_id='+viewer_id+'&pagenum='+Videopagenum+'&page_rows='+Videopage_rows+'&VideoWidth='+VideoWidth;
	alert(url);
	$(document).ready(function() {
	   $("#NextVideos").load(url);
	   $.ajaxSetup({ cache: false });
	});	
*/
document.getElementById('mp4').src = video[Videopagenum]+"mp4";	
document.getElementById('ogg').src = video[Videopagenum]+"ogg";	
document.getElementById('ogv').src = video[Videopagenum]+"ogv";	
document.getElementById('webm').src = video[Videopagenum]+"webm";	
document.getElementById('object').src = video[Videopagenum]+"swf";	
document.getElementById('embed').src = video[Videopagenum]+"swf";	
alert(document.getElementById('mp4').src);
}

</script>

</head>
<body>


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
<script type="text/javascript" src="<?php echo base_url();?>/scripts/jquery.cycle.all.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/scripts/jquery.easing.1.3.js"></script>
</body>
</html>
		