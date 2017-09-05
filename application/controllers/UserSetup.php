<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserSetup extends MY_Controller {
	public function index()
	{
		if (isset($_POST['Submit']))
		{
			 $first_name = $this->input->post('first_name');
			 $last_name =  $this->input->post('last_name');
			 $email_address = strtolower($this->input->post('email_address'));
			 $password = $this->input->post('password');
			 $passwordConfirm = $this->input->post('passwordConfirm');
			 $username = $email_address;
			if (($first_name == "") 
				or ($last_name == "") 
				or ($password == "") 
				or ($passwordConfirm == "") 
				or !preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email_address)
			    )
			{
				if(($first_name == "") 
				or ($last_name == "") 
				or ($email_address == "") 
				or ($passwordConfirm == "") 
				or ($password == "") )
				{
					$ErrorMessage = "** Please fill in missing information **";
				}
				else $ErrorMessage = "** Invalid e-mail address **"; 	   
			}
			else {
				$first_name =  $this->input->post('first_name');
				$last_name = $this->input->post('last_name');
				$email_address =  strtolower($this->input->post('email_address'));
				$password =  MD5($this->input->post('password'));
				$get=$this->user->_get1(array('email_address'=>$email_address));
				if ($get){
					$this->general_model->_update(array('table'=>'user','email_address'=>$email_address),array('first_name'=>$first_name,'last_name'=>$last_name,'password'=>$password));
				}
				ELSE {
					$this->general_model->_add(array('table'=>'user','first_name'=>$first_name,'last_name'=>$last_name,'password'=>$password,'email_address'=>$email_address));
					$get1=$this->user->_get1(array('email_address'=>$email_address));
					if($get1){
						$id = $get1->id;
						$first_name = str_replace (" ", "", $first_name);
						$owner_path = "$first_name"."$get1->id";
						$this->general_model->_add(array('table'=>'spender','user_id'=>$get1->id,'name'=>'Me'));
						$get2=$this->general_model->_get(array('table'=>'spender','user_id'=>$get1->id,'limit'=>1));
						$spender_id = $get2[0]->id;
						$this->general_model->_add(array('table'=>'sp_bank','user_id'=>$get1->id,'spender_id'=>$spender_id,'bank'=>'Cash On Hand(Me)'));
					}
					$this->general_model->_update(array('table'=>'user','id'=>$get1->id),array('owner_path'=>$owner_path));
					$this->session->set_userdata('username',$email_address);
					$this->session->set_userdata('private',"yes");
				} /* SaveCheck.RecordCount */
				redirect('/', 'refresh');
			}
		}
		else if (isset($_GET['UserSetup']))
		{
			$ErrorMessage = "*** Please setup your account ***";
			$findme   = ':::';
			$SearchString = $this->function_model->newdecode($this->input->get('UserSetup'));
			$pos = strpos($SearchString, $findme);
			$email_address = strtolower(substr($SearchString,0,$pos));
			$ReqDate = strtotime (substr($SearchString,($pos+3)));
			$ExpirDate = strtotime ( '+7 day' , $ReqDate ) ;	
			$start_date=gregoriantojd(date('m'), date('d'), date('Y'));   
			$end_date=gregoriantojd(date('m',$ExpirDate), date('d',$ExpirDate), date('Y',$ExpirDate));   
			$daysdiff = $end_date - $start_date;
			if($daysdiff < 0) {
			echo <<<_END
			<script type="text/javascript">
			alert('Your requirement has expired!');
			//window.stop();
			window.open( '/', '_top');
			</script>
_END;
exit();
			}
			else {
				$get=$this->user->_get1(array('email_address'=>$email_address));
				if ($get){
					$first_name =  $get->first_name;
					$last_name = $get->last_name;
				}
			}			
		}
		else {
		echo <<<_END
		<script type="text/javascript">
		window.open( '/', '_top');
		</script>
_END;
exit();
		}
		if(isset($ErrorMessage)) $data['ErrorMessage']=$ErrorMessage;
		if(isset($first_name)) $data['first_name']=$first_name;
		if(isset($last_name)) $data['last_name']=$last_name;
		if(isset($email_address)) $data['email_address']=$email_address;
		$this->load->view("userSetup_view",$data);
	}
}
