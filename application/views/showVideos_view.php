<video id="OnShow" width="<?php echo $VideoWidth; ?>" controls="controls" onClick="VideoShow('<?php echo $video ?>')">
  <source src="<?php echo $video ?>mp4" type="video/mp4" />
  <source src="<?php echo $video ?>ogg" type="video/ogg" />
  <source src="<?php echo $video ?>ogv" type="video/ogv" />
  <source src="<?php echo $video ?>webm" type="video/webm" />
  <?php $videoswf=$video.'swf'; ?>
	<OBJECT id=NSPlay classid=CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95 align=middle width=320 codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0'>
	<PARAM NAME='fileName' VALUE='<?php echo $videoswf; ?>'> 
	<PARAM NAME='autoStart' VALUE='0'>
	<PARAM NAME='BufferingTime' value='0'>
	<PARAM NAME='CaptioningID' value=''> 
	<PARAM NAME='ShowControls' value='1'>
	<PARAM NAME='ShowAudioControls' value='1'>
	<PARAM NAME='ShowGotoBar' value='0'>
	<PARAM NAME='ShowPositionControls' value='0'>
	<PARAM NAME='ShowStatusBar' value='-1'>
	<PARAM NAME='EnableContextMenu' value='0'>
	<embed src='<?php echo $videoswf; ?>' menu='true' quality='high' bgcolor='#FFFFFF' width='320' name='player' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'/> 
	</OBJECT>		  
  Your browser does not support the video tag.
</video>
