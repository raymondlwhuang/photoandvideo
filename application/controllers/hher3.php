<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hher3 extends MY_Controller {
	public function index()
	{
		$cloak_keyword = "raymond".$this->session->userdata('pin');
		$e = new endec(); 
		$zero = $e->new_encode("0");
		$month_array = array ("1"=>"January","2"=>"February","3"=>"March","4"=>"April","5"=>"May","6"=>"June","7"=>"July","8"=>"August","9"=>"September","10"=>"October","11"=>"November","12"=>"December");
		$ErrorMessage = '';
		$user_id = $this->session->userdata('id');
		$category_id = $this->input->get('category_id');
		$item_id = $this->input->get('item_id');
		$comment_id = $this->input->get('comment_id');
		$amount = $this->input->get('amount');
		$amount_neg = $amount * (-1);
		$expenses = $e->new_encode("$amount");
		$expenses_neg = $e->new_encode("$amount_neg");
		$spender_id = $this->input->get('spender_id');
		$type_id = $this->input->get('type_id');
		$bank_id = $this->input->get('bank_id');
		$to_bank = $this->input->get('to_bank');
		$frequency_id = $this->input->get('frequency_id');
		$refer_date = $this->input->get('start_date');
		$year_month = $this->input->get('year_month');
		$yearly = $this->input->get('yearly');
		$fmyear=substr($refer_date,6,4);
		$fmmonth=substr($refer_date,0,2);
		$fmday=substr($refer_date,3,2);
		$toyear = date("Y");
		$tomonth = date("m");
		$today = date("d");
		$start_date = ($fmyear.$fmmonth.$fmday."000000");
		$curr_date = date("Y-m-d");
		$start_date1=gregoriantojd($fmmonth, $fmday, $fmyear);   
		$end_date=gregoriantojd($tomonth, $today, $toyear);   
		$daysdiff = $end_date - $start_date1;
		$date = "$fmyear-$fmmonth-$fmday";
		$this->load->model('spending_category');
		$CategoryResult=$this->spending_category->_get(array('user_id'=>$this->session->userdata('id'),'sortBy'=>'category'));
		if($CategoryResult){
			foreach($CategoryResult as $option1) {
				$optionCategory[$option1->id] = $option1->category;
			}
		}
		$this->load->model('item_frequency');
		$GetInsertData=$this->item_frequency->_get(array('user_id'=>$user_id,'activated'=>$curr_date));
		if($GetInsertData){
			foreach($GetInsertData as $ResultRow){
				$item_curr_id = $ResultRow->id;
				$reupdate_date = $ResultRow->activated."000000";
				$org_date = strtotime ($ResultRow->start_date."000000");
				$amount10 = $ResultRow->amount;
				$fq_amount10 = $e->new_decode("$amount10");
				$fq_amount10_neg = $e->new_decode("$amount10") * (-1);
				$amount10_neg = $e->new_encode("$fq_amount10_neg");
				$spender_id10 = $ResultRow->spender_id;
				$category_id10 = $ResultRow->category_id;
				$item_id10 = $ResultRow->item_id;
				$type_id10 = $ResultRow->type_id;
				$bank_id10 = $ResultRow->bank_id;
				$to_bank_id10 = $ResultRow->to_bank_id;
				$reupdateyear=substr($reupdate_date,0,4);
				$reupdatemonth=substr($reupdate_date,5,2);
				$reupdateday=substr($reupdate_date,8,2);
				$reupdate_date2 = ($reupdateyear.$reupdatemonth.$reupdateday."000000");
				$reupdate_date1=gregoriantojd($reupdatemonth, $reupdateday, $reupdateyear);   
				$daysdiff1 = $end_date - $reupdate_date1 + 1;
				
				$fq_Cash = 0;
				$BankResult4=$this->general_model->_get(array('table'=>'sp_bank','id'=>$bank_id10));
				if($BankResult4){
					foreach($BankResult4 as $fq_OnHand) {
						if($fq_OnHand->balance == '') $fq_Cash = 0;
						else $fq_Cash = $e->new_decode("$fq_OnHand->balance");
					}
				}				
				$fq_Cash_to = 0;
				$ToBankResult4=$this->general_model->_get(array('table'=>'sp_bank','id'=>$to_bank_id10));
				if($ToBankResult4){
					foreach($ToBankResult4 as $GetToBank4) {						
						if($GetToBank4->balance == '') $fq_Cash_to = 0;
						else $fq_Cash_to = $e->new_decode($GetToBank4->balance);
					}
				}
				$queryMonthly=$this->general_model->_get(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1));
				if($queryMonthly){
					foreach($queryMonthly as $CurrResult) {
						$fq_income2 = 0;
						$fq_expenses3 = 0;
						if($category_id10 == 1) {	
							$fq_income2 = $e->new_decode($CurrResult->monthly_income);
							$fq_income3 = $fq_income2 + $amount10;
							$fq_income4 = $e->new_encode($fq_income3);
						}
						else {
							$fq_expenses3 = $e->new_decode($CurrResult->monthly_expenese);
							$fq_expenses4 = $fq_expenses3 + $amount10;
							$fq_expenses5 = $e->new_encode($fq_expenses4);
						}
					}
				}
				$month3=date('m');
				$year3=date('Y');
				$MonthlyDetail3=$this->general_model->_get(array('table'=>'sp_monthly_detail','user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'month'=>$month3,'year'=>$year3,'limit'=>1));
				if ($MonthlyDetail3) {
					$fq_Monthly_id = $MonthlyDetail3[0]->id;
					$fq_expenses6 = $e->new_decode($MonthlyDetail3[0]->expenses);
					$fq_expenses7 = $fq_expenses6 + $amount10;
					$fq_expenses8 = $e->new_encode("$fq_expenses7");
				}
				else {
					$this->general_model->_add(array('table'=>'sp_monthly_detail','user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$zero,'month'=>$month3,'year'=>$year3));
					$MonthlyDetail4=$this->general_model->_get(array('table'=>'sp_monthly_detail','user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'month'=>$month3,'year'=>$year3,'limit'=>1));
					if($MonthlyDetail4) {
						$fq_Monthly_id = $MonthlyDetail4[0]->id;
					}
					$fq_expenses6 = 0;
					$fq_expenses7 = $amount10;
					$fq_expenses8 = $e->new_encode("$fq_expenses7");
				}	
				$fq_CashOnHand = 0;
				$fq_CashOnHand_to = 0;
				$fq_adjustment = 0;
				$fq_adjustment_neg = 0;
				$fq_amount_tot = $fq_income2;
				$fq_expenses_tot = $fq_expenses3;
				if($ResultRow->frequency_id == 2) {	/* Daily */
					for ($i = 1; $i <= $daysdiff1; $i++) {
						if($category_id10==1 && $item_id10 != 77) {
							$fq_CashOnHand = $fq_Cash + $fq_amount10;
							$fq_CashOnHand_to = 0;
						}
						else {
							$fq_CashOnHand = $fq_Cash - $fq_amount10;
							$fq_CashOnHand_to = $fq_Cash_to + $fq_amount10;
						}
						if($item_id10 != 77) {
							$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
						}
						else {
							$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10_neg,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
							$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
						}
						$fq_adjustment = $fq_adjustment + $fq_amount10;
						$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount10;
						$fq_amount_tot = $fq_amount_tot + $fq_amount10;
						$fq_expenses_tot = $fq_expenses_tot + $fq_amount10;
						$fq_expenses6 = $fq_expenses6 + $fq_amount10;
					}
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					$this->load->model('sp_monthly');
					if($category_id10==1) {
						if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id10 == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$itemRow=$this->general_model->_get(array('table'=>'category_item','id'=>item_id10,'limit'=>1));
						if($itemRow){
							$itemDesc = $itemRow->category_item;
							$description = "$optionCategory[$category_id10]=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id10),array('balance'=>$fq_balance));
							if($item_id10 == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id10),array('balance'=>$fq_balance_to));
							if($item_id10 != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id10 == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}
						}
					}
					
					$newdate = strtotime ( "+$daysdiff1 day" , strtotime ( $reupdate_date2 ) ) ;
					$activated = date ( 'Y-m-d' , $newdate );
					$this->general_model->_update(array('table'=>'item_frequency','id'=>$item_curr_id,'sortBy'=>'id','limit'=>1),array('activated'=>$activated));
				}
				else if ($ResultRow->frequency_id == 3) {		/* Weekly */
					$j = 0;
					for ($i = 1; $i <= $daysdiff1; $i=$i+7) {
						if($category_id10==1 && $item_id10 != 77) {
							$fq_CashOnHand = $fq_Cash + $fq_amount10;
							$fq_CashOnHand_to = 0;
						}
						else {
							$fq_CashOnHand = $fq_Cash - $fq_amount10;
							$fq_CashOnHand_to = $fq_Cash_to + $fq_amount10;
						}		
						if($item_id10 != 77) {
							$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
						}
						else {
							$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10_neg,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
							$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$to_bank_id10,'paid'=>1));
						}		
						$j++;
						$fq_adjustment = $fq_adjustment + $fq_amount10;
						$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount10;
						$fq_amount_tot = $fq_amount_tot + $fq_amount10;
						$fq_expenses_tot = $fq_expenses_tot + $fq_amount10;
						$fq_expenses6 = $fq_expenses6 + $fq_amount10;
					}
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id10==1) {
						if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id,'limit'=>1),array('expenses'=>$fq_expenses_detail));
					if($type_id10 == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$itemRow=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id10,'limit'=>1));
						if($itemRow){
							$itemDesc = $itemRow->category_item;
							$description = "$optionCategory[$category_id10]=>$itemDesc";
							$description = "$optionCategory[$category_id10]=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id10),array('balance'=>$fq_balance));
							if($item_id10 == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id10),array('balance'=>$fq_balance_to));
							if($item_id10 != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id10 == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$to_bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}
					}
					$adday = $j * 7;
					$newdate = strtotime ( "+$adday day" , strtotime ( $reupdate_date2 ) ) ;
					$activated = date ( 'Y-m-d' , $newdate );
					$this->general_model->_update(array('table'=>'item_frequency','id'=>$item_curr_id),array('activated'=>$activated));
				}
				else if ($ResultRow->frequency_id == 4) {  /* Bi-Weekly */
					$j = 0;
					for ($i = 1; $i <= $daysdiff1; $i=$i+14) {
						if($category_id10==1 && $item_id10 != 77) {
							$fq_CashOnHand = $fq_Cash + $fq_amount10;
							$fq_CashOnHand_to = 0;
						}
						else {
							$fq_CashOnHand = $fq_Cash - $fq_amount10;
							$fq_CashOnHand_to = $fq_Cash_to + $fq_amount10;
						}		
						if($item_id10 != 77) {
							$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
						}
						else {
							$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10_neg,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
							$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$to_bank_id10,'paid'=>1));
						}
						$j++;
						$fq_adjustment = $fq_adjustment + $fq_amount10;
						$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount10;
						$fq_amount_tot = $fq_amount_tot + $fq_amount10;
						$fq_expenses_tot = $fq_expenses_tot + $fq_amount10;
						$fq_expenses6 = $fq_expenses6 + $fq_amount10;
					}
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id10==1) {
						if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id,'sortBy'=>'id','limit'=>1),array('expenses'=>$fq_expenses_detail));
					if($type_id10 == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult1=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id10,'limit'=>1));
						if($ItemResult1) {
							$description = "$optionCategory[$category_id10]=>$ItemResult1[0]->category_item";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id10),array('balance'=>$fq_balance));
							if($item_id10 == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id10),array('balance'=>$fq_balance_to));
							if($item_id10 != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id10 == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$to_bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}			
					}
					$adday = $j * 14;			
					$newdate = strtotime ( "+$adday day" , strtotime ( $reupdate_date2 ) ) ;
					$activated = date ( 'Y-m-d' , $newdate );
					$this->general_model->_update(array('table'=>'item_frequency','id'=>$item_curr_id,'sortBy'=>'id','limit'=>1),array('activated'=>$activated));
				}
				else if ($ResultRow->frequency_id == 5) {	/* Monthly */
					if($category_id10==1 && $item_id10 != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount10;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount10;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount10;
					}	
					$fq_adjustment = $fq_adjustment + $fq_amount10;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount10;
					if($item_id10 != 77) {
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10_neg,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$to_bank_id10,'paid'=>1));
					}
					$fq_amount_tot = $fq_amount_tot + $fq_amount10;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount10;
					$fq_expenses6 = $fq_expenses6 + $fq_amount10;
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					
					if($category_id10==1) {
						if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id10 == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$itemRow=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id10,'limit'=>1));
						if($itemRow) {
							$itemDesc = $itemRow[0]->category_item;
							$description = "$optionCategory[$category_id10]=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id10),array('balance'=>$fq_balance));
							if($item_id10 == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id10),array('balance'=>$fq_balance_to));
							if($item_id10 != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id10 == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$to_bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}			
					}
					$newdate = strtotime ( "+1 month" , strtotime ( $reupdate_date2 ) ) ;
					$activated = date ( 'Y-m-d' , $newdate );
					if(date('d',$org_date) != date('d',$newdate)) $activated = date('Y-m-d', strtotime('last day of next month', strtotime ($reupdate_date)));
					$this->general_model->_update(array('table'=>'item_frequency','id'=>$item_curr_id),array('activated'=>$activated));
				}
				else if ($ResultRow->frequency_id == 6) {	/* Quarterly */
					if($category_id10==1 && $item_id10 != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount10;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount10;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount10;
					}	
					$fq_adjustment = $fq_adjustment + $fq_amount10;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount10;
					if($item_id10 != 77) {
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10_neg,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$to_bank_id10,'paid'=>1));
					}
					$fq_amount_tot = $fq_amount_tot + $fq_amount10;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount10;
					$fq_expenses6 = $fq_expenses6 + $fq_amount10;
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id10==1) {
						if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly_detail','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly','id'=>$fq_Monthly_id,'sortBy'=>'id','limit'=>1),array('expenses'=>$fq_expenses_detail));
					if($type_id10 == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult1=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id10));
						if($ItemResult1) {
							$description = $optionCategory[$category_id10]."=>".$ItemResult1[0]->category_item;
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id10),array('balance'=>$fq_balance));
							if($item_id10 == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id10),array('balance'=>$fq_balance_to));
							if($item_id10 != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id10 == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$to_bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}			
					}
					$newdate = strtotime ( "+3 month" , strtotime ( $reupdate_date2 ) ) ;
					$activated = date ( 'Y-m-d' , $newdate );
					$this->general_model->_update(array('table'=>'item_frequency','id'=>$item_curr_id),array('activated'=>$activated));
				}
				else if ($ResultRow->frequency_id == 7) {	/* Semi-Annually */
					if($category_id10==1 && $item_id10 != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount10;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount10;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount10;
					}	
					$fq_adjustment = $fq_adjustment + $fq_amount10;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount10;
					if($item_id10 != 77) {
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10_neg,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$to_bank_id10,'paid'=>1));
					}
					$fq_amount_tot = $fq_amount_tot + $fq_amount10;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount10;
					$fq_expenses6 = $fq_expenses6 + $fq_amount10;
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id10==1) {
						if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id10 == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult1=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id10));
						if($ItemResult1){
							foreach($ItemResult1 as $itemRow) {
								$itemDesc = $itemRow->category_item;
							}
						}
						$description = "$optionCategory[$category_id10]=>$itemDesc";
						$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id10),array('balance'=>$fq_balance));
						if($item_id10 == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id10),array('balance'=>$fq_balance_to));
						if($item_id10 != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
						if($item_id10 == 77) {
							$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
							$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$to_bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
						}			
					}
					$newdate = strtotime ( "+6 month" , strtotime ( $reupdate_date2 ) ) ;
					$activated = date ( 'Y-m-d' , $newdate );
					$this->general_model->_update(array('table'=>'item_frequency','id'=>$item_curr_id),array('activated'=>$activated));
				}
				else if ($ResultRow->frequency_id == 8) {	/* Yearly */
					if($category_id10==1 && $item_id10 != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount10;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount10;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount10;
					}	
					$fq_adjustment = $fq_adjustment + $fq_amount10;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount10;
					if($item_id10 != 77) {
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10_neg,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$bank_id10,'paid'=>1));
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id10,'item_id'=>$item_id10,'expenses'=>$amount10,'date'=>date('Y-m-d'),'spender_id'=>$spender_id10,'type_id'=>$type_id10,'bank_id'=>$to_bank_id10,'paid'=>1));
					}
					$fq_amount_tot = $fq_amount_tot + $fq_amount10;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount10;
					$fq_expenses6 = $fq_expenses6 + $fq_amount10;
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id10==1) {
						if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id10 != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id10 == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult1=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id10));
						if($ItemResult1){
							foreach($ItemResult1 as $itemRow) {
								$itemDesc = $itemRow->category_item;
							}
						}
						$ItemResult1=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id10));
						if($ItemResult1){
							foreach($ItemResult1 as $itemRow) {
								$itemDesc = $itemRow->category_item;
							}
						}						
						$description = "$optionCategory[$category_id10]=>$itemDesc";
						$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id10),array('balance'=>$fq_balance));
						if($item_id10 == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id10),array('balance'=>$fq_balance_to));
						if($item_id10 != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
						if($item_id10 == 77) {
							$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id10,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
							$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$to_bank_id10,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
						}			
					}
					$newdate = strtotime ( "+1 year" , strtotime ( $reupdate_date2 ) ) ;
					$activated = date ( 'Y-m-d' , $newdate );
					$this->general_model->_update(array('table'=>'item_frequency','id'=>$item_curr_id),array('activated'=>$activated));
				}
			}
		}

		$SpenderResult=$this->general_model->_get(array('table'=>'spender','user_id'=>$user_id));
		if($SpenderResult){
			foreach($SpenderResult as $option) {
				$optionSpender[$option->id] = $option->name;
			}
		}
		$TypeResult=$this->general_model->_get(array('table'=>'sp_payment_type','user_id'=>0));
		if($TypeResult){
			foreach($TypeResult as $option6) {
				$optionType[$option6->id] = $option6->Type;
			}
		}
		$frequencyResult=$this->general_model->_get(array('table'=>'sp_frequency','user_id'=>0));
		if($frequencyResult){
			foreach($frequencyResult as $option8) {
				$optionfrequency[$option8->id] = $option8->frequency;
			}
		}
		$CommentResult=$this->general_model->_get(array('table'=>'sp_comment','category_id'=>7,'item_id'=>1,'sortBy'=>'item_id'));
		if($CommentResult){
			foreach($CommentResult as $option3) {
				$optionComment[$option3->id] = $option3->comment;
			}
		}
		$queryMonthly=$this->general_model->_get(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1));
		if($queryMonthly){
			foreach($queryMonthly as $CurrResult) {
				if($category_id == 1) {	
					$income2 = $e->new_decode($CurrResult->monthly_income);
					if($item_id == 77)	$income3 = $income2; else $income3 = $income2 + $amount;
					$income4 = $e->new_encode("$income3");
				}
				else {
					$expenses3 = $e->new_decode($CurrResult->monthly_expenese);
					$expenses4 = $expenses3 + $amount;
					$expenses5 = $e->new_encode("$expenses4");
				}
			}
		}
		$month1=date('m');
		$year1=date('Y');
		$MonthlyDetail=$this->general_model->_get(array('table'=>'sp_monthly_detail','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'month'=>$month1,'year'=>$year1,'limit'=>1));
		if ($MonthlyDetail) {
			foreach($MonthlyDetail as $Row) {
				$Monthly_curr_id = $Row->id;
				$expenses6 = $e->new_decode($Row->expenses);
				$expenses7 = $expenses6 + $amount;
				$expenses8 = $e->new_encode("$expenses7");
			}
		}
		else {
			$this->general_model->_add(array('table'=>'sp_monthly_detail','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$zero,'month'=>$month1,'year'=>$year1));
			$MonthlyDetail2=$this->general_model->_get(array('table'=>'sp_monthly_detail','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'month'=>$month1,'year'=>$year1,'limit'=>1));
			if($MonthlyDetail2){
				$Monthly_curr_id = $MonthlyDetail2[0]->id;
			}
			$expenses7 = $amount;
			$expenses8 = $e->new_encode("$expenses7");
			
		}
		$paid = 0;
		$BankResult2=$this->general_model->_get(array('table'=>'sp_bank','id'=>$bank_id));
		if($BankResult2){
			foreach($BankResult2 as $OnHand) {
				$paid = $OnHand->pay_now;
				if($OnHand->balance=='') $Cash = 0;
				else $Cash = $e->new_decode($OnHand->balance);
				if($category_id == 1) {
					if($item_id!=77) {
						$CashOnHand = $Cash + $amount;
					}
					else {
						$CashOnHand = $Cash - $amount;
					}
				}
				else {
					$CashOnHand = $Cash - $amount;
				}
				$balance1 = $e->new_encode("$CashOnHand");
			}
		}
		if($category_id == 1) $paid=1;
		$queryBank1=$this->general_model->_get(array('table'=>'sp_bank','id'=>$to_bank));
		if($queryBank1){
			foreach($queryBank1 as $GetBank1) {						
				$balance7 = $e->new_decode($GetBank1->balance);
				$balance8 = $balance7 + $amount;
				$balance9 = $e->new_encode("$balance8");						
			}
		}
		$this->load->model('category_item');
		$ItemResult=$this->category_item->_get(array('user_id'=>$this->session->userdata('id'),'category_id'=>$category_id,'sortBy'=>'category_id'));
		if($ItemResult){
			foreach($ItemResult as $option2) {
				$optionItem[$option2->id] = $option2->category_item;
			}
			$description2 = "$optionCategory[$category_id]=>$optionItem[$item_id]";
		}
		$ItemResult=$this->general_model->_get(array('table'=>'category_item','sortBy'=>'category_id'));
		if($ItemResult){
			foreach($ItemResult as $option2) {
				$optionItem[$option2->id] = $option2->category_item;
				$itemPayType[$option2->id] = $option2->pay_type;
				$itemBank[$option2->id] = $option2->default_bank;
				if($option2->default_value==1)	$defaultPayType[$option2->category_id] = $option2->pay_type;
			}
		}			
		if($frequency_id == 1) {
			if($type_id == 1) { 
				$paid=1;
				$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$balance1));
			}
			if($item_id != 77) {
				$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'comment_id'=>$comment_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
			}
			else {
				$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'comment_id'=>$comment_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank,'paid'=>$paid));
				$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'comment_id'=>$comment_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank,'paid'=>$paid));
			}
			if($category_id == 1) {
				if($type_id==1) {
						if($item_id != 77) {
							$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$expenses,'balance'=>$balance1,'description'=>$description2));
						}
						else {  
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank),array('balance'=>$balance9));
							$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$expenses_neg,'balance'=>$balance1,'description'=>$description2));
							$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$to_bank,'adjust_amount'=>$expenses,'balance'=>$balance9,'description'=>$description2));
						}
				}
				if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$income4));
				if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$Monthly_curr_id),array('expenses'=>$expenses8));
			}
			else {
				if($type_id == 1) {
					$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$expenses_neg,'balance'=>$balance1,'description'=>$description2));
				}
				$this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$expenses5));
				$this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$Monthly_curr_id),array('expenses'=>$expenses8));
			}
		}
		else if($frequency_id == 9) {
			if($item_id != 77) {
				$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'comment_id'=>$comment_id,'expenses'=>$expenses,'date'=>$start_date,'paid_date'=>$start_date,'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>4));
			}
			else {
				$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'comment_id'=>$comment_id,'expenses'=>$expenses_neg,'date'=>$start_date,'paid_date'=>$start_date,'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>4));
				$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'comment_id'=>$comment_id,'expenses'=>$expenses,'date'=>$start_date,'paid_date'=>$start_date,'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank,'paid'=>4));
			}
			if($category_id == 1) {
				if($type_id==1) {
						if($item_id != 77) {
							$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$expenses,'balance'=>$balance2,'description'=>$description2));
						}
						else {  
							$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$expenses_neg,'balance'=>$balance1,'description'=>$description2));
							$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$to_bank,'adjust_amount'=>$expenses,'balance'=>$balance9,'description'=>$description2));
						}
				}
				if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$income4));
				if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$Monthly_curr_id),array('expenses'=>$expenses8));
			}
			else {
				if($type_id == 1) {
					$this->general_model->_add(array('table'=>'sp_bank_detail','allowduplicate'=>true,'user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$expenses_neg,'balance'=>$balance1,'description'=>$description2));
				}
				$this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$expenses5));
				$this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$Monthly_curr_id),array('expenses'=>$expenses8));
			}	
		}
		else {
			if($frequency_id == 2) {
				$newdate = strtotime ( "+1 day" , strtotime ( $start_date ) ) ;
				$activated2 = date('Y-m-d',$newdate);
			}
			else if ($frequency_id == 3) {
				$newdate = strtotime ( "+7 day" , strtotime ( $start_date ) ) ;
				$activated2 = date('Y-m-d',$newdate);
			}
			else if ($frequency_id == 4) {
				$newdate = strtotime ( "+14 day" , strtotime ( $start_date ) ) ;
				$activated2 = date('Y-m-d',$newdate);
			}
			else if ($frequency_id == 5) {
				$newdate = strtotime ( "+1 month" , strtotime ( $start_date ) ) ;
				$activated2 = date('Y-m-d',$newdate);
			}
			else if ($frequency_id == 6) {
				$newdate = strtotime ( "+3 month" , strtotime ( $start_date ) ) ;
				$activated2 = date('Y-m-d',$newdate);
			}
			else if ($frequency_id == 7) {
				$newdate = strtotime ( "+6 month" , strtotime ( $start_date ) ) ;
				$activated2 = date('Y-m-d',$newdate);
			}
			else if ($frequency_id == 8) {
				$newdate = strtotime ( "+1 year" , strtotime ( $start_date ) ) ;
				$activated2 = date('Y-m-d',$newdate);
			}
			$vRes=$this->general_model->_get(array('table'=>'item_frequency','user_id'=>$user_id,'spender_id'=>$spender_id,'item_id'=>$item_id,'frequency_id'=>$frequency_id,'activated'=>$activated2,'limit'=>1));
			if ($vRes) $ErrorMessage = "Duplicated record existed! insert canceled!";	
			else {
				if($daysdiff == 0) {
					$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$balance1));
					if($item_id != 77) {
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'comment_id'=>$comment_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>1));
						if($item_id != 77) $this->general_model->_add(array('table'=>'item_frequency','user_id'=>$user_id,'spender_id'=>$spender_id,'category_id'=>$category_id,'item_id'=>$item_id,'frequency_id'=>$frequency_id,'amount'=>$expenses,'start_date'=>$start_date,'activated'=>$activated2,'type_id'=>$type_id,'bank_id'=>$bank_id));
						if($frequency_id != 1) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$Monthly_curr_id),array('expenses'=>$expenses8));
					}
					else {
						$this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank),array('balance'=>$balance9));
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'comment_id'=>$comment_id,'expenses'=>$expenses_neg,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>1));
						$this->general_model->_add(array('table'=>'spending','allowduplicate'=>true,'user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'comment_id'=>$comment_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank,'paid'=>1));
						$this->general_model->_add(array('table'=>'item_frequency','user_id'=>$user_id,'spender_id'=>$spender_id,'category_id'=>$category_id,'item_id'=>$item_id,'frequency_id'=>$frequency_id,'amount'=>$expenses,'start_date'=>$start_date,'activated'=>$activated2,'type_id'=>$type_id,'bank_id'=>$bank_id,'to_bank_id'=>$to_bank));
					}
					if($category_id == 1) {	
						if($frequency_id != 1 && $item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$income4));
						if($type_id==1) {
							if($item_id != 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$balance2));
							else {  
								$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$balance1));
								$this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank),array('balance'=>$balance9));
							}
						}
					}
					else {
						if($frequency_id != 1) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$expenses5));
					}
				}
				else {
					if($item_id != 77) $this->general_model->_add(array('table'=>'item_frequency','user_id'=>$user_id,'spender_id'=>$spender_id,'category_id'=>$category_id,'item_id'=>$item_id,'frequency_id'=>$frequency_id,'amount'=>$expenses,'start_date'=>$start_date,'activated'=>$start_date,'type_id'=>$type_id,'bank_id'=>$bank_id));
					else $this->general_model->_add(array('table'=>'item_frequency','user_id'=>$user_id,'spender_id'=>$spender_id,'category_id'=>$category_id,'item_id'=>$item_id,'frequency_id'=>$frequency_id,'amount'=>$expenses,'start_date'=>$start_date,'activated'=>$start_date,'type_id'=>$type_id,'bank_id'=>$bank_id,'to_bank_id'=>$to_bank));
				}
			}
		}

		$fmdate = "$year_month"."01000000";
		$yearly = (int)substr($year_month,0,4);
		$dispmonth = (int)substr($year_month,4,2);
		$fmdate = date('Y-m-d', strtotime ( $fmdate ));
		$newdate = strtotime ( "+1 month" , strtotime ( $fmdate ) ) ;
//			$newdate = strtotime ( "+1 day" , $newdate) ;
		$todate = date ( 'Y-m-d' , $newdate );
		$paid_date = date ('Y-m-d');
		$this->load->model('spending');
		$DoBalanceResult=$this->spending->GetUnpaid(array('user_id'=>$user_id));
		if($DoBalanceResult){
			foreach($DoBalanceResult as $BRow) {
				$id11 = $BRow->id;
				$bank_id11 = $BRow->bank_id;
				$category_id11 = $BRow->category_id;
				$item_id11 = $BRow->item_id;
				$amount11 = $e->new_decode($BRow->expenses);
				$OnHand1=$this->general_model->_get(array('table'=>'sp_bank','id'=>$bank_id11));
				if($OnHand1) {
					if($OnHand1->balance=='') $Cash1 = 0;
					else $Cash1 = $e->new_decode($OnHand1->balance);
					$CashOnHand1 = $Cash1 - $amount11;
					$balance11 = $e->new_encode("$CashOnHand1");
					$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id11),array('balance'=>$balance11));
					$this->general_model->_update(array('table'=>'spending','id'=>$id11),array('paid'=>1));
				}
			}
		}
	}
}
