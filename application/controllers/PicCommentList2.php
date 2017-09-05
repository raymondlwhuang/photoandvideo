<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PicCommentList2 extends MY_Controller {
	public function index()
	{
		$upload_id=$this->input->get('upload_id');
		$pv_id=$this->input->get('pv_id');
		if($this->input->get('pagenum')) $pagenum = $this->input->get('pagenum');
		$page_rows = 6;
		$Comcount=0;
		if($upload_id=="SharedPicture") $queryComment=$this->general_model->_get(array('table'=>'pv_comment','PV_id'=>$pv_id,'sortBy'=>'id','sortDirection'=>'desc'));
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
		if (!(isset($pagenum)))	$pagenum = 1; 
		$last = ceil($Comcount/$page_rows); 
		if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $last) $pagenum = $last; 
		$first_row=($pagenum -1)* $page_rows;
		$previous = $pagenum-1;
		if($previous <= 0) $previous=1;
		$next = $pagenum+1;
		if($next > $Comcount) $next=$Comcount;
			echo "<div id=\"ComNav\" style=\"width:17px;display:inline-block;vertical-align:top;\">";
				echo "<img src=\"../images/first_up2.png\" id='first' onClick=\"CommentList('first','$upload_id',0);\" style=\"border:none;\">";
				echo "<img src=\"../images/previous_up2.png\" id='previous'  onClick=\"CommentList('previous','$upload_id',0);\" style=\"border:none;\">";
				echo "<img src=\"../images/next_up.png\" id='next' onClick=\"CommentList('next','$upload_id',0);\" style=\"border:none;\">";
				echo "<img src=\"../images/last_up.png\" id='last'  onClick=\"CommentList('last','$upload_id',0);\" style=\"border:none;\">";
			echo "</div>";
			echo '<div id="comments" style="width:580px;display:inline-block;vertical-align:top;font-size:28px;text-align:left;">';
			if(isset($comments)) {
				$listcount=0;
				foreach ($comments as $key => $comment) {
					$listcount++;
					if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
						echo "<div>";
							echo '<div style="display:inline-block;">';
							echo "<img src=\"$result_profile_picture[$key]\"  height=\"38\" style=\"border:none;\">";
							echo "</div>";
							echo $comment;
						echo "</div>";
					}
				}
			}
			echo "</div>";
		echo <<<_END
		<script type="text/javascript">
		pagenum = 1;
		last = $last;
		Comcount = $Comcount;

		if($Comcount<=$page_rows) {
			if(document.getElementById('ComNav')) document.getElementById('ComNav').style.display = "none";
			if(document.getElementById('first')) document.getElementById('first').style.display = "none";
			if(document.getElementById('previous')) document.getElementById('previous').style.display = "none";
			if(document.getElementById('next')) document.getElementById('next').style.display = "none";
			if(document.getElementById('last')) document.getElementById('last').style.display = "none";
		}
		else {
			if(document.getElementById('ComNav')) document.getElementById('ComNav').style.display = "inline-block";
			if(document.getElementById('first')) document.getElementById('first').style.display = "inline-block";
			if(document.getElementById('previous')) document.getElementById('previous').style.display = "inline-block";
			if(document.getElementById('next')) document.getElementById('next').style.display = "inline-block";
			if(document.getElementById('last')) document.getElementById('last').style.display = "inline-block";
		}
		</script>
_END;
	}
}
