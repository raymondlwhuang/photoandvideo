<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CreditPayOff extends MainController {
	public function index()
	{
		$this->db->cache_off();
		if(!$this->session->userdata('pin'))
		{
			redirect('/getPin', 'refresh');
		}
		$cloak_keyword = "raymond".$this->session->userdata('pin');
		$e = new endec(); 
		$zero = $e->new_encode("0");
		$bank_id = 0;
		$BankResult=$this->general_model->_get(array('table'=>'sp_bank','user_id'=>$this->session->userdata('id')));
		if($BankResult){
			foreach($BankResult as $option7) {
				if($bank_id == 0) $bank_id = $option7->id;
				$optionBank[$option7->id] = $option7->bank;
				if($option7->balance == '') $balance = 0; else $balance = $e->new_decode($option7->balance);
				$optionBalance[$option7->id] = $balance;
			}
		}
		$todate = date('Ymd')."000000";
		$newdate = strtotime ( "+ 1 day" , strtotime ( $todate )) ;
		$todate = date('Y-m-d',$newdate);
		$newdate = strtotime ( "-3 month" , strtotime ( $todate ) ) ;
		$fmdate = date ( 'Ymd' , $newdate )."000000";
		$this->load->model('spending');
		$queryResult=$this->spending->Get(array('user_id'=>$this->session->userdata('id'),'bank_id'=>$bank_id,'paid_status'=>'Unpaid','bank_id'=>3,'sortBy'=>'type_id'),$fmdate,$todate);
		$CategoryResult=$this->general_model->_get(array('table'=>'spending_category','user_id'=>0,'sortBy'=>'category'));
		foreach($CategoryResult as $option1) {
			$optionCategory[$option1->id] = $option1->category;
		}
		$TypeResult=$this->general_model->_get(array('table'=>'sp_payment_type','user_id'=>0));
		foreach($TypeResult as $option6) {
			$optionType[$option6->id] = $option6->Type;
		}
		$BalanceResult=$this->general_model->_get(array('table'=>'sp_bank','id'=>$bank_id));
		if($BalanceResult){
			foreach($BalanceResult as $Balance) {
				$curr_balance = $e->new_decode($Balance->balance);
			}
		}
		$querySpender=$this->general_model->_get(array('table'=>'spender'));
		if($querySpender){
			foreach($querySpender as $row){
				$optionSpender[$row->id]=$row->name;
			}
		}
		$ItemResult=$this->general_model->_get(array('table'=>'category_item'));
		if($ItemResult){
			foreach($ItemResult as $row2){
				$optionItem[$row2->id]=$row2->category_item;
			}
		}
		if($queryResult) $this->data['queryResult']=$queryResult;
		$this->data['optionBank']=$optionBank;
		$this->data['optionCategory']=$optionCategory;
		$this->data['optionItem']=$optionItem;
		$this->data['optionSpender']=$optionSpender;
		$this->data['optionType']=$optionType;
		$this->data['cloak_keyword']=$cloak_keyword;
		$this->data['curr_balance']=$curr_balance;
		$this->load->view('creditpayoff_header');
		$detect = new Mobiledtect;
		$this->data['layout'] = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
		$this->load->view("header",$this->data);
		$this->load->view("creditPayOff_view",$this->data);
	}
}
