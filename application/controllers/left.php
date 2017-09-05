<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Left extends MY_Controller {
	public function index()
	{
		$show_id = 0;
		$pic_avail =0;
		if(isset($_POST['FriendID']))
		{	
			$FriendID = $this->input->post('FriendID');
		}
		elseif($this->session->userdata('id') && $this->session->userdata('FriendID')) $FriendID=$this->session->userdata('FriendID');
		elseif($this->session->userdata('id')) $FriendID = $this->session->userdata('id');
		else $FriendID = "Public";
		if($FriendID=="SharedPicture") $FriendID = $this->session->userdata('id');
		if($FriendID !='Public' && $this->session->userdata('id')){
			if($this->session->userdata('admin')!=1){
				if(isset($FriendID) && $this->session->userdata('id') != $FriendID){
					$get=$this->view_permission->Get_group(array('user_id'=>$FriendID,'viewer_id'=>$this->session->userdata('id')));
				}
				else {
					$get=$this->view_permission->Get_group(array('user_id'=>$this->session->userdata('id')));
				}
			}
			else $get=$this->view_permission->Get_group(array('user_id'=>$FriendID));
		}
		else $get=$this->view_permission->Get_group();
		$rows=0;
		if (!$get){
			foreach($get as $row)
			{	
				$curr_path = $row->owner_path;
				if($FriendID !='Public') $permit[$curr_path][] = $row->viewer_group;
				else $permit[$curr_path][] = 'Public';
				
			}
			foreach ($permit as $key => $value) {
				foreach ($value as $key2 => $value2) {
					$PicturePath = "/pictures/$key";
					if($FriendID !='Public') 
						$get1=$this->picture_video->Get_picture($value2,$key);
					else 
						$get1=$this->picture_video->Get_picture('Public');
					$count = 0;
					$upload_id = '';
					if($get1){
						foreach($get1 as $row3)
						{
							if ($row3->viewer_group=="$value2" || $row3->viewer_group == '') { 				
								$upload_id = $row3->upload_id;
								$countrow=true;
								if(isset($picture_group) && $row3->picture_video=="pictures") {
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
									if($row3->picture_video=="pictures") {
										$rows=1;
										$picture_group[$upload_id][] = $row3->name;
										$pic_avail=1;
									}
								}
								if ($show_id == 0) $show_id = $upload_id;
								$get2=$this->upload_infor->Get($upload_id);
								$description = '';
								if($get2){
									$UploadDate = $get2->upload_date;
									$description = $get2->description;
								}
								$picture_UploadDate[$upload_id] = $UploadDate;
								$picture_description[$upload_id] = $description;
								$count++;
							}
						}
					}					
					if($count > 0) $owner_list[] = $key;			
				}
			}
		}
		$pagenum = 1; 
		$page_rows = 4;  
		$last = ceil($rows/$page_rows); 
		if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
		$first_row=($pagenum -1)* $page_rows;
		$previous = $pagenum-1;
		if($previous == 0) $previous=1;
		$next = $pagenum+1;
		if($next > $rows) $next=$rows;
		$count=0;
		/* Share photo folder*/
		if($this->session->userdata('id')) {
			$this->load->model('pv_share');
			$get3=$this->pv_share->Get($this->session->userdata('id'));
			$shardCount=count($get3);
		}
	    $data['count']=$count;
		$this->load->view('PictureGroup_view',$data);
	}
}
