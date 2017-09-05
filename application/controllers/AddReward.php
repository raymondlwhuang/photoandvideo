<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddReward extends MY_Controller {
	public function index()
	{
		if($this->input->post('Save_x'))
		{
			$description = $this->input->post('description');
			$rewardid = $this->input->post('rewardid');
			$amount = $this->input->post('amount');
			$signature = $this->input->post('signature');
			$this->general_model->_add(array('table'=>'kidsreward','description'=>$description,'rewardid'=>$rewardid,'amount'=>$amount,'signature'=>$signature,'date'=>date('Y-m-d')));
		 }
		$todate = date('Y-m-d');
		$result=$this->general_model->_get(array('table'=>'kidsreward','date'=>$todate));
		$data=array();
		if($result) $data['result']=$result;
		if(isset($description)) $data['description']=$description;
		if(isset($ErrorMessage)) $data['ErrorMessage']=$ErrorMessage;
		$this->load->view("addReward_view",$data);
	}
}
