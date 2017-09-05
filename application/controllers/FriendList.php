<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FriendList extends MY_Controller {
	public function index()
	{
		$user_id=$this->input->get('user_id');
		$get=$this->user->_get1(array('id'=>$user_id));
		if($get)
		{
			$owner_path1=$get->owner_path;
			$profile_picture1=$get->profile_picture;
			$name1=$get->first_name." ".$get->last_name;
			$friendid1=$get->email_address;
			$friendid1=$get->id;
		}

		if(isset($_GET['pagenum']))
			$pagenum = $this->input->get('pagenum');
		else
			$pagenum = 1; 
		$name['public'] = 'Public';
		$profile_picture['public'] = "/images/profile/public.jpg";
		$FriendID['public'] = 'Public';
		$name[$owner_path1] = $name1;
		$profile_picture[$owner_path1] = $profile_picture1;
		$FriendID[$owner_path1] = $friendid1;
		$PicturePath = "/pictures/$owner_path1";
		$get1=$this->picture_video->Get(array('PicturePath'=>"$PicturePath",'owner_path'=>"$owner_path1"));
		if($get1)
		{
			$upload_id[$owner_path1] = $get1->upload_id;
		}
		$rows = 2;
		$get2=$this->view_permission->_get(array('viewer_id'=>$friendid1));
		if($get2){
			foreach($get2 as $row2)
			{
				$curr_path = $row2->owner_path;
				$FriendID[$curr_path] = $row2->user_id;
				$get3=$this->user->_get1(array('email_address'=>"$row2->owner_email"));
				if($get3){
					$first_name=ucfirst(strtolower($get3->first_name));
					$last_name = ucfirst(strtolower($get3->last_name));
					$profile_picture[$curr_path] = $get3->profile_picture;
					$rows++; 
					$name[$curr_path] = $first_name." ".$last_name;
				}
				$PicturePath = "/pictures/$curr_path";
				$get4=$this->picture_video->Get($PicturePath,$curr_path);
				if($get4)
				{
					$upload_id[$curr_path] = $get4->upload_id;
				}
			}
		}
		 
		 //This is the number of results displayed per page 
		 $page_rows = 5; 
		 
		 //This tells us the page number of our last page 
		 $last = ceil($rows/$page_rows); 
		 
		 //this makes sure the page number isn't below one, or more than our maximum pages 
		 if ($pagenum < 1) 
		 { 
		 $pagenum = 1; 
		 } 
		 elseif ($pagenum > $last) 
		 { 
		 $pagenum = $last; 
		 } 
		$first_row=($pagenum -1)* $page_rows; 
		$count=0;
		if($pagenum==1) echo "<b>Public</b><br/>";
		foreach ($profile_picture as $key => $value) {
		$count++;
		if(isset($upload_id[$key])) $show_id = $upload_id[$key]; else $show_id = '';
		if($name[$key]=='Public') $ShowName="<font color='red'><b>Friends</b></font>"; else $ShowName=substr($name[$key],0,25);
		$longstring = <<<STRINGBEGIN
		<a href="" onClick="SendRequest('LastActivity?user_id=$user_id','maincontent');refreshiframe('$name[$key]','$FriendID[$key]','$value','$show_id','$key');return false;"><img src='$value' width='67'/></a><br/><font size='2'>$ShowName</font><br/>
STRINGBEGIN;
			if($count > $first_row && $count <= ($first_row+$page_rows)){
				echo $longstring;
			}
		}

	}
}
