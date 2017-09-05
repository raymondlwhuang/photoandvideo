<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UpdateShare extends MY_Controller {
	public function index()
	{
		$flag=$this->input->get('flag');
		$pv_id=$this->input->get('pv_id');
		$user_id=$this->input->get('user_id');
		$shareto_id=$this->input->get('shareto_id');
		$share=$this->input->get('share');
		if($this->input->get('is_video')) $is_video=$this->input->get('is_video');
		else $is_video = 0;
		$RequireResult=$this->general_model->_get(array('table'=>'user','id'=>$user_id));
		if($RequireResult){
			foreach($RequireResult as $Reqopt) {
				$ReqName=$Reqopt->first_name." ".$Reqopt->last_name;
			}
		}
		$todaydate = date("l, F j, Y, g:i a");
		$headers = "From: no-reply@raymondlwhuang.com\r\n";
		$headers .= "Reply-To: no-reply@raymondlwhuang.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$websit = $_SERVER['HTTP_HOST'];
		$inviter = newencode($user_id);
		$bodyText ='
		<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		 <html>
		 <head>
		 <style>
		 <!--
		 body, P.msoNormal, LI.msoNormal
		 {
		 background-position: top;
		 background-color: #336699;
		 margin-left:  10em;
		 margin-top: 1em;
		 font-family: "verdana";
		 font-size:   10pt;
		 font-weight:bold ;
		 color:    #000000;
		 }
		a {
		background-color: #DFB214;
		border: 3px #FDD032 outset;
		text-decoration: none;
		}
		 -->
		 </style>
		 </head>
		 <body> 
		';						
		$bodyText .= "<h1><b>From: $ReqName</b><h1><br/>";
		$bodyText .= "<h1>Please click accept to grain me to share one of your photo to my friend<br/>";
		$bodyText .= "Sent at: $todaydate</h1><br/><br/><br/>";
		$message = $bodyText;	
		$message .= "<br/><br/>LIKE TO SHARE YOU SOME PHOTOS<br/>";	
		$message .= "<br/><br/><a href='$websit/' style=\"background-color:#AFC7C7;border-style:outset;text-decoration: none;padding:5px;\">Click here to take a look at the picture</a><br/>";	
		$message .= '</body></html>';
		$PictureResult=$this->general_model->_get(array('table'=>'picture_video','id'=>$pv_id));
		if($PictureResult){
			foreach($PictureResult as $row) {
				$pv_name=$row->name;
			}
		}
		if($flag==1) {
			$FriendResult=$this->view_permission->Get_group(array('user_id'=>$user_id,'groupBy'=>'viewer_id','flag'=>0));
			if($FriendResult){
				foreach($FriendResult as $option) {
					$viewer_id=$option->viewer_id;
					if($share==1) {
						$beforeInsert=$this->general_model->_get(array('table'=>'pv_share','pv_id'=>$pv_id,'shareto_id'=>$viewer_id,'limit'=>1));
						if(!$beforeInsert) {
							$EmailResult =$this->general_model->_get(array('table'=>'view_permission','user_id'=>$user_id,'viewer_id'=>$viewer_id,'limit'=>1));
							if($EmailResult){
								foreach($EmailResult as $emailopt) {
									if($emailopt->share_flag==2) {
										$this->general_model->_add(array('table'=>'pv_share','pv_id'=>$pv_id,'sharefm_id'=>$user_id,'shareto_id'=>$viewer_id,'pv_name'=>$pv_name,'is_video'=>$is_video)); /* I am a viewer, the name is my name for display purpose*/
										mail($emailopt->viewer_email, "Picture sharing",  $message, $headers);
									}
									else {
										$this->general_model->_add(array('table'=>'pv_share','pv_id'=>$pv_id,'sharefm_id'=>$user_id,'shareto_id'=>$viewer_id,'pv_name'=>$pv_name,'is_video'=>$is_video,'accept'=>1)); /* I am a viewer, the name is my name for display purpose*/
										$infor=newencode("$pv_id,$user_id,$viewer_id,$pv_name");
										$invitee = newencode($emailopt->viewer_email);
										$bodyText .= "<a href='$websit/PHP/ShareReply.php?infor=$infor&accept=1' onClick=\"alert('Thank you. Your photo is been shared!');\">&nbsp;&nbsp;Accept&nbsp;&nbsp;</a>   <a href='$websit/PHP/ShareReply.php?infor=$infor&accept=0' onClick=\"alert('Thank you. Your photo will not be shared!');\">&nbsp;&nbsp;Decline&nbsp;&nbsp;</a><br/>";
										$bodyText .= "$ReqImg</body></html>";
										$link=$websit.substr($pv_name,3);
										$ReqImg="<a href=\"$link\">Click here to view your photo </a>";
										$bodyText .= "$ReqImg</body></html>";						
										mail($emailopt->viewer_email, "Sharing Required",  $bodyText, $headers);
									}
								}
							}
						}
					}
					else {
						$this->general_model->_delete(array('table'=>'pv_share','pv_id'=>$pv_id,'sharefm_id'=>$user_id,'shareto_id'=>$viewer_id,'limit'=>1));
					}
				}
			}
		}
		else {
			if($share==1) {
				$beforeInsert=$this->general_model->_get(array('table'=>'pv_share','pv_id'=>$pv_id,'shareto_id'=>$shareto_id,'limit'=>1));
				if(!$beforeInsert) {
					$EmailResult=$this->general_model->_get(array('table'=>'view_permission','user_id'=>$user_id,'viewer_id'=>$shareto_id,'limit'=>1));
					if($EmailResult){
						foreach($EmailResult as $emailopt) {
							if($emailopt->share_flag==2) {
								$this->general_model->_add(array('table'=>'pv_share','pv_id'=>$pv_id,'sharefm_id'=>$user_id,'shareto_id'=>$shareto_id,'pv_name'=>$pv_name,'is_video'=>$is_video)); /* I am a viewer, the name is my name for display purpose*/
								mail($emailopt->viewer_email, "Picture sharing",  $message, $headers);
							}
							else {
								$this->general_model->_add(array('table'=>'pv_share','pv_id'=>$pv_id,'sharefm_id'=>$user_id,'shareto_id'=>$shareto_id,'pv_name'=>$pv_name,'is_video'=>$is_video,'accept'=>1)); /* I am a viewer, the name is my name for display purpose*/
								$infor=newencode("$pv_id,$user_id,$shareto_id,$pv_name");
								$invitee = newencode($emailopt->viewer_email);
								$bodyText .= "<a href='$websit/PHP/ShareReply.php?infor=$infor&accept=1' onClick=\"alert('Thank you. Your photo is been shared!');\">&nbsp;&nbsp;Accept&nbsp;&nbsp;</a>   <a href='$websit/PHP/ShareReply.php?infor=$infor&accept=0' onClick=\"alert('Thank you. Your photo will not be shared!');\">&nbsp;&nbsp;Decline&nbsp;&nbsp;</a><br/>";
								$bodyText .= "$ReqImg</body></html>";
								$link=$websit.substr($pv_name,3);
								$ReqImg="<a href=\"$link\">Click here to view your photo </a>";
								$bodyText .= "$ReqImg</body></html>";						
								mail($emailopt->viewer_email, "Sharing Required",  $bodyText, $headers);
							}
						}
					}
				}
			}	
			else {
				$this->general_model->_get(array('table'=>'pv_share','pv_id'=>$pv_id,'sharefm_id'=>$user_id,'shareto_id'=>$shareto_id));
			}
		}
	}
}
