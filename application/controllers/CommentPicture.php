<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CommentPicture extends MainController {
	public function index()
	{
		$this->load->model('pv_share');
		if($this->input->get('comment'))
		{
			$upload_id = $this->input->get('upload_id');
			$PV_id = $this->input->get('pv_id');
			$this->session->set_userdata('this_id', $PV_id);
			$this->session->set_userdata('thisPicture', $this->input->get('thisPicture'));
			$comment = $this->input->get('comment');
			if($this->input->get('owner_id') && $this->input->get('owner_id') != "" && $this->input->get('owner_id') != "Public") $owner_id1 = $this->input->get('owner_id');
			else $owner_id1 = $this->session->userdata('id');
			if($comment!="") {
				$this->general_model->_add(array('table'=>'pv_comment','PV_id'=>$this->input->get('pv_id'),'upload_id'=>$upload_id,'user_id'=>$owner_id1,'viewer_user_id'=>$this->session->userdata('id'),'comment'=>$comment));
			}
		}
		if($this->input->get('viewer_group')) { 
			$viewer_group = $this->input->get('viewer_group');
			$this->session->set_userdata('viewer_group',$viewer_group);
		}
		else $viewer_group = $this->session->userdata('viewer_group');
		if($this->input->get('upload_id')) {
			$upload_id = $this->input->get('upload_id');
			$this->session->set_userdata('upload_id',$upload_id);
		}
		else $upload_id = $this->session->userdata('upload_id');
		if($this->input->get('owner_id')) $user_id = $this->input->get('owner_id'); else $user_id=$this->session->userdata('id');
		if($this->input->get('viewingOn')) {
			if($this->input->get('viewingOn')!="Public") {
				$queryOwner=$this->general_model->_get(array('table'=>'user','id'=>$this->input->get('viewingOn')));
				if($queryOwner){
					foreach($queryOwner as $row3)
					{
					 $first_name=ucfirst(strtolower($row3->first_name));
					 $last_name = ucfirst(strtolower($row3->last_name));
					 $this->session->set_userdata('viewingOn', $first_name.' '.$last_name);
					 $this->session->set_userdata('viewingOnprofile', $row3->profile_picture);
					}
				}
			}
			else {
				 $this->session->set_userdata('viewingOn', 'Public');
				 $this->session->set_userdata('viewingOnprofile', '/images/profile/public.jpg');
			}
		}
		if($viewer_group!="Public") {
			$getowner=$this->general_model->_get(array('table'=>'user','id'=>$user_id));
			if($getowner){
				foreach($getowner as $owneresult) {
					$result_path=$owneresult->owner_path;
					$result_name=ucfirst(strtolower($owneresult->first_name))." ".ucfirst(strtolower($owneresult->last_name));
				}
			}
			$beforeShow=$this->general_model->_get(array('table'=>'picture_video','owner_path'=>$result_path,'viewer_group'=>$viewer_group,'upload_id'=>$upload_id));
			if($beforeShow){
				foreach($beforeShow as $row7) {
					$pictures[]=$row7->name;
					$OrgPicture[] = str_replace("/pictures/", "/Orgpictures/", $row7->name);
					if(!$this->session->userdata('thisPicture')) $this->session->set_userdata('thisPicture', str_replace("/pictures/", "/Orgpictures/", $row7->name));
					$pv_id[]=$row7->id;
				}
			}			
		}
		else {
			$beforeShow=$this->general_model->_get(array('table'=>'picture_video','viewer_group'=>'Public','upload_id'=>$upload_id));
			if($beforeShow){
				foreach($beforeShow as $row7) {
					$pictures[]=$row7->name;
					$OrgPicture[] = str_replace("/pictures/", "/Orgpictures/", $row7->name);
					if(!$this->session->userdata('thisPicture')) $this->session->set_userdata('thisPicture', str_replace("/pictures/", "/Orgpictures/", $row7->name));
					$pv_id[]=$row7->id;
				}	
			}
		}
		$name="";
		if(!$this->session->userdata('this_id')) $this->session->set_userdata('this_id', $pv_id[0]);
		$Comcount=0;
		$queryComment=$this->general_model->_get(array('table'=>'pv_comment','upload_id'=>$upload_id,'sortBy'=>'id','sortDirection'=>'desc'));
		if($queryComment){
			foreach($queryComment as $row4)
			{	
				if($row4->PV_id==0 || $row4->PV_id==$this->session->userdata('this_id')) {
					$Comcount++;
					$queryUser=$this->general_model->_get(array('table'=>'user','id'=>$row4->viewer_user_id));
					if($queryUser){
						foreach($queryUser as $row6)
						{		
							$name=$row6->first_name." ".$row6->last_name;
							$result_profile_picture[]=$row6->profile_picture;
						}
					}
					$date1= strtotime($row4->comment_date);
					$comment_date= substr(date('r',$date1),0,-6);
					$queryPV=$this->general_model->_get(array('table'=>'picture_video','upload_id'=>$row4->upload_id,'limit'=>1));
					if($queryPV){
						foreach($queryPV as $row5)
						{
							$pic_group[]=$row5->viewer_group;
						}
					}
					if($row4->PV_id==0) $color='darkblue';
					else $color='black';
					$comments[] = "<div style=\"display:inline-block;vertical-align:top;text-align:left;color:$color;\">".$row4->comment."<font size='3'>($comment_date - $name)</font></div>";
					$pic_upload_id[]=$row4->upload_id;
					$owner_id[]=$row4->user_id;
				}
			}
		}
		$pagenum = 1; 
		$page_rows = 6;  
		$last = ceil($Comcount/$page_rows); 
		if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
		$first_row=($pagenum -1)* $page_rows;
		$previous = $pagenum-1;
		if($previous <= 0) $previous=1;
		$next = $pagenum+1;
		if($next > $Comcount) $next=$Comcount;
		$Sharerows=0;
		$shareallowed = "no";
		if($this->session->userdata('id') && $user_id==$this->session->userdata('id')) $shareallowed = "yes";
		elseif($user_id!="Public") {
			$PermitResult = $this->view_permission->Get_group(array('user_id'=>$user_id,'viewer_id'=>$this->session->userdata('id'),'groupBy'=>'viewer_id','flag'=>2));
			if($PermitResult) {
				$shareallowed = "yes";
			}
		}
		if($this->session->userdata('id')) {
			$beforeList3 = $this->pv_share->Get1($this->session->userdata('this_id'),$this->session->userdata('id'));
			if($beforeList3){
				foreach($beforeList3 as $rowB3) {
					if(!isset($skip_id)) $skip_id[]=$rowB3->sharefm_id;
					$found=false;
					foreach ($skip_id as $key=>$id) {
						if($rowB3->sharefm_id==$id) $found=true;
					}
					if($found==false && $rowB3->sharefm_id!=$this->session->userdata('id'))  $skip_id[]=$rowB3->sharefm_id;
					$found=false;
					foreach ($skip_id as $key=>$id) {
						if($rowB3->shareto_id==$id) $found=true;
					}
					if($found==false && $rowB3->shareto_id!=$this->session->userdata('id'))  $skip_id[]=$rowB3->shareto_id;
				}
			}
		}	
		if($user_id!="Public") {
			if($user_id!=$this->session->userdata('id')) $FriendResult = $this->view_permission->Get_group4(array('user_id'=>$this->session->userdata('id'),'viewer_id'=>$user_id));
			else $FriendResult = $this->view_permission->Get_group(array('user_id'=>$this->session->userdata('id'),'groupBy'=>'viewer_id','flag'=>0));
			if($FriendResult){
				foreach($FriendResult as $option) {
					$found=false;
					if(isset($skip_id)) {
						if (in_array($option->viewer_id, $skip_id)) {
							$found=true;
						}
					}	
					if($found==false) {
						$PictureResult = $this->general_model->_get(array('table'=>'user','email_address'=>$option->viewer_email));
						if($PictureResult){
							foreach($PictureResult as $row) {
								$profile_picture=$row->profile_picture;
								$first_name=$row->first_name;
								$last_name=$row->last_name;
							}
						}
						if(!isset($optionViewer_id) && isset($profile_picture)) {
							$optionViewer_id[] =  $option['viewer_id'];
							$optionPicture[] = $profile_picture;
							$FirstName[] = $first_name;
							$LastName[] = $last_name;
							$optionSel[] = $option['share_flag'];
							$Sharerows++;
							$shareResult = $this->general_model->_get(array('table'=>'pv_share','pv_id'=>$this->session->userdata('this_id'),'shareto_id'=>$option->viewer_id,'limit'=>1));
							if($shareResult) $shareFlag[]="checked='checked'";
							else $shareFlag[]="";
						}
						elseif(isset($optionViewer_id)) {
							$duplicated = false;
							foreach($optionViewer_id as $id=>$Viewer_id) {
								if($Viewer_id==$option['viewer_id']) $duplicated = true;
							}
							if($duplicated == false){
								$optionViewer_id[] = $option['viewer_id'];
								$optionPicture[] = $profile_picture;
								$FirstName[] = $first_name;
								$LastName[] = $last_name;
								$optionSel[] = $option['share_flag'];
								$Sharerows++;
								$shareResult = $this->general_model->_get(array('table'=>'pv_share','pv_id'=>$this->session->userdata('this_id'),'shareto_id'=>$option->viewer_id,'limit'=>1));
								if($shareResult) $shareFlag[]="checked='checked'";
								else $shareFlag[]="";
							}
						}
					}
				}
			}
		}
		$Sharepagenum=1;
		$Sharepage_rows = 12;  
		$Sharelast = ceil($Sharerows/$Sharepage_rows); 
		if ($Sharepagenum < 1) $Sharepagenum = 1; elseif ($Sharepagenum > $Sharelast) $Sharepagenum = $Sharelast; 
		$Sharefirst_row=($Sharepagenum -1) * $Sharepage_rows;
		if(!$this->session->userdata('thisPicture')) $this->session->set_userdata('thisPicture', $OrgPicture[0]);
		if(isset($shareFlag)) $this->data['shareFlag'] = $shareFlag;
		if(isset($FirstName)) $this->data['FirstName'] = $FirstName;
		if(isset($LastName)) $this->data['LastName'] = $LastName;
		if(isset($optionPicture)) $this->data['optionPicture'] = $optionPicture;
		if(isset($upload_id)) $this->data['upload_id'] = $upload_id;
		if(isset($comments)) $this->data['comments'] = $comments;
		if(isset($first_row)) $this->data['first_row'] = $first_row;
		if(isset($page_rows)) $this->data['page_rows'] = $page_rows;
		if(isset($result_profile_picture)) $this->data['result_profile_picture'] = $result_profile_picture;
		if(isset($pictures)) $this->data['pictures'] = $pictures;
		if(isset($OrgPicture)) $this->data['OrgPicture'] = $OrgPicture;
		if(isset($pv_id)) $this->data['pv_id'] = $pv_id;
		if(isset($shareallowed)) $this->data['shareallowed'] = $shareallowed;
		if(isset($Sharelast)) $this->data['Sharelast'] = $Sharelast;
		if(isset($Sharerows)) $this->data['Sharerows'] = $Sharerows;
		if(isset($last)) $this->data['last'] = $last;
		if(isset($Comcount)) $this->data['Comcount'] = $Comcount;
		if(isset($page_rows)) $this->data['page_rows'] = $page_rows;
		$this->data['Sharepage_rows'] = $Sharepage_rows;
		if(isset($optionViewer_id)) $this->data['optionViewer_id'] = $optionViewer_id;
		$this->load->view('commentPicture_header');
		if($this->session->userdata('id')) {
			$detect = new Mobiledtect;
			$this->data['layout'] = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
			$this->load->view("header",$this->data);
		}
		else {
			$this->load->view("header2",$this->data);
		}		
		$this->load->view("commentPicture_view",$this->data);
	}
}
