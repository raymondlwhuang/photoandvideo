<div id='header'>
	<input type="image" src="/images/home.png" name="Home" id="Home" title="Home" value="Home" width="40" onClick="window.open('/',target='_top');">
	<input type="image" src="/images/sharing.jpg" name="sharing" id="sharing" title="sharing" value="sharing" width="40" onclick="window.open('SetSharing',target='_top');">
	<input type="image" src="/images/setup.jpg" name="Setup" id="Setup" id="Setup" title="Setup" width="40" onClick="window.open('PickFriend',target='_top');">
	<input type="image" src="/images/pictureupload.jpg" name="PUpload" id="PUpload" title="Upload Picture" value="Picture Upload" width="40" onClick="window.open('FileUploader',target='_top');">
	<input type="image" src="/images/videoupload.png" name="VUpload" id="VUpload" title="Upload Video" value="Video Upload" width="40" onClick="window.open('UploadVideo',target='_top');">
	<input type="image" src="/images/deletepic.png" name="DeleteP" id="DeleteP" title="Delete Picture" value="Delete Picture" width="40" onClick="window.open('PRemove',target='_top');">
	<input type="image" src="/images/deletevideo.png" name="DeleteV" id="DeleteV" title="Delete Video" value="Delete Video" width="40" onClick="window.open('VRemove',target='_top');">
	<input type="image" src="/images/HERecorder.jpg" name="HERecorder" id="HERecorder" title="Household Expense Recorder" value="Household Expenese" width="40" onclick="window.open('HERecorder',target='_top');">
	<input type="image" src="/images/profile.png" name="Profile" id="Profile" title="Profile" value="Profile" width="40" onclick="window.open('ChangeProfile',target='_top');">
	<object type="application/x-shockwave-flash" data="/images/player_mp3_maxi.swf" id="audio" width="40" height="40">
		<param name="movie" value="/images/player_mp3_maxi.swf" />
		<param name="bgcolor" value="#000000" />
		<param name="FlashVars" value="mp3=/images/Cherries.mp3&amp;width=40&amp;height=40&amp;showslider=0&amp;sliderwidth=0&amp;sliderheight=0&amp;volumewidth=0&amp;volumeheight=0" />
	</object>
	<input type="submit" class="submiting" value="Introduction" onClick="window.open('Introduction',target='_top');return false;">
	<!---
	<?php if($layout!='tablet' && $layout!='mobile') : ?>
		<input type="image" src="/images/chat.png" name="start_chat" id="Chat" value="Chat" width="40" onClick="chat();">
	<?php else: ?>
		<input type="image" src="/images/chat.png" name="start_chat" id="Chat" value="Chat" width="40"  onClick="window.open('chat',target='_top');">
	<?php endif; ?>
	<div style='display:inline-block'>
	<font style="font-size:16px;color:blue;" id="Available">
	<div style='display:inline-block'><font style="font-size:8px;color:green;">0 Friend Online</font>
	<font id="AvailMsg"></font>
	</div>
	</font><font style="font-size:20px;color:red;font-weight:bold;"><?php if(isset($message)) echo $message ?></font>
	</div>
	--->
	<input type="image" src="/images/logout.png" name="Logout" id="Logout" title="Logout" value="Logout" width="40" style="float:right;" onClick="window.open('Signout',target='_top');"><hr/>
</div>