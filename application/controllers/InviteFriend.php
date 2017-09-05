<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InviteFriend extends MainController {
	public function index()
	{
		if(isset($_POST['Invite_x']))
		{
			foreach($_POST as $encode_email => $invited) {
			  if($invited=="invited"){
				$viewer_email=newdecode($encode_email);
				if($this->session->userdata('username')=="") {
					$from=$this->session->userdata('first_name'). " ".$this->session->userdata('last_name');
					if($from=="") $from=$this->session->userdata('email_address');
				}
				else $from=$this->session->userdata('username');
				$this->view_permission->Set_friendreq($this->session->userdata('id'),$viewer_email);
				$todaydate = date("l, F j, Y, g:i a");
				$to = $viewer_email;
				$inviter = newencode($this->session->userdata('email_address'));
				$invitee = newencode($viewer_email);
				$cc = "";
				$subject = "Invitation";
				$headers = "From: ".$this->session->userdata('email_address')."\r\n";
				$headers .= "Reply-To: ".$this->session->userdata('email_address')."\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
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
				.pointer { cursor: pointer }
				 -->
				 </style>
				 </head>
				 <body> 
				';						
				$bodyText .= "<h1><b>From: $from</b><h1><br/>";
				$bodyText .= "<h1>You're invited to view my photo book at http://www.raymondlwhuang.com Enjoy!<br/> Please accept me as your friend<br/>";
				$bodyText .= "Sent at: $todaydate</h1><br/><br/><br/>";
				$bodyText .= "<a href='http://www.raymondlwhuang.com/IReply.php?inviter=$inviter&invitee=$invitee&accept=1' class='pointer'>&nbsp;&nbsp;Accept&nbsp;&nbsp;</a>   <a href='http://www.raymondlwhuang.com/IReply.php?inviter=$inviter&invitee=$invitee&accept=0'  class='pointer'>&nbsp;&nbsp;Decline&nbsp;&nbsp;</a><br/>";
				$bodyText .= '</body></html>';
				if (preg_match("/bcc:/i", $viewer_email . " " . $bodyText) == 0 &&          /* check for injected 'bcc' field */
					preg_match("/Content-Type:/i", $viewer_email . " " . $bodyText) == 0 && /* check for injected 'content-type' field */
					preg_match("/cc:/i", $viewer_email . " " . $bodyText) == 0 &&           /* check for injected 'cc' field */
					preg_match("/to:/i", $viewer_email . " " . $bodyText) == 0) {           /* check for injected 'to' field */
					// Format the body of the email
					$sent = mail($to, $subject, $bodyText, $headers) ;
				} else  {
					$message = "We encountered an error sending your mail<br/>";
				}
			  }
			}
		}
		$ViewerList=$this->view_permission->Get_waiting($this->session->userdata('email_address'),9);
		$data['ViewerList']=$ViewerList;
		if ($ViewerList){
			$this->load->view("inviteFriend_view",$data);
		}
		else {
		/*
			$this->data['ErrorMessage']="Set up is done!";
			$this->data['rows']=$this->session->userdata('rows');
			$this->data['rows2']=$this->session->userdata('rows2');
			$this->data['profile_picture']=$this->session->userdata('profile_picture');
			$this->data['name']=$this->session->userdata('name');
			$this->load->view('main_header');
			$detect = new Mobiledtect;
			$this->data['layout'] = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
//			$this->load->view("header",$this->data);
//			$this->load->view("index",$this->data);	*/		
			redirect('/', 'refresh');
		}
	}
}
