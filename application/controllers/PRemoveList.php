<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PRemoveList extends MY_Controller {
	public function index()
	{
		$user_id=$this->input->get('user_id');
		$owner_path= $this->input->get('owner_path');
		if(isset($_get['pagenum'])) $pagenum = $this->input->get('pagenum');
		else $pagenum = 1; 
		$page_rows=49;
		$max = 'limit ' .($pagenum - 1) * $page_rows .',' .$page_rows; 
			$rows=0;
			$get=$this->picture_video->Get0($owner_path);
			$count = 0;
			$upload_id = '';
			if($get){
				foreach($get as $row3)
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
					$get1=$this->general_model->_get(array('table'=>upload_infor,'id'=>$upload_id));
					if($get1){
						foreach($get1 as $row4)
						{
							$picture_UploadDate[$upload_id] = $row4->upload_date;
							$picture_description[$upload_id] = $row4->description;
							$count++;
						}
					}						
				}
			}

		$last = ceil($rows/$page_rows); 
		if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
		$first_row=($pagenum -1)* $page_rows;
		$count=0;
		if(isset($picture_group)) {
			$listcount=0;
			foreach ($picture_group as $key3 => $value3) {
					echo "<div style='display:inline-block;' >";
					$count=0;
					$listcount++;
					if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
						foreach ($value3 as $key4 => $value4) {
							if($key4==0) echo "<input type=\"image\" src='ImgOnImgWithBorder?second_img=$value4' alt='' onClick=\"Action('LastActivity?user_id=$user_id','maincontent');refreshiframe('$key3');\"/>";
						}
						echo "<a href='PRemove?link=$key3'  onclick=\"return confirm('Are you sure you want to delete this folder?');\"><img src='/images/delete.png' alt='delete' width='25' /></a>";
						echo "<br/>$picture_UploadDate[$key3]<br/>";
						echo "$picture_description[$key3]<br/>";
					}
					echo "</div>";
			}
		}
	}
}
