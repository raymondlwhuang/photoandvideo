<center>
<font style="font-size:18px;color:red;">VIDEO MAINTENANCE</font><br/>
<?php
if(isset($video)){
	foreach ($video as $key1 => $value1) {
		$link = $this->function_model->newencode("$upload_id[$key1],$value1");
		$VideoName1=$value1."mp4";
		$VideoName2=$value1."ogg";
		$VideoName3=$value1."ogv";
		$VideoName4=$value1."webm";
		$videoswf=$value1."swf";
		echo "<div style='display:inline-block;'>
		<video width='134'>
		  <source src='$VideoName1' type='video/mp4' />
		  <source src='$VideoName2' type='video/ogg' />
		  <source src='$VideoName3' type='video/ogv' />
		  <source src='$VideoName4' type='video/webm' />
			<OBJECT id=NSPlay classid=CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95 align=middle width=320 codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0'>
			<PARAM NAME='fileName'  id='object' VALUE='$videoswf'> 
			<PARAM NAME='autoStart' VALUE='0'>
			<PARAM NAME='BufferingTime' value='0'>
			<PARAM NAME='CaptioningID' value=''> 
			<PARAM NAME='ShowControls' value='1'>
			<PARAM NAME='ShowAudioControls' value='1'>
			<PARAM NAME='ShowGotoBar' value='0'>
			<PARAM NAME='ShowPositionControls' value='0'>
			<PARAM NAME='ShowStatusBar' value='-1'>
			<PARAM NAME='EnableContextMenu' value='0'>
			<embed  id='embed' src='$videoswf' menu='true' quality='high' bgcolor='#FFFFFF' width='320' name='player' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'/> 
			</OBJECT>
		  Your browser does not support the video tag.
		</video><br/><a href='VRemove?link=$link'>Delete this video</a></div>";
	}
}
else {	
echo <<<_END
<script type="text/javascript">
alert("No more video file available to delete!");
</script>
_END;

}
?>
</center>
<div id='BlankMsg' style="display:none;"></div>
<!--
<iframe src="chat.php" height="380" width="645" id="ChatFrame" frameborder=0 SCROLLING=no allowTransparency="false" style="position:fixed;bottom:0px;right:0px;z-index:3;background-color:#FFFFFF;display:block;">
  <p>Your browser does not support iframes.</p>
</iframe>
-->
<script src="/scripts/jquery.js"></script>
<script type="text/javascript" >var user_id = "<?php echo $this->session->userdata('id'); ?>";</script>
<!--
<script src="/scripts/chat.js"></script>
-->
<script type="text/javascript" >
var admin = <?php echo $this->session->userdata('admin'); ?>;
if(admin==1) {
	document.getElementById('Setup').style.display = "none";
}
document.getElementById('DeleteV').style.display = "none";
</script>
</body>
</html>
	