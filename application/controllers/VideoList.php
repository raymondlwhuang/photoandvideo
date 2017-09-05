<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VideoList extends MY_Controller {
	public function index()
	{
		$this->load->model('upload_infor');
		if($this->input->get('pagenum')) $pagenum=$this->input->get('pagenum'); 
		else $pagenum = 1; 
		if($this->input->get('page_rows')) $page_rows=$this->input->get('page_rows'); 
		else $page_rows = 1;  
		$max = 'limit ' .($pagenum - 1) * $page_rows .',' .$page_rows; 
		$offset=($pagenum - 1) * $page_rows;
		if($this->input->get('VideoWidth')) $VideoWidth=$this->input->get('VideoWidth'); 
		else $VideoWidth = 320;  
		if($this->input->get('FriendID'))
		{
			$FriendID=$this->input->get('FriendID');
			$viewer_id=$this->input->get('viewer_id');
			$getviewer=$this->general_model->_get(array('table'=>'user','id'=>$FriendID));
			if($getviewer){
				foreach($getviewer as $vieweresult) {
					$viewer_path=$vieweresult->owner_path;
					$viewer_email_address=$vieweresult->email_address;
					$user_id=$vieweresult->id;
				}
			}
			if($this->input->get('show_id') && $this->input->get('show_id')!='')	$show_id=$this->input->get('show_id');
			if($FriendID == 'Public') {
				$Picked['Public'] = 'Public';
				$GetSomething="both";
			}
			else if($FriendID == $viewer_id) {
					$GetSomething='';
					$user_id=$viewer_id;
					$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$viewer_path));
					if($GetSomething=='') $Picked['Public'] = 'Public';	
			
					$queryPermission=$this->general_model->_get(array('table'=>'view_permission','owner_email'=>$viewer_email_address,'groupBy'=>'viewer_group'));
					if($queryPermission){
						foreach($queryPermission as $row)
						{
						 $viewer_group = $row->viewer_group;
						 $viewer_path = $row->owner_path;
						 $Picked[$viewer_path] = $viewer_group;
						}
					}
				}	
			else {
					$GetSomething='';
					$getowner=$this->general_model->_get(array('table'=>'user','id'=>$FriendID));
					if($getowner){
						foreach($getowner as $owneresult) {
							$result_path=$owneresult->owner_path;
							$user_id=$owneresult->id;
						}
					}
					$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$result_path));
					if($GetSomething=='') $Picked['Public'] = 'Public';	
			
					$resultPermission=$this->general_model->_get(array('table'=>'view_permission','user_id'=>$FriendID,'viewer_id'=>$viewer_id,'sortBy'=>'owner_path'));
					if($resultPermission){
						foreach($resultPermission as $row)
						{
						 $viewer_group = $row->viewer_group;
						 $owner_path = $row->owner_path;
						 $Picked[$owner_path] = $viewer_group;
						}
					}
				}
		}
		else {
			$FriendID=$viewer_id;
			$resultPermission=$this->general_model->_get(array('table'=>'view_permission','owner_email'=>$viewer_email_address,'groupBy'=>'viewer_group'));
			if($resultPermission){
				foreach($resultPermission as $row)
				{
				 $viewer_group = $row->viewer_group;
				 $viewer_path = $row->owner_path;
				 $Picked[$viewer_path] = $viewer_group;
				}
			}
			$GetSomething=$this->picture_video->GetPV(array('owner_path'=>$viewer_path));
			if($GetSomething=='') $Picked['Public'] = 'Public';
		}
		$videocount = 0;

		if(isset($Picked)) {
			foreach ($Picked as $key => $value) {
				if($key =='Public') {
					$result=$this->upload_infor->getvideo(array('viewer_group'=>'Public','limit'=>$page_rows,'offset'=>$offset));
				}
				else {
					$getowner2=$this->general_model->_get(array('table'=>'user','owner_path'=>$key));
					if($getowner2){
						foreach($getowner2 as $owneresult2) {
							$user_id1=$owneresult2->id;
						}
						$result=$this->upload_infor->get_private_video($value,$user_id1);
					}					
				}
				foreach($result as $row2)
				{
					$video[]=$row2->name.".";
					$VDupload_id[]=$row2->id;
				};
			}
		}


		if(isset($video)) {
			foreach ($video as $key => $value) {
				echo "
				<video id='OnShow' width='320' controls='controls' onClick='VideoShow($VDupload_id[$key]);'>
				  <source src='$video[$key]mp4' type='video/mp4' />
				  <source src='$video[$key]ogg' type='video/ogg' />
				  <source src='$video[$key]ogv' type='video/ogv' />
				  <source src='$video[$key]webm' type='video/webm' />
				<OBJECT id=NSPlay classid=CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95 align=middle width=320 codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0'>
				<PARAM NAME='fileName' VALUE='$video[$key]swf'> 
				<PARAM NAME='autoStart' VALUE='0'>
				<PARAM NAME='BufferingTime' value='0'>
				<PARAM NAME='CaptioningID' value=''> 
				<PARAM NAME='ShowControls' value='1'>
				<PARAM NAME='ShowAudioControls' value='1'>
				<PARAM NAME='ShowGotoBar' value='0'>
				<PARAM NAME='ShowPositionControls' value='0'>
				<PARAM NAME='ShowStatusBar' value='-1'>
				<PARAM NAME='EnableContextMenu' value='0'>
				<embed src='$video[$key]swf' menu='true' quality='high' bgcolor='#FFFFFF' width='320' name='player' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'/> 
				</OBJECT>
				</video>";
			}    
		}
	}
}
