<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PictureList extends MY_Controller {
	public function index()
	{
		$show_id = 0;
		if($this->input->get('FriendID')) $FriendID = $this->input->get('FriendID'); else $FriendID = 'Public';
		$viewer_id=$this->input->get('viewer_id');
		$rows=0;
		if($FriendID !='Public'){
			if($FriendID!=$viewer_id){
				$resultPermit=$this->general_model->_get(array('table'=>'view_permission','user_id'=>$FriendID,'viewer_id'=>$viewer_id,'groupBy'=>'viewer_group'));
			}
			else {
				$resultPermit=$this->general_model->_get(array('table'=>'view_permission','user_id'=>$FriendID,'groupBy'=>'viewer_group'));
			}
		}
		else $resultPermit=$this->general_model->_get(array('table'=>'view_permission','groupBy'=>'viewer_group'));
		if ($resultPermit){
			foreach($resultPermit as $row)
			{	
				$curr_path = $row->owner_path;
				if($FriendID !='Public') $permit[$curr_path][] = $row->viewer_group;
				else $permit[$curr_path][] = 'Public';
			}
			foreach ($permit as $key => $value) {
				$count = 0;
				$upload_id = '';
				foreach ($value as $key2 => $value2) {
					$PicturePath = "/pictures/$key";
					if($FriendID !='Public') $resultPicture=$this->picture_video->_get(array('picture_video'=>'pictures','sortBy'=>'id','sortDirection'=>'desc'),array('name'=>$PicturePath));
					else $resultPicture=$this->general_model->_get(array('table'=>'picture_video','picture_video'=>'pictures','viewer_group'=>'Public','sortBy'=>'id','sortDirection'=>'desc'));
					if($resultPicture){
						foreach($resultPicture as $row3)
						{
							if ($row3->viewer_group=="$value2" || $row3->viewer_group=='') { 				
								$upload_id = $row3->upload_id;
								if(isset($picture_group)) {
									$dosave=true;
									foreach ($picture_group as $key5 => $value5) {
										foreach ($value5 as $key6 => $value6) {
											if($key5==$upload_id && $value6==$row3->name) $dosave=false;
										}
									}
									if($dosave) {
										$rows++;
										$picture_group[$upload_id][] = $row3->name;
									}
								}
								else {
									$rows=1;
									$picture_group[$upload_id][] = $row3->name;
								}
								if ($show_id == 0) $show_id = $upload_id;
								$description = '';
								$resultupload_infor=$this->general_model->_get(array('table'=>'upload_infor','id'=>$upload_id));
								if($resultupload_infor){
									foreach($resultupload_infor as $row4)
									{
										$UploadDate = $row4->upload_date;
										$description = $row4->description;
									}
								}
								$picture_UploadDate[$upload_id]=$UploadDate;
								$picture_description[$upload_id]=$description;
								$count++;
							}
						}
					}
					if($count > 0) $owner_list[] = $key;			
				}
			}
		}
		if($this->input->get('pagenum')) $pagenum =$this->input->get('pagenum'); 
		else $pagenum = 1; 
		$page_rows = 4;  
		$last = ceil($rows/$page_rows); 
		if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
		$first_row=($pagenum -1)* $page_rows;

		$count=0;
		if(isset($picture_group)) {
			$listcount=0;
			foreach ($picture_group as $key3 => $value3) {
				$count=0;
				$listcount++;
				if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
					foreach ($value3 as $key4 => $value4) {
						$count++;
						if($count>5) break;
						if($key4==0) echo "<input type=\"image\" src='".base_url()."ImgOnImgWithBorder?second_img=.$value4' alt='' onClick=\"Action('/LastActivity?user_id=$FriendID','maincontent');refreshiframe('$key3');\"/><br/>";
						else echo "<img src='$value4' width='35'/>";
					}
					echo "<br/>$picture_UploadDate[$key3]<br/>";
					echo "$picture_description[$key3]<br/>";
				}
			}
		}		
	}
}
