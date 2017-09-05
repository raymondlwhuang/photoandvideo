<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SetReminder extends MY_Controller {
	public function index()
	{
		$cloak_keyword = "raymond".$this->session->userdata('pin');
		$e = new endec();
		$user_id = $this->session->userdata('id');
		$spender_id = $this->input->post('spender_id');
		$category_id = $this->input->post('category_id');
		$item_id = $this->input->post('item_id');
		$reminder = $this->input->post('reminder');
		$amount = $this->input->post('amount');
		$type_id = $this->input->post('type_id');
		$bank_id = $this->input->post('bank_id');
		$to_bank = $this->input->post('to_bank');
		$amount = $e->new_encode("$amount");
		$start_date = date('Ymd');
		if($reminder==2) {
			$newdate = strtotime ( "+1 day" , strtotime ( $start_date ) ) ;
		}
		elseif($reminder==3) {
			$newdate = strtotime ( "+1 week" , strtotime ( $start_date ) ) ;
		}
		elseif($reminder==4) {
			$newdate = strtotime ( "+2 week" , strtotime ( $start_date ) ) ;
		}
		elseif($reminder==5) {
			$newdate = strtotime ( "+1 month" , strtotime ( $start_date ) ) ;
		}
		elseif($reminder==6) {
			$newdate = strtotime ( "+3 month" , strtotime ( $start_date ) ) ;
		}
		elseif($reminder==7) {
			$newdate = strtotime ( "+6 month" , strtotime ( $start_date ) ) ;
		}
		else {
			$newdate = strtotime ( "+1 year" , strtotime ( $start_date ) ) ;
		}

		$activated = date ( 'Ymd' , $newdate );
		if($reminder==5) {
			if(date('d') != date('d',$newdate)) $activated = date('Ymd', strtotime('last day of next month', strtotime ( $start_date )));
		}
		$beforeInsert=$this->general_model->_get(array('table'=>'sp_reminder','user_id'=>$user_id,'spender_id'=>$spender_id,'category_id'=>$category_id,'item_id'=>$item_id,'frequency_id'=>$reminder,'type_id'=>$type_id,'bank_id'=>$bank_id,'to_bank_id'=>$to_bank,'activated'=>$activated));
		if(!$beforeInsert){
			$this->general_model->_add(array('table'=>'sp_reminder','user_id'=>$user_id,'spender_id'=>$spender_id,'category_id'=>$category_id,'item_id'=>$item_id,'frequency_id'=>$reminder,'amount'=>$amount,'type_id'=>$type_id,'bank_id'=>$bank_id,'to_bank_id'=>$to_bank,'start_date'=>$start_date,'activated'=>$activated));
			echo "Reminder is set now!";
		}
		else echo "Duplicate reminder! operation canceled!";
	}
}
