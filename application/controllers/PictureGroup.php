<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PictureGroup extends MY_Controller {
	public function index()
	{
		$show_id = 0;
		$pic_avail =0;
		if($this->input->get('FriendID'))
		{	
			$FriendID = $this->input->get('FriendID');
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
		else $get=$this->view_permission->Get_group(array());
		$rows=0;
		if ($get){
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
								$get2=$this->general_model->_get(array('table'=>'upload_infor','id'=>$upload_id));
								$description = '';
								if($get2){
									$UploadDate = $get2[0]->upload_date;
									$description = $get2[0]->description;
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
		$shardCount=0;
		if($this->session->userdata('id')) {
			$queryShare=$this->general_model->_get(array('table'=>'pv_share','shareto_id'=>$this->session->userdata('id')));
			if($queryShare)	$shardCount=count($queryShare);
		}
		$data['rows']=$rows;
		$data['page_rows']=$page_rows;
		$data['pagenum']=$pagenum;
		$data['previous']=$previous;
		$data['next']=$next;
		$data['first_row']=$first_row;
		$data['last']=$last;
		
	    $data['count']=$count;
	    $data['FriendID']=$FriendID;
	    $data['pic_avail']=$pic_avail;
	    $data['picture_UploadDate']=$picture_UploadDate;
	    $data['picture_description']=$picture_description;
	    if(isset($show_id)) $data['show_id']=$show_id;
	    if(isset($queryShare))$data['queryShare']=$queryShare;
	    if(isset($picture_group))$data['picture_group']=$picture_group;
	    $data['shardCount']=$shardCount;
		$this->load->view('PictureGroup_view',$data);
	}
}
