<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BankingDetail extends MY_Controller {
	public function index()
	{
		$this->load->model('spending');
		if($this->session->userdata('private') != "yes")
		{
			$this->session->set_userdata('page',"HER");
			redirect('/', 'refresh');
		}
		if(!$this->session->userdata('pin'))
		{
			redirect('/getPin', 'refresh');
		}
		$cloak_keyword = "raymond".$this->session->userdata('pin');
		$e = new endec(); 
		$zero = $e->new_encode("0");
		$bank_id = $this->input->get('bank_id');

		$todate = substr($now,0,10)."00000000";
		$replaceStr = array("-", ":", " ");
		$todate = str_replace($replaceStr,"",$todate);
		$fmdate = substr($todate,0,6)."01000000";
		$daysdiff = date('d') + 1;
		$RangResult=$this->spending->Get(array('user_id'=>$this->session->userdata('id'),'bank_id'=>$bank_id),$fmdate,$todate);
		$BankResult=$this->general_model->_get(array('table'=>'sp_bank','user_id'=>$this->session->userdata('id'),'id'=>$bank_id));
		if($BankResult){
			foreach($BankResult as $option) {
				$BankName = $option->bank;
				if($option->balance=='') $balance = 0; else $balance = $e->new_decode($option->balance);
			}
		}
		$CategoryResult=$this->general_model->_get(array('table'=>'spending_category','user_id'=>0,'sortBy'=>'category'));
		if($CategoryResult){
			foreach($CategoryResult as $option1) {
				$optionCategory[$option1->id] = $option1->category;
			}
		}
		$data['BankName']=$BankName;
		$data['balance']=$balance;
		if($RangResult) $data['RangResult']=$RangResult;
		$this->load->view("bankingDetail_view",$data);
	}
}
