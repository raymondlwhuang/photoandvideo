<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PRemove2 extends MY_Controller {
	public function index()
	{
		if(isset($_GET['FriendID']))
		{
			$FriendID=$this->input->get('FriendID');
			$get=$this->user->_get1(array('id'=>$FriendID));
			if($get)
			{
				$owner_path=$get->owner_path;
				$user_id=$get->id;
			}
			$rows=0;
			$get1=$this->picture_video->_get(array('owner_path'=>$owner_path,'picture_video'=>'pictures'));
			$count = 0;
			$upload_id = '';
			if($get1)
				foreach($get1 as $row3)
				{
					$upload_id = $row3->upload_id;
					$countrow=true;
					if(isset($picture_group)) {
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
						$rows=1;
						$picture_group[$upload_id][] = $row3->name;
					}
					$get2=$this->general_model->_get(array('table'=>'upload_infor','id'=>$upload_id));
					$description = '';
					if($get2){
						foreach($get2 as $row4)
						{
							$UploadDate = $row4->upload_date;
							$description = $row4->description;
						}
					}
					$picture_UploadDate[$upload_id] = $UploadDate;
					$picture_description[$upload_id] = $description;
					$count++;
				}	
			}
			$pagenum = 1; 
			$page_rows = 49;  
			$last = ceil($rows/$page_rows); 
			if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
			$first_row=($pagenum -1)* $page_rows;
			$previous = $pagenum-1;
			if($previous == 0) $previous=1;
			$next = $pagenum+1;
			if($next > $rows) $next=$rows;
			$count=0;
			$output="";
			if(isset($picture_group)) {
				$listcount=0;
				foreach ($picture_group as $key3 => $value3) {
					$output.= "<div style='display:inline-block;' >";
					$count=0;
					$listcount++;
					if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
						foreach ($value3 as $key4 => $value4) {
							$infor=newencode("$key3,$value4,$picture_UploadDate[$key3],$picture_description[$key3],$FriendID");
							if($key4==0) $output.= "<input type=\"image\" src='ImgOnImgWithBorder.php?second_img=$value4' alt='' onClick=\"Action('../PHP/LastActivity.php?user_id=$user_id','maincontent');refreshiframe('$infor');\"/>";
						}
						$encode=newencode($key3);
						$output.= "<a href='PRemove.php?link=$encode'  onclick=\"return confirm('Are you sure you want to delete this folder?');\"><img src='../images/delete.png' alt='delete' width='25' /></a>";
						$output.= "<br/>$picture_UploadDate[$key3]<br/>";
						$output.= "$picture_description[$key3]<br/>";
					}
					$output.= "</div>";
				}
			}
			echo $output;
		}
	}
}
