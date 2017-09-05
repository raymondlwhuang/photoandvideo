<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ShareList extends MY_Controller {
	public function index()
	{
		if($this->input->get('Sharepagenum')) $Sharepagenum = $this->input->get('Sharepagenum'); 
		else $Sharepagenum = 1;
		$user_id = $this->input->get('user_id');
		$pv_id = $this->input->get('pv_id');
		$beforeList1 = $this->general_model->_get(array('table'=>'picture_video','id'=>$pv_id));
		if($beforeList1){
			foreach($beforeList1 as $rowB1) {
				$owner_path=$rowB1->owner_path;
			}
		}
		$beforeList2 = $this->general_model->_get(array('table'=>'user','owner_path'=>$owner_path));
		if($beforeList2){
			foreach($beforeList2 as $rowB2) {
				$owner_id=$rowB2->id;
			}
		}
		$this->load->model('pv_share');
		$beforeList3 = $this->pv_share->Get1($pv_id,$user_id);
		if($beforeList3){
			foreach($beforeList3 as $rowB3) {
				if(!isset($skip_id)) $skip_id[]=$rowB3->sharefm_id;
				$found=false;
				foreach ($skip_id as $key=>$id) {
					if($rowB3->sharefm_id==$id) $found=true;
				}
				if($found==false && $rowB3->sharefm_id!=$user_id)  $skip_id[]=$rowB3->sharefm_id;
				$found=false;
				foreach ($skip_id as $key=>$id) {
					if($rowB3->shareto_id==$id) $found=true;
				}
				if($found==false && $rowB3->shareto_id!=$user_id)  $skip_id[]=$rowB3->shareto_id;
			}
		}
		$Sharerows=0;
		$FriendResult=$this->view_permission->Get_group4($user_id,$owner_id);
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
						$optionViewer_id[] =  $option->viewer_id;
						$optionPicture[] = $profile_picture;
						$FirstName[] = $first_name;
						$LastName[] = $last_name;
						$optionSel[] = $option->share_flag;
						$Sharerows++;
						$shareResult = $this->general_model->_get(array('table'=>'pv_share','pv_id'=>$pv_id,'shareto_id'=>$option->viewer_id,'limit'=>1));
						if($shareResult) $shareFlag[]="checked='checked'";
						else $shareFlag[]="";
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
							$Sharerows++;
							$shareResult = $this->general_model->_get(array('table'=>'pv_share','pv_id'=>$pv_id,'shareto_id'=>$option->viewer_id,'limit'=>1));
							if($shareResult) $shareFlag[]="checked='checked'";
							else $shareFlag[]="";
						}
					}
				}
			}
		}
		$Sharepage_rows = 12;  
		$Sharelast = ceil($Sharerows/$Sharepage_rows); 
		if ($Sharepagenum < 1) $Sharepagenum = 1; elseif ($Sharepagenum > $Sharelast) $Sharepagenum = $Sharelast; 
		$Sharefirst_row=($Sharepagenum -1) * $Sharepage_rows;

		if(isset($optionViewer_id)) {
			$listcount=0;
			foreach ($optionViewer_id as $id => $friendeID) {
			$listcount++;
			if($listcount > $Sharefirst_row && $listcount <= ($Sharefirst_row+$Sharepage_rows)){
				$name=substr($FirstName[$id]." ".$LastName[$id],0,15);
				echo "<tr>
					<td  style='width:15px;'>";
				echo "<input type='checkbox' value='$id' id='$friendeID' $shareFlag[$id] onChange='Share(this.id,0);' />";
				echo "
					</td>
					<td  style='border-style: solid;border-width: 1px;text-align:center;width:50px;'>
					<img src=\"$optionPicture[$id]\" width='40px' class='img' /> 
					</td>";
				echo "	
					<td  style='font-size:10px;border-style: solid;border-width: 1px;text-align:center;'>
					$name
					</td></tr>";
				}
			}
		}
	}
}
