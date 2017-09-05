<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CommentList2 extends MY_Controller {
	public function index()
	{
		$upload_id=$this->input->get('upload_id');
		$type=$this->input->get('type');
		if($this->input->get('pagenum')) $pagenum=$this->input->get('pagenum');
		else $pagenum = 1; 
		if($this->input->get('page_rows')) $page_rows=$this->input->get('page_rows'); 
		else $page_rows = 2;  
		$max = 'limit ' .($pagenum - 1) * $page_rows .',' .$page_rows; 
		$offset=($pagenum - 1) * $page_rows;
		if(isset($upload_id))	{
			$queryComment=$this->general_model->_get(array('table'=>'pv_comment','upload_id'=>$upload_id,'type'=>$type,'sortBy'=>'id','sortDirection'=>'desc','limit'=>page_rows,'offset'=>$offset));
			if($queryComment){
				foreach($queryComment as $row)
				{		
					$queryUser=$this->general_model->_get(array('table'=>'user','id'=>$row->viewer_user_id));
					if($queryUser){
						foreach($queryUser as $row2)
						{		
							$Currname=$row2->first_name." ".$row2->last_name;
							$Currprofile_picture=$row2->profile_picture;
							echo "<div style=\"display:inline-block;\"><img src=\"$Currprofile_picture\" height=\"37\"></div>";
						}
					}					
					$date1= strtotime($row->comment_date);
					$comment_date= substr(date('r',$date1),0,-5);
					$CurrComment = "<font size=\"3\">".$row->comment."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
					echo "<div style=\"display:inline-block;vertical-align:top;\">".$CurrComment."</div><br/>";
				}
			}
		}
	}
}
