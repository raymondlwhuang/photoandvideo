<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CommentList3 extends MY_Controller {
	public function index()
	{
		$upload_id=$this->input->get('upload_id');
		$type=$this->input->get('type');
		if($this->input->get('pagenum')) $pagenum=$this->input->get('pagenum');
		else $pagenum = 1; 
		if($this->input->get('VideoCompage_rows')) $VideoCompage_rows=$this->input->get('VideoCompage_rows'); 
		else $VideoCompage_rows = 2;  
		$max = 'limit ' .($pagenum - 1) * $VideoCompage_rows .',' .$VideoCompage_rows; 
		$VideoComcount=0;
		if(isset($upload_id))	{
			$queryComment3=$this->general_model->_get(array('table'=>'pv_comment','upload_id'=>$upload_id,'type'=>$type,'sortBy'=>'id','sortDirection'=>'desc'));
			$VideoComcount=count($queryComment3);
			if($queryComment3){
				foreach($queryComment3 as $row13)
				{	
					$curr_user=$row13->viewer_user_id;
					$queryUser=$this->general_model->_get(array('table'=>'user','id'=>$row13->viewer_user_id));
					if($queryUser){
						foreach($queryUser as $row15)
						{		
							$Currname=$row15->first_name." ".$row15->last_name;
							$CurrVideoprofile_picture[]=$row15->profile_picture;
						}
					}
					$date1= strtotime($row13->comment_date);
					$comment_date= substr(date('r',$date1),0,-5);
					$CurrVideoComment[] = "<font size=\"3\">".$row13->comment."</font><font size=\"3\" color='darkblue'><br/>$comment_date ($Currname)</font><br/>";
					$Videoupload_id[]=$upload_id;
				}
			}
		$VideoCompage_rows = 2;  
		$VideoComplast = ceil($VideoComcount/$VideoCompage_rows); 
		if ($pagenum < 1) $pagenum = 1; elseif ($pagenum > $VideoComplast) $pagenum = $VideoComplast; 
		$first_row=($pagenum -1)* $VideoCompage_rows;
		$previous = $pagenum-1;
		if($previous <= 0) $previous=1;
		$next = $pagenum+1;
		if($next > $VideoComcount) $next=$VideoComcount;
		$VideoComplast = ceil($VideoComcount/$VideoCompage_rows); 
			if($VideoComcount==0)  $height="0px";
			elseif($VideoComcount==1)  $height="49px";
			else $height="98px";
			echo "<div class=\"PicNav\" style=\"height:$height;\">";
			echo "<img src=\"/images/first_up2.png\" id='VideoComfirst' onClick=\"CommentList3('first',$upload_id,1,0);\"><br/>";
			echo "<img src=\"/images/previous_up2.png\" id='VideoComprevious'  onClick=\"CommentList3('previous',$upload_id,1,0);\"><br/>";
			echo "<img src=\"/images/next_up.png\" id='VideoComnext' onClick=\"CommentList3('next',$upload_id,1,0);\"><br/>";
			echo "<img src=\"/images/last_up.png\" id='VideoComlast'  onClick=\"CommentList3('last',$upload_id,1,0);\">";
			echo "</div>";
			echo "<div class=\"PicNavbg\" style=\"height:$height;\">";
			echo "<div id=\"CurrVideoComment\" id=\"CurrVideoComment\">";
					$listcount=0;
			if(isset($CurrVideoComment)) {
					foreach ($CurrVideoComment as $key2 => $comment2) {
						$listcount++;
						if($listcount > $first_row && $listcount <= ($first_row+$VideoCompage_rows)){
							echo "<div class=\"block\"><img src=\"$CurrVideoprofile_picture[$key2]\" height=\"37\" class='img' ></div>";
							echo "<div class=\"block\">".$comment2."</div><br/>";
						}
					}				
			echo "</div>";
			echo "</div>";
			}	
		}
		if(VideoComcount==0||!$this->session->userdata(id)) $disp='none';
		else $disp='block';
		$str = <<<END
		<script type="text/javascript">
		VideoComcount=$VideoComcount;
		VideoCompage_rows=$VideoCompage_rows;
		VideoComplast=$VideoComplast;
		if(VideoComcount<=VideoCompage_rows) {
			document.getElementById('VideoComfirst').style.display = "none";
			document.getElementById('VideoComprevious').style.display = "none";
			document.getElementById('VideoComnext').style.display = "none";
			document.getElementById('VideoComlast').style.display = "none";
		}
		document.getElementById('VDComment').style.display = "$disp";
		</script>
END;
		echo $str;
	}
}
