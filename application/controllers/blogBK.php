<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends MainController {
	public function __construct() 
	{ 
		parent::__construct(); 
	} 
	 
	public function index()
	{
		$ErrorMessage="";
		$this->user->Active();
		if($this->session->userdata('username')))
		{
			if (isset($this->session->userdata('page')) && $this->session->userdata('page') == "HER") {
				$this->load->view("HERecorder");
			}
			elseif (isset($this->session->userdata('page')) && $this->session->userdata('page') == "HELP") {
				$this->load->view("ResultDisp");
			}
			unset($this->session->userdata('page'));
		}
		$ip = $this->function_model->getIpAddr();
		if(isSet($_POST['submit']))
		{
			$post_username = $this->input->post('username');
			$post_password = MD5($this->input->post('password'));

			if(isset($_POST['autologin'])) $post_autologin = $this->input->post('autologin'); else $post_autologin = 0;
			$get=$this->user->Get($post_username,$post_password);
			if ($get){
				$this->session->userdata('first_name') =ucfirst(strtolower($get->first_name));
				$this->session->userdata('last_name') = ucfirst(strtolower($get->last_name));
				$this->session->userdata('id') = $get->id;
				$this->session->userdata('owner_path') = $get->owner_path;
				$this->session->userdata('name') = ucfirst(strtolower($get->first_name))." ".ucfirst(strtolower($get->last_name));
				$this->session->userdata('profile_picture') = $get->profile_picture;
				$this->session->userdata('admin') = $get->is_super_admin;
				$this->session->userdata('discoverable') = $get->discoverable;
				$this->session->userdata('username') = $post_username;
				$this->session->userdata('email_address') = $post_username;
				$this->session->userdata('private') = "yes";
				$this->session->userdata('MsgBox') = "no";
				$this->user->Set_activity($get->id,3);
				$this->view_permission->Set_activity($post_username);
				if($post_autologin == 1)
				{
					setcookie ($this->cookie_name, 'usr='.$post_username.'&hash='.$post_password, time() + $this->cookie_time);
				}
//				$this->load->view("index",$data);
			}
			else $ErrorMessage="Password not match! Please try again.";
		}
		elseif ( isset( $_POST["UserSetup"] ) ) {
		  $ErrorMessage=UserSetup($this->input->post('username'));
		}
		elseif ( isset( $_GET["Temporary"] ) ) {
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
//								$FriendResult3 = mysql_query("SELECT * FROM view_permission WHERE owner_email = '$this->session->userdata('email_address')' and viewer_email='$option2[viewer_email]' limit 1");
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
		$beforeShow2=$this->view_permission->_get1(array('user_id'=>$this->session->userdata('id'),'activeFlag'=>0));
		$this->data['beforeShow2']=$beforeShow2;
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
		$this->session->set_userdata('rows', $rows);
		$this->session->set_userdata('rows2', $rows2);
		$this->data['profile_picture']=$profile_picture;
		$this->data['name']=$name;
		$this->data['FriendID']=$FriendID;
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

	public function blog () {
		echo "this is blog";
	}
}
