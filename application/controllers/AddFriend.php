<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddFriend extends MainController {
	public function index()
	{
		$is_active = 1;
		$this->view_permission->Delete($this->session->userdata('id'),-2);
		$this->view_permission->Update1($this->session->userdata('id'),-2);
		$this->view_permission->Set_waiting($this->session->userdata('id'));
		$get=$this->view_permission->Get_waiting($this->session->userdata('email_address'),9);
		if($get){
			foreach($get as $nt1){
				$get1=$this->view_permission->Get_friend($nt1->viewer_email,$nt1->owner_email);	 /* owner_email	is $nt1->viewer_email*/
				if($get1){
					foreach($get1 as $nt2){
						$this->view_permission->Set_activity1($nt1->id);
						if($nt2->is_active==9) $this->view_permission->Set_activity1($nt2->id);
					}
				}
			}
		}
		if(isset($_POST['Delete_x']))
		{
			$delete_viewer_id = $this->input->post('delete_viewer_id');
			$this->view_permission->Delete1($this->session->userdata('id'),$delete_viewer_id);
		}
		elseif(isset($_POST['Save_x']))
		{

			$viewer_email =  $this->input->post('viewer_email');
			$first_name =  $this->input->post('first_name');
			$last_name =  $this->input->post('last_name');
			if(!filter_var((String) $viewer_email, FILTER_VALIDATE_EMAIL)) {
				$ErrorMessage = "** Invalide e-mail address! Please try again. **";
			}
			else {
				if($this->session->userdata('email_address') == $viewer_email) {
					$viewer_email = $this->session->userdata('email_address');
					$ErrorMessage = "**You don't have to set up for yourself! **";
				}
				else {
					$get=$this->view_permission->Get($this->session->userdata('email_address'),$viewer_email);
					if ($get){
							$ErrorMessage = "**Duplicated Record. Please try again! **";
					}
					ELSE {
						$get1=$this->user->Get($viewer_email);
						if (!get1){
							$options=array(
								'table'=>'user',
								'email_address'=>$viewer_email,
								'first_name'=>$first_name,
								'last_name'=>$last_name
							);
							$this->general_model->_add($options);
							$get2=$this->user->Get($viewer_email);
							if($get2) {
								$owner_path = $get2->first_name.$get2->id;
								$id1= $get2->id;
								$this->user->Update1($get2->id,$owner_path);
							}				
						}
						else {
							$id1= $get1->id;
						}
						$options=array(
							'table'=>'view_permission',
							'user_id'=>$this->session->userdata('id'),
							'owner_email'=>$this->session->userdata('email_address'),
							'owner_path'=>$this->session->userdata('owner_path'),
							'is_active'=>9,
							'viewer_id'=>$id1,
							'viewer_email'=>$viewer_email,
							'first_name'=>$first_name,
							'last_name'=>$last_name
						);
						$this->general_model->_add($options);
					}
				}
			}
		}
		$ViewerList=$this->view_permission->Get_viewer_email($this->session->userdata('email_address'));
		$data['ViewerList']=$ViewerList;
		$data['ViewerCount']=count($ViewerList);
		if(isset($ErrorMessage)) $data['ErrorMessage']=$ErrorMessage;
		$this->load->view("addFriend_view",$data);
	}
}
