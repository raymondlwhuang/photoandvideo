<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends MainController {
	public function index()
	{
		if(!isset( $_COOKIE["greeting"] )) {
			setcookie( "greeting", "Welcome back ", time() + 60 * 60 * 24 * 365, "", "", false, true );
			redirect('/Introduction', 'refresh');
			die();					
		}
		else
		{
			$ErrorMessage="";
			$this->user->Active();
			if($this->session->userdata('username'))
			{
				if ($this->session->userdata('page') == "HER") {
					redirect('/HERecorder', 'refresh');
					die();					
				}
				elseif ($this->session->userdata('page') == "HELP") {
					$this->load->view("ResultDisp");
				}
				$this->session->unset_userdata('page');
			}
			$ip = $this->function_model->getIpAddr();
		
			if($this->input->post('submit'))
			{
				$post_username = $this->input->post('username');
				$post_password = MD5($this->input->post('password'));
				if($this->input->post('autologin')) $post_autologin = $this->input->post('autologin'); else $post_autologin = 0;
				$get=$this->user->Get($post_username,$post_password);
				if ($get){
					$gethash=ucfirst(strtolower($get->first_name)).date("YmdHi");
					$private=$this->function_model->newencode($gethash);
					//echo $private;
					$this->session->set_userdata('first_name',ucfirst(strtolower($get->first_name)));
					$this->session->set_userdata('last_name', ucfirst(strtolower($get->last_name)));
					$this->session->set_userdata('id', $get->id);
					$this->session->set_userdata('owner_path', $get->owner_path);
					$this->session->set_userdata('name', ucfirst(strtolower($get->first_name))." ".ucfirst(strtolower($get->last_name)));
					$this->session->set_userdata('profile_picture', $get->profile_picture);
					$this->session->set_userdata('admin', $get->is_super_admin);
					$this->session->set_userdata('discoverable', $get->discoverable);
					$this->session->set_userdata('username', $post_username);
					$this->session->set_userdata('email_address', $post_username);
					$this->session->set_userdata('private', $private);
					$this->session->set_userdata('MsgBox', "no");
					$this->user->Set_activity($get->id,3);
					$this->view_permission->Set_activity($post_username);
					if($post_autologin == 1)
					{
						setcookie ($this->cookie_name, 'usr='.$post_username.'&hash='.$post_password, time() + $this->cookie_time);
					}
					redirect('/', 'refresh');
				}
				else $ErrorMessage="Password not match! Please try again.";
			}
			elseif ($this->input->post("UserSetup")) {
				$email_address = strtolower($this->input->post('username'));
				$ip=$this->function_model->getIpAddr();
				$todaydate = date("l, F j, Y, g:i a");
				$sendinfo = $email_address.':::'.date('Ymd').'000000';
				$userEmail = $this->function_model->newencode($sendinfo);
				$to = "$email_address";
				$headers = "From: no-reply@raymondlwhuang.com\r\n";
				$headers .= "cc: raymondlwhuang@yahoo.com\r\n";
				$headers .= "Reply-To: no-reply@raymondlwhuang.com\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$message = '<html><body>';	
				$password = '';
				$get=$this->user->_get1(array('email_address'=>$email_address));
				if ($get){
					$first_name=ucfirst(strtolower($get->first_name));
					$last_name = ucfirst(strtolower($get->last_name)); 
					$password = $get->password;
				}
				if($password != ''){
					$subject = "Password reset required";
					$message .= "<h1>Hi $first_name $last_name,</h1>";
					$message .= "<br/><br/>You require a password reset to this email address: $email_address <br/>
					Click here to <a href='http://www.raymondlwhuang.com/UserSetup?UserSetup=$userEmail' > Reset your password</a><br/>
					If you forgot the password for one of these usernames, just re-type in all information for this e-mail address,<br/>
					Hope this helps.<br/><br/><br/>
					See you back on www.raymondlwhuang.com!<br/><br/><br/>";
				}
				else {
					$subject = "Account Setup Notification";
					$message .= "<h1>User Setup Notification,</h1>";
					$message .= "<br/><br/>Please Click here to <a href='http://www.raymondlwhuang.com/UserSetup?UserSetup=$userEmail' > Set up your account</a><br/>
					Hope this helps.<br/><br/><br/>
					See you back on www.raymondlwhuang.com!<br/><br/><br/>";	
				}
				$message .= '</body></html>';
				if (preg_match("/bcc:/i", $userEmail . " " . $message) == 0 &&          /* check for injected 'bcc' field */
					preg_match("/Content-Type:/i", $userEmail . " " . $message) == 0 && /* check for injected 'content-type' field */
					preg_match("/cc:/i", $userEmail . " " . $message) == 0 &&           /* check for injected 'cc' field */
					preg_match("/to:/i", $userEmail . " " . $message) == 0) {           /* check for injected 'to' field */
					// Format the body of the email
					$message = $message . "\n\nSent from: $ip ($todaydate)\n";
					// Set the header, include the ip and set the reply-to field for convenience when replying to the email
			//		$headers = "CC: $cc\nX-Sender-IP: $ip\nFrom: $email\nReply-To: $email";
					// Send the email and check the result whether the function call was successful or not
					$sent = mail($to, $subject, $message, $headers) ;
					if($sent) {
						$ErrorMessage="Please check your mail and <br/>See you back on www.raymondlwhuang.com!";
					} else {
						$ErrorMessage="We encountered an error sending your mail";
					}
				} else  {
					$ErrorMessage="We encountered an error sending your mail";
				}
			}
			elseif ($this->input->get("Temporary")) {
			  $Temporary=$this->input->get("Temporary");
			}

			if(isset($Temporary)) {
				$name['Temporary'] = 'Temporary';
				$profile_picture['Temporary'] = "/images/profile/public.jpg";
				$FriendID['Temporary'] = 'Temporary';
			}
			else {
				$name['public'] = 'Public';
				$profile_picture['public'] = "/images/profile/public.jpg";
				$FriendID['public'] = 'Public';
			}
			$rows=1;
			$rows2=0;

			if($this->session->userdata('id')) {
				setcookie( "greeting", "Welcome back ".$this->session->userdata('first_name'), time() + 60 * 60 * 24 * 365, "", "", false, true ); 
				$owner_path=$this->session->userdata('owner_path');
				$this->user->Update_last_activity($this->session->userdata('id'));
				$name[$owner_path] = $this->session->userdata('name');
				$profile_picture[$owner_path] = $this->session->userdata('profile_picture');
				$rows++;
				$FriendID[$owner_path] = $this->session->userdata('id');
				$PicturePath = "/pictures/".$this->session->userdata('owner_path');
				$get=$this->picture_video->Get($PicturePath,$owner_path);
				if($get){
					$upload_id[$owner_path] = $get->upload_id;
				}
				if($this->session->userdata('admin')==1){
					$get1=$this->view_permission->Get_email();
				}
				else
					$get1=$this->view_permission->Get_email($this->session->userdata('id'));
				foreach($get1 as $row2)
				{
					$curr_path = $row2->owner_path;
					$FriendID[$curr_path] = $row2->user_id;
					$get2=$this->user->Get($row2->owner_email);
					$first_name=ucfirst(strtolower($get2->first_name));
					$last_name = ucfirst(strtolower($get2->last_name));
					$profile_picture[$curr_path] = $get2->profile_picture;
					$rows++; 
					$name[$curr_path] = $first_name." ".$last_name;
					$PicturePath = "/pictures/$curr_path";
					$get3=$this->picture_video->Get($PicturePath,$curr_path);
					if($get3) $upload_id["$curr_path"] = $get3->upload_id;
				}
				$PicturePath = "/pictures/$curr_path";
				$get4=$this->picture_video->Get($PicturePath,$curr_path);
				$message = "";
				if(!$get4) {
					$message = "<br/>Please upload some picture/video to share!";
				}
				$get5=$this->view_permission->Get_email($this->session->userdata('id'));
				if($get5){
					foreach($get5 as $option) {
						$get6=$this->view_permission->Get($this->session->userdata('email_address'),$option->owner_email);
						if(!$get6) {
							$get7=$this->user->Get($option->owner_email);
							if($get7) {
								$first_name=$get7->first_name;
								$last_name=$get7->last_name;
								$viewer_id=$get7->id;
								$curr_path=$get7->owner_path;
								if(isset($profile_picture2)) {
									$found=0;
									foreach ($profile_picture2 as $key2 => $value2) {
										if($key2==$curr_path && $value2==$get7->profile_picture) $found=1;
									}
									if($found==0) {
										$FriendID2[$curr_path] = $get7->id;
										$profile_picture2[$curr_path] = $get7->profile_picture;
										$name[$curr_path] = $first_name." ".$last_name;
										$rows2++;
									}
								}
								else {
										$FriendID2[$curr_path] = $get7->id;
										$profile_picture2[$curr_path] = $get7->profile_picture;
										$name[$curr_path] = $first_name." ".$last_name;
										$rows2++; 
								}			
							}
						}
						if($option->owner_email!=$this->session->userdata('email_address')){
							$get8=$this->view_permission->Get_viewer_email($option->owner_email);
							if($get8){
								foreach($get8 as $option2) {
									if(!$get8 && $option2->viewer_email!=$this->session->userdata('email_address')) {
										$get9=$this->user->Get($option2->viewer_email);
										if($get9) {
											$first_name=$get9->first_name;
											$last_name=$get9->last_name;
											$viewer_id=$get9->id;
											$curr_path=$get9->owner_path;
											if(isset($profile_picture2)) {
												$found=0;
												foreach ($profile_picture2 as $key2 => $value2) {
													if($key2==$curr_path && $value2==$get9->profile_picture) $found=1;
												}
												if($found==0) {
													$FriendID2[$curr_path] = $get9->id;
													$profile_picture2[$curr_path] = $get9->profile_picture;
													$name[$curr_path] = $first_name." ".$last_name;
													$rows2++; 
												}
											}
											else {
												$FriendID2[$curr_path] = $get9->id;
												$profile_picture2[$curr_path] = $get9->profile_picture;
												$name[$curr_path] = $first_name." ".$last_name;
												$rows2++; 
											}
										}		
									}
								}
							}
						}
						
					}
				}
			}
			$pagenum=1;
			$page_rows = 5;
			$previous=1;
			$next = $pagenum+1;
			if($next > $rows) $next=$rows;
			$first_row=($pagenum -1)* $page_rows;
			$last = ceil($rows/$page_rows);
			$pagenum2=1;
			$page_rows2 = 4;
			$previous2=1;
			$next2 = $pagenum2+1;
			if($next2 > $rows2) $next2=$rows2;
			$first_row2=($pagenum2 -1)* $page_rows2;
			$last2 = ceil($rows2/$page_rows2);
			if($this->session->userdata('id')) $beforeShow2=$this->view_permission->_get1(array('user_id'=>$this->session->userdata('id'),'activeFlag'=>0));
			if(isset($beforeShow2)) $this->data['beforeShow2']=$beforeShow2;
			$this->data['ErrorMessage']=$ErrorMessage;
			$this->data['rows']=$rows;
			$this->data['page_rows']=$page_rows;
			$this->data['pagenum']=$pagenum;
			$this->data['previous']=$previous;
			$this->data['next']=$next;
			$this->data['first_row']=$first_row;
			$this->data['last']=$last;
			$this->data['rows2']=$rows2;
			$this->data['page_rows2']=$page_rows2;
			$this->data['pagenum2']=$pagenum2;
			$this->data['previous2']=$previous2;
			$this->data['next2']=$next2;
			$this->data['first_row2']=$first_row2;
			$this->data['last2']=$last2;
			$this->session->set_userdata('rows',$rows);
			$this->session->set_userdata('rows2',$rows2);
			if(isset($profile_picture))$this->data['profile_picture']=$profile_picture;
			if(isset($profile_picture2))$this->data['profile_picture2']=$profile_picture2;
			$this->data['name']=$name;
			$this->data['FriendID']=$FriendID;
			if(isset($FriendID2)) $this->data['FriendID2']=$FriendID2;
			if(isset($upload_id)) $this->data['upload_id']=$upload_id;
			if(isset($Temporary)) $this->data['Temporary']=$Temporary;
			if(isset($show_id)) $this->data['show_id']=$show_id;
			$this->load->view('main_header');
			if($this->session->userdata('id')) {
				$detect = new Mobiledtect;
				$this->data['layout'] = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
				$this->load->view("header",$this->data);
			}
			else {
				$this->load->view("header2",$this->data);
			}
			$this->load->view("index",$this->data);			
		}
	}
}
