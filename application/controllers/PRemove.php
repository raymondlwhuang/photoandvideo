<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PRemove extends MainController {
	public function index()
	{
		$FriendID=$this->session->userdata('id');
		$notmypic=false;
		$backtomain=false;
		if($this->session->userdata('admin')==1 && $this->session->userdata('FriendID') && $this->session->userdata('FriendID')!=$this->session->userdata('id') && $this->session->userdata('FriendID')!="Public")
		{
			$FriendID=$this->session->userdata('FriendID');
			$get=$this->user->Get($FriendID);
			if($get)
			{
				$FriendName=$get->first_name." ".$get->last_name;
				$owner_path=$get->owner_path;
			}
		}
		if(isset($_GET['link']))
		{
			$link = $this->function_model->newdecode($this->input->get('link'));
			$get1=$this->picture_video->Get2($this->session->userdata('owner_path'),$link);
			if ($get1){
				foreach($get1 as $rowDelete)
				{
					$Orgname="/Orgpictures".substr($rowDelete->name,11);
					unlink($rowDelete->name);
					unlink($Orgname);
					$this->general_model->_delete(array('table'=>'pv_share','pv_id'=>$rowDelete->id));						
					$this->general_model->_delete(array('table'=>'picture_video','id'=>$rowDelete->id));						
				}
			}
			$this->general_model->_delete(array('table'=>'upload_infor','id'=>$link));						
			$this->general_model->_delete(array('table'=>'pv_comment','upload_id'=>$link));						
			$GetSomething=$this->picture_video->GetPV($this->session->userdata('owner_path'));
			if (!($GetSomething=="pictures" or $GetSomething=="both")){
				$backtomain=true;
			}
		}
		$rows=0;
		$resultPicture=$this->picture_video->Get0($this->session->userdata('owner_path'));
		if(!$resultPicture && $this->session->userdata('admin')==1 && $this->session->userdata('FriendID') && $this->session->userdata('FriendID')!=$this->session->userdata('id')) {
			$resultPicture=$this->picture_video->Get0($owner_path);
			$notmypic=true;
		}	
		$count = 0;
		$upload_id = '';
		if($resultPicture){
		foreach($resultPicture as $row3)
		{
				$upload_id = $row3->upload_id;
				$countrow=true;
				if(isset($picture_group)) {
					$dosave=true;
					foreach ($picture_group as $key5 => $value5) {
						foreach ($value5 as $key6 => $value6) {
							if($key5==$upload_id) $countrow=false;
							if($key5==$upload_id && $value6==$row3->name) $dosave=false;
						}
					}
					if($dosave) {
						$picture_group[$upload_id][] = $row3->name;
						if($countrow) $rows++;
					}
				}
				else {
					$rows=1;
					$picture_group[$upload_id][] = $row3->name;
				}
				$get3=$this->general_model->_get(array('table'=>'upload_infor','id'=>$upload_id));						
				if($get3){
					foreach($get3 as $row4)
					{
						$UploadDate = $row4->upload_date;
						$description = $row4->description;
					}
				}					
				$picture_UploadDate[$upload_id] = $UploadDate;
				$picture_description[$upload_id] = $description;
				$count++;
		}
		}
		if($rows==0) {
			$backtomain=true;
		}
		$pagenum = 1; 
		$page_rows = 49;  
		$last = ceil($rows/$page_rows); 
		if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
		$first_row=($pagenum -1)* $page_rows;
		$previous = $pagenum-1;
		if($previous == 0) $previous=1;
		$next = $pagenum+1;
		if($next > $rows) $next=$rows;
		$count=0;
		if($backtomain){
			$this->session->set_userdata('message', 'You got not pictures to delete!');
			redirect('/', 'refresh');
			/*
			$this->data['ErrorMessage']="You got not pictures to delete!";
			$this->data['rows']=$this->session->userdata('rows');
			$this->data['rows2']=$this->session->userdata('rows2');
			$this->data['profile_picture']=$this->session->userdata('profile_picture');
			$this->data['name']=$this->session->userdata('name');
			$this->load->view('main_header');
			$detect = new Mobiledtect;
			$this->data['layout'] = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
			$this->load->view("header",$this->data);
			$this->load->view("index",$this->data);			
			*/
		}
		else{
			$this->data['count']=$count;
			$this->data['last']=$last;
			$this->data['first_row']=$first_row;
			$this->data['page_rows']=$page_rows;
			$this->data['rows']=$rows;
			$this->data['notmypic']=$notmypic;
			if(isset($FriendName)) $this->data['FriendName']=$FriendName;
			$this->data['picture_UploadDate']=$picture_UploadDate;
			$this->data['picture_description']=$picture_description;
			$this->data['FriendID']=$FriendID;
			if(isset($picture_group)) $this->data['picture_group']=$picture_group;
			$this->load->view('setup_header');
			$this->load->view("header",$this->data);
			$this->load->view("pRemove_view",$this->data);
		}
	}
}
