<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SetSharing3 extends MY_Controller {
	public function index()
	{
		if(isset($_GET['pagenum'])) $pagenum = $this->input->get('pagenum'); 
		else $pagenum = 1;
		$user_id = $this->input->get('user_id');  
		$rows=0;
		$get=$this->view_permission->Get_group(array('user_id'=>$user_id,'flag'=>0));
		if($get){
			foreach($get as $option) {
				$get1=$this->user->Get($option->viewer_email);
				if($get){
					$profile_picture=$get1->profile_picture;
					$first_name=$get1->first_name;
					$last_name=$get1->last_name;
				}
				if(!isset($optionViewer_id) && isset($profile_picture)) {
					$optionViewer_id[] =  $option['viewer_id'];
					$optionPicture[] = $profile_picture;
					$FirstName[] = $first_name;
					$LastName[] = $last_name;
					$optionSel[] = $option['share_flag'];
					$rows++;
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
						$rows++;
					}
				}
			}
		}
		$page_rows = 12;  
		$last = ceil($rows/$page_rows); 
		if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
		$first_row=($pagenum -1)* $page_rows;
		if(isset($optionViewer_id)) {
			$listcount=0;
			foreach ($optionViewer_id as $id => $friendeID) {
			$listcount++;
			if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
				echo "<tr>";
				echo "<td  style='border-style: solid;border-width: 1px;text-align:center;width:50px;'>
					<img src=\"$optionPicture[$id]\" width='50px' /> 
					</td>";
				echo "<td  style='font-size:15px;border-style: solid;border-width: 1px;text-align:center;'>
					  $FirstName[$id]	$LastName[$id]
					  </td>";
				echo "<td  style='border-style: solid;border-width: 1px;text-align:center;width:50px;'>";
				echo "<select onChange='Action(this.value,$friendeID);'>";
				if($optionSel[$id]==0) echo "<option value=\"0\" selected=\"selected\">No Sharing</option>";
				else  echo "<option value=\"0\" >No Sharing</option>";
				if($optionSel[$id]==1) echo "<option value=\"1\" selected=\"selected\">Ask to share</option>";
				else echo "<option value=\"1\">Ask to share</option>";
				if($optionSel[$id]==2) echo "<option value=\"2\" selected=\"selected\">Share allowed</option>";
				else echo "<option value=\"2\">Share allowed</option></select></td></tr>";
				}
			}
		}
	
	}
}
