<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModifyPaidStatus extends MY_Controller {
	public function index()
	{
		$cloak_keyword = "raymond".$this->session->userdata('pin');
		$paid = $this->input->get('paid');
		$user_id = $this->session->userdata('id');
		$bank_id = $this->input->get('bank_id');
		$action = $this->input->get('action');
		$paid_status = $this->input->get('paid_status');
		$e = new endec(); 
		$zero = $e->new_encode("0");
		if($paid==1) {
			$BankResult=$this->general_model->_get(array('table'=>'sp_bank','id'=>$bank_id));
			if($BankResult){
				foreach($BankResult as $OnHand) {
					if($OnHand->balance == '') $CashOnHand = 0;
					else $CashOnHand = $e->new_decode($OnHand->balance);
				}
			}
			$GetBalance=$this->general_model->_get(array('table'=>'spending','user_id'=>$user_id,'bank_id'=>$bank_id,'paid'=>3));
			if($GetBalance){
				foreach($GetBalance as $OnHandResult) {
					$expenses = $e->new_decode($OnHandResult->expenses);
					$CashOnHand = $CashOnHand - $expenses;
				}
			}
			$balance = $e->new_encode("$CashOnHand");	
			$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$balance));
			$refer_date = $this->input->get('paid_date');
			$fmyear=substr($refer_date,6,4);
			$fmmonth=substr($refer_date,0,2);
			$fmday=substr($refer_date,3,2);
			$paid_date = $fmyear."-".$fmmonth."-".$fmday;		
			$this->general_model->_update(array('table'=>'spending','user_id'=>$user_id,'bank_id'=>$bank_id,'paid'=>3),array('paid'=>1,'paid_date'=>$paid_date));
		}
		else {
			if($action=='update'){
				$id = $this->input->get('id');
				$this->general_model->_update(array('table'=>'spending','id'=>$id),array('paid'=>$paid));
			}
		}
		$todate = date('Y-m-d');
		$newdate = strtotime ( "+ 1 day" , strtotime ( $todate )) ;
		$todate = date('Y-m-d',$newdate);
		$newdate = strtotime ( "-3 month" , strtotime ( $todate ) ) ;
		$fmdate = date ( 'Y-m-d' , $newdate );
		$this->load->model('spending');
		$queryResult=$this->spending->Get(array('user_id'=>$user_id,'bank_id'=>$bank_id,'sortBy'=>'type_id','paid_status'=>$paid_status),$fmdate,$todate);
		$CategoryResult=$this->general_model->_get(array('table'=>'spending_category','user_id'=>0,'sortBy'=>'category'));
		if($CategoryResult){
			foreach($CategoryResult as $option1) {
				$optionCategory[$option1->id] = $option1->category;
			}
		}
		$TypeResult=$this->general_model->_get(array('table'=>'sp_payment_type','user_id'=>0));
		if($TypeResult){
			foreach($TypeResult as $option6) {
				$optionType[$option6->id] = $option6->Type;
			}
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
		
		$data['cloak_keyword']=$cloak_keyword;
		$data['paid_status']=$paid_status;
		$data['optionCategory']=$optionCategory;
		$data['optionItem']=$optionItem;
		$data['optionSpender']=$optionSpender;
		$data['optionType']=$optionType;
		$data['curr_balance']=$curr_balance;
		if($queryResult) $data['queryResult']=$queryResult;
		$this->load->view("modifyPaidStatus_view",$data);
	}
}
