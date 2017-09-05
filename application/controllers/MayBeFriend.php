<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MayBeFriend extends MY_Controller {
	public function index()
	{
		$user_id=$this->input->get('user_id');
		$get=$this->user->_get1(array('id'=>$this->input->get('user_id')));
		if($get)
		{
		 $email_address=$get->email_address;
		} 
		if(isset($_REQUEST['pagenum'])) $pagenum2 = (int)$_REQUEST['pagenum'];
		else $pagenum2 = 1; 
		$page_rows2=4;
		$max = 'limit ' .($pagenum2 - 1) * $page_rows2 .',' .$page_rows2; 
		$rows2=0;
		$get1=$this->view_permission->_get(array('viewer_id'=>$this->input->get('user_id')));
		if($get1){
			foreach($get1 as $option) {
				$get2=$this->view_permission->_get1(array('owner_email'=>$email_address));
				if(!$get2) {
					$get3=$this->user->_get1(array('email_address'=>$option->owner_email));
					if($get3) {
						$first_name=$get3->first_name;
						$last_name=$get3->last_name;
						$viewer_id=$get3->id;
						$curr_path=$get3->owner_path;
						if(isset($profile_picture2)) {
							$found=0;
							foreach ($profile_picture2 as $key2 => $value2) {
								if($key2==$curr_path && $value2==$get3->profile_picture) $found=1;
							}
							if($found==0) {
								$FriendID2[$curr_path] = $get3->id;
								$profile_picture2[$curr_path] = $get3->profile_picture;
								$name[$curr_path] = $first_name." ".$last_name;
								$rows2++;
							}
						}
						else {
							$FriendID2[$curr_path] = $get3->id;
							$profile_picture2[$curr_path] = $get3->profile_picture;
							$name[$curr_path] = $first_name." ".$last_name;
							$rows2++; 
						} 
					}
				}
				$get4=$this->view_permission->_get(array('owner_email'=>$option->owner_email));
				foreach($get4 as $option2) {
					$get5=$this->view_permission->_get1(array('owner_email'=>$email_address,'viewer_email'=>$option2->viewer_email));
					if(!$get5 && $option2->viewer_email!=$email_address) {
						$get6=$this->user->_get1(array('email_address'=>$option2->viewer_email));
						if($get6) {
							$first_name=$get6->first_name;
							$last_name=$get6->last_name;
							$viewer_id=$get6->id;
							$curr_path=$get6->owner_path;
							if(isset($profile_picture2)) {
								$found=0;
								foreach ($profile_picture2 as $key2 => $value2) {
									if($key2==$curr_path && $value2==$get6->profile_picture) $found=1;
								}
								if($found==0) {
									$FriendID2[$curr_path] = $get6->id;
									$profile_picture2[$curr_path] = $get6->profile_picture;
									$name[$curr_path] = $first_name." ".$last_name;
									$rows2++; 
								}
							}
							else {
								$FriendID2[$curr_path] = $get6->id;
								$profile_picture2[$curr_path] = $get6->profile_picture;
								$name[$curr_path] = $first_name." ".$last_name;
								$rows2++; 
							} 
						}		
					}
				}
				
			}
		}

		$last = ceil($rows2/$page_rows2); 
		if ($pagenum2 < 1) 
		{ 
			$pagenum2 = 1; 
		} 
		elseif ($pagenum2 > $last) 
		{ 
			$pagenum2 = $last; 
		} 
		$first_row=($pagenum2 -1)* $page_rows2;
		$count=0;
		if(isset($profile_picture2)) {
			foreach ($profile_picture2 as $key2 => $value2) {
			
			$count++;
			$ShowName=substr($name[$key2],0,25);
			$longstring = <<<STRINGBEGIN
			<a href="" onClick="SendRequest ('LastActivity?user_id=$user_id','maincontent');makefriend('$name[$key2]','$FriendID2[$key2]');return false;"><img src='$value2' width='67'/></a><br/><font size='2'>$ShowName</font><br/>
STRINGBEGIN;

				if($count > $first_row && $count <= ($first_row+$page_rows2)){
					echo $longstring;
				}
			}
		}

	}
}
