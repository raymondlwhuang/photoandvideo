<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PicRemove extends MainController {
	public function index()
	{
		if(isset($_GET['infor'])){
			$infor=$this->function_model->newdecode($this->input->get('infor'));
			$pieces = explode(",", $infor);
			$upload_id = (int)$pieces[0];
			$picture = $pieces[1];
			$pic_date = $pieces[2];
			$pic_desc = $pieces[3];
			$FriendID=(int)$pieces[4];
			$get=$this->user->Get($FriendID);
			if($get)
			{
				$owner_path=$get->owner_path;
			}	
		}
		if(isset($_GET['folder']))
		{
				$folder = $this->function_model->newdecode($this->input->get('folder'));
				$get=$this->picture_video->_get(array('upload_id'=>$folder));
				if ($get){
					foreach($get as $rowDelete)
					{
						$Orgname="/Orgpictures".substr($rowDelete->name,11);
						unlink($rowDelete->name);
						unlink($Orgname);
						$this->general_model->_delete(array('table'=>'pv_share','pv_id'=>$rowDelete->id));
						$this->general_model->_delete(array('table'=>'picture_video','id'=>$rowDelete->id));
					}
				}
				$this->general_model->_delete(array('table'=>'upload_infor','id'=>$folder));
				$this->general_model->_delete(array('table'=>'pv_comment','upload_id'=>$folder));
				redirect(base_url(). 'pRemove');				
/*				
echo <<<_END
<script type="text/javascript">
window.open('PRemove.php',target='_top');
</script>
_END;
		
exit();	
*/	
		}
		if(isset($_GET['link']))
		{
			$link=$this->function_model->newdecode($this->input->get('link'));
			$pieces = explode(",", $link);
			$id = (int)$pieces[0];
			$name = $pieces[1];
			$upload_id = (int)$pieces[2];
			$pic_date = $pieces[3];
			$pic_desc = $pieces[4];
			$picture = $pieces[5];
			$OK=$this->general_model->_delete(array('table'=>'picture_video','id'=>$id));
			if($OK) {
				$this->general_model->_delete(array('table'=>'pv_share','pv_id'=>$id));
				$Orgname="/Orgpictures".substr($name,11);
				unlink($Orgname);
				unlink($name);
			}
		}
		$result=$this->picture_video->_get('upload_id'=>$upload_id,'sortBy'=>'upload_id');
		if (!$result){
			$this->general_model->_delete(array('table'=>'upload_infor','id'=>$upload_id));
			redirect(base_url(). 'pRemove');
			/*
echo <<<_END
<script type="text/javascript">
//window.open('PRemove.php',target='_top');
</script>
_END;

exit();			
*/
		}
		else {
			if(isset($name) && $name==$picture) {
				$get1=$this->picture_video->_get(array('upload_id'=>$upload_id,'SortBy'=>'upload_id','limit'=>1));
				if($get1){
					$picture=$get1->name;
				}
			}
		}
		$this->data['upload_id']=$upload_id;
		$this->data['pic_date']=$pic_date;
		$this->data['pic_desc']=$pic_desc;
		$this->data['result']=$result;
		$this->load->view('main_header');
		$detect = new Mobiledtect;
		$this->data['layout'] = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
		$this->load->view("header",$this->data);
		$this->load->view("picRemove_view",$this->data);
	}
}
