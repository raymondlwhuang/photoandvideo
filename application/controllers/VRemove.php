<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VRemove extends MainController {
	public function index()
	{
		if(isset($_GET['link']))
		{
			$link=$this->function_model->newdecode($this->input->get('link'));
			$pieces = explode(",", $link);
			$upload_infor = (int)$pieces[0];
			$pos=strpos($pieces[1] , ".",4) + 1;
			$name = substr($pieces[1],0,$pos)."%";
			$get=$this->picture_video->_get(array('upload_id'=>$upload_infor,'picture_video'=>'videos','sortBy'=>'upload_id','sortDirection'=>'asc'),array('name'=>"$name"));
//			$GetDelete=mysql_query("SELECT * FROM picture_video where upload_id = $upload_infor and picture_video = 'videos' and name like '$name' order by upload_id");
			if($get){
				foreach($get as $row) {
					$OK=$this->general_model->_delete(array('table'=>'picture_video','id'=>$row->id));
					if($OK) {
						$this->general_model->_delete(array('table'=>'pv_share','pv_id'=>$row->id));
						unlink($row->name);
					}
				}
			}
			$get1=$this->picture_video->_get1(array('upload_id'=>$upload_infor));
			if (!$get1){
				$this->general_model->_delete(array('table'=>'pv_comment','upload_id'=>$upload_infor));
				$this->general_model->_delete(array('table'=>'upload_infor','id'=>$upload_infor));
			}
		}
		$get2=$this->picture_video->_get(array('owner_path'=>$this->session->userdata('owner_path'),'picture_video'=>'videos','sortBy'=>'upload_id'));
		if($get2){
			foreach($get2 as $row2)
			{
				$pos=strpos($row2->name , ".",4) + 1;
				$VideoName=substr($row2->name,0,$pos);
				$AddVideo=1;
				if(isset($video)){
					foreach ($video as $key1 => $value1) {
						if($value1==$VideoName) $AddVideo=0;
					}
				}
				if($AddVideo!=0)
				{
					$video[]=$VideoName;
					$upload_id[] = $row2->upload_id;
				}
			}
		}
		$data=array();
		if(isset($video)) $data['video']=$video;
		if(isset($upload_id)) $data['upload_id']=$upload_id;
		$this->load->view('main_header');
		$detect = new Mobiledtect;
		$data['layout'] = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
		$this->load->view("header",$data);
		
		$this->load->view("vRemove_view",$data);
	}
}
