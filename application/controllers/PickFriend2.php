<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PickFriend2 extends MY_Controller {
	public function index()
	{
		$user_id = $this->input->get('user_id');
		$get=$this->user->Get1($this->input->get('user_id'));
		if($get) {
			$owner_email = $get->email_address;
			$owner_path = $get->owner_path;
		}
		$get1=$this->user->Get1($this->input->get('viewer_id'));
		if($get1) {
			$viewer_email = $get1->email_address;
		}
		$name = $this->input->get('name');
		$friendnow = $this->input->get('friendnow');
		$action = $this->input->get('action');

		if($action==0){
			$this->view_permission->Update($user_id,$owner_email,$viewer_email,$friendnow)
		}
		else {
			$this->view_permission->Update1($user_id,$friendnow)
		}
		$get2=$this->view_permission->Get_inactive($owner_email);
		if($get2){
			while($get2 as $option4) {
				$get3=$this->user->Get($option4->viewer_email);
				if($get3) {
					$profile_picture=$get3->profile_picture;
					$first_name=$get3->first_name;
					$last_name=$get3->last_name;
				}
				if(!isset($optionEmail) && isset($profile_picture)) {
					$optionEmail[] =  $option4->viewer_email;
					$optionPicture[] = $profile_picture;
					$FirstName[] = $first_name;
					$LastName[] = $last_name;
					$optionSel[] = $option4->is_active;
				}
				elseif(isset($optionEmail)) {
					$duplicated = false;
					foreach($optionEmail as $id=>$email) {
						if($email==$option4->viewer_email) $duplicated = true;
					}
					if($duplicated == false){
						$optionEmail[] = $option4->viewer_email;
						$optionPicture[] = $profile_picture;
						$FirstName[] = $first_name;
						$LastName[] = $last_name;
						$optionSel[] = $option4->is_active;
					}
				}
			}
		}

		if(isset($optionEmail)) {
			foreach ($optionEmail as $id => $friendemail) {
			echo "<tr>
				<td  style='width:15px;'>";
				if($optionSel[$id]==-1)
					echo "<input type='checkbox' value='$id' id='$friendemail' checked='checked' onChange='Action(this.value,this.id,0);' />";
				else
					echo "<input type='checkbox' value='$id' id='$friendemail' onChange='Action(this.value,this.id,0);' />";
			echo "
				</td>
				<td  style='border-style: solid;border-width: 1px;text-align:center;width:50px;'>
				<img src=\"$optionPicture[$id]\" width='50px' /> 
				</td>";
			echo "	
				<td  style='font-size:15px;border-style: solid;border-width: 1px;text-align:center;'>
				$FirstName[$id]	$LastName[$id]
				</td>";
			}
		}
	}
}
