<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SetSharing extends MY_Controller {
	public function index()
	{
		$pagenum = 1; 
		$rows=0;
		$get=$this->view_permission->Get_group(array('user_id'=>$this->session->userdata('id'),'flag'=>0));
		if($get){
			foreach($get as $option) {
				$get1=$this->user->Get($option->viewer_email);
				if($get1){
					$profile_picture=$get1->profile_picture;
					$first_name=$get1->first_name;
					$last_name=$get1->last_name;
				}
				if(!isset($optionViewer_id) && isset($profile_picture)) {
					$optionViewer_id[] =  $option->viewer_id;
					$optionPicture[] = $profile_picture;
					$FirstName[] = $first_name;
					$LastName[] = $last_name;
					$optionSel[] = $option->share_flag;
					$rows++;
				}
				elseif(isset($optionViewer_id)) {
					$duplicated = false;
					foreach($optionViewer_id as $id=>$Viewer_id) {
						if($Viewer_id==$option->viewer_id) $duplicated = true;
					}
					if($duplicated == false){
						$optionViewer_id[] = $option->viewer_id;
						$optionPicture[] = $profile_picture;
						$FirstName[] = $first_name;
						$LastName[] = $last_name;
						$optionSel[] = $option->share_flag;
						$rows++;
					}
				}
			}
		}
		$page_rows = 12;  
		$last = ceil($rows/$page_rows); 
		if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
		$first_row=($pagenum -1)* $page_rows;
		$this->data['last']=$last;
		$this->data['pagenum']=$pagenum;
		$this->data['first_row']=$first_row;
		$this->data['page_rows']=$page_rows;
		$this->data['rows']=$rows;
		if(isset($optionViewer_id)) $this->data['optionViewer_id']=$optionViewer_id;
		if(isset($optionPicture)) $this->data['optionPicture']=$optionPicture;
		if(isset($FirstName)) $this->data['FirstName']=$FirstName;
		if(isset($LastName)) $this->data['LastName']=$LastName;
		if(isset($optionSel)) $this->data['optionSel']=$optionSel;
		$detect = new Mobiledtect;
		$this->data['layout'] = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
		$this->load->view('main_header');
		$this->load->view("header",$this->data);
		$this->load->view("SetSharing_view",$this->data);
	}
}
