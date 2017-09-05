<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PicCommentList extends MY_Controller {
	public function index()
	{
		$upload_id=$this->input->get('upload_id');
		$pv_id=$this->input->get('pv_id');
		if($this->input->get('pagenum')) $pagenum=$this->input->get('pagenum');
		$page_rows = 6;
		$Comcount=0;
		if($upload_id=="SharedPicture")$queryComment=$this->general_model->_get(array('table'=>'pv_comment','PV_id'=>$pv_id,'sortBy'=>'id','sortDirection'=>'desc'));
		else $queryComment=$this->general_model->_get(array('table'=>'pv_comment','upload_id'=>$upload_id,'sortBy'=>'id','sortDirection'=>'desc'));
		if($queryComment){
			foreach($queryComment as $row4)
			{	
				if($row4->PV_id==0 || $row4->PV_id==$pv_id) {
					$Comcount++;
					$queryUser=$this->general_model->_get(array('table'=>'user','id'=>$row4->viewer_user_id));
					if($queryUser){
						foreach($queryUser as $row6)
						{		
							$name=$row6->first_name." ".$row6->last_name;
							$result_profile_picture[]=$row6->profile_picture;
						}
					}
					$date1= strtotime($row4->comment_date);
					$comment_date= substr(date('r',$date1),0,-6);
					$queryPV=$this->general_model->_get(array('table'=>'picture_video','upload_id'=>$row4->upload_id,'limit'=>1));
					if($queryPV){
						foreach($queryPV as $row5)
						{
							$pic_group[]=$row5->viewer_group;
						}
					}
					if($row4->PV_id==0) $color='darkblue';
					else $color='black';
					$comments[] = "<div style=\"display:inline-block;vertical-align:top;text-align:left;color:$color;\">".$row4->comment."<font size='3'>($comment_date - $name)</font></div>";
					$pic_upload_id[]=$row4->upload_id;
					$owner_id[]=$row4->user_id;
				}
			}
		}
		$last = ceil($Comcount/$page_rows); 
		if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
		$first_row=($pagenum -1)* $page_rows;
		$previous = $pagenum-1;
		if($previous <= 0) $previous=1;
		$next = $pagenum+1;
		if($next > $Comcount) $next=$Comcount;
			if(isset($comments)) {
				$listcount=0;
				foreach ($comments as $key => $comment) {
					$listcount++;
					if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
						echo "<div>";
							echo '<div style="display:inline-block;">';
							echo "<img src=\"$result_profile_picture[$key]\" height=\"38\" style=\"border:none;\">";
							echo "</div>";
							echo $comment;
						echo "</div>";
					}
				}
			}
	}
}
