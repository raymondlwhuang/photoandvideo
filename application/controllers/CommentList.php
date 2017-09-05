<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CommentList extends MY_Controller {
	public function index()
	{
		$user_id=(int)$this->input->get('FriendID');
		if($this->input->get('pagenum')) $pagenum=$this->input->get('pagenum');
		else $pagenum = 1; 
		$page_rowsC=4;
		$maxC = 'limit ' .($pagenum - 1) * $page_rowsC .',' .$page_rowsC; 
		$offset=($pagenum - 1) * $page_rowsC;
		$queryComment=$this->general_model->_get(array('table'=>'pv_comment','viewer_user_id'=>$user_id,'sortBy'=>'id','sortDirection'=>'desc','limit'=>$page_rowsC,'offset'=>$offset));
		if($queryComment){
			foreach($queryComment as $row4)
			{	
				$queryUser=$this->general_model->_get(array('table'=>'user','id'=>$row4->user_id));
				if($queryUser){
					foreach($queryUser as $row6)
					{		
						$name=$row6->first_name." ".$row6->last_name;
						$profile_picture=$row6->profile_picture;
					}
				}
				$queryPV=$this->general_model->_get(array('table'=>'picture_video','upload_id'=>$row4->upload_id,'limit'=>1));
				if($queryPV){
					foreach($queryPV as $row5)
					{	
						$pic_group=$row5->viewer_group;
					}
				}
				$date1= strtotime($row4->comment_date);
				$comment_date= substr(date('r',$date1),0,-15);
				$comment_tmp="<input type=\"image\" src=\"../images/view.png\" name=\"view\" value=\"view\" width=\"16\" onClick=\"window.open('CommentPicture?owner_id=$row4->user_id&viewer_group=$pic_group&upload_id=$row4->upload_id',target='_top');\">";

				$comment_tmp.=$row4->comment.$row4->upload_id."<font color='darkblue'> on $name"."'s</font> ";
				if($row4->type==1) $comment_tmp.="<font color='blue'>video</font>($comment_date)<br/>";
				else $comment_tmp.="<font color='red'>photo</font>($comment_date)</font><br/>";
				
				echo $comment_tmp;
			}
		}
	}
}
