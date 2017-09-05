<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ShowVideos extends MY_Controller {
	public function index()
	{
		$video = $this->input->get('video');
		if($this->input->get('VideoWidth')) $VideoWidth=$this->input->get('VideoWidth'); 
		else $VideoWidth = 320;
		$video1=$video."mp4";
		$video2=$video."ogg";
		$video3=$video."ogv";
		$video4=$video."webm";
		$videoswf=$video.".swf";
		$str = <<<END
		<video id="OnShow" width="$VideoWidth" controls="controls" onClick="VideoShow('$video')">
		  <source src="$video1" type="video/mp4" />
		  <source src="$video2" type="video/ogg" />
		  <source src="$video3" type="video/ogv" />
		  <source src="$video4" type="video/webm" />
			<OBJECT id=NSPlay classid=CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95 align=middle width=320 codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0'>
			<PARAM NAME='fileName' VALUE='$videoswf'> 
			<PARAM NAME='autoStart' VALUE='0'>
			<PARAM NAME='BufferingTime' value='0'>
			<PARAM NAME='CaptioningID' value=''> 
			<PARAM NAME='ShowControls' value='1'>
			<PARAM NAME='ShowAudioControls' value='1'>
			<PARAM NAME='ShowGotoBar' value='0'>
			<PARAM NAME='ShowPositionControls' value='0'>
			<PARAM NAME='ShowStatusBar' value='-1'>
			<PARAM NAME='EnableContextMenu' value='0'>
			<embed src='$videoswf' menu='true' quality='high' bgcolor='#FFFFFF' width='320' name='player' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'/> 
			</OBJECT>
		  Your browser does not support the video tag.
		</video>
END;
		echo $str;
		
//		$this->load->view("showVideos_view",$data);
	}
}
