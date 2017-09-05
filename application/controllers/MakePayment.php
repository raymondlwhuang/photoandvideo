<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MakePayment extends MainController {
	public function index()
	{
		$cloak_keyword = "raymond".$this->session->userdata('pin');	
		$e = new endec();
		$zero = $e->new_encode("0");
		$user_id = $this->session->userdata("id");
		$reminder_id = $this->input->get('reminder_id');
		$fq_amount = $this->input->get('amount');
		$fq_amount_neg = $fq_amount * (-1);
		$expenses = $e->new_encode($fq_amount);
		$expenses_neg = $e->new_encode($fq_amount_neg);
		$beforeInsert=$this->general_model->_get(array('table'=>'sp_reminder','id'=>$reminder_id));
		if($beforeInsert){
			foreach($beforeInsert as $Row){
				$category_id=$Row->category_id;
				$item_id=$Row->item_id;
				$spender_id=$Row->spender_id;
				$type_id=$Row->type_id;
				if($type_id==1) $paid=1; else $paid=2;
				$bank_id=$Row->bank_id;
				$to_bank_id=$Row->to_bank_id;
				$start_date=$Row->start_date;
				$reupdate_date=$Row->activated;
				$frequency_id=$Row->frequency_id;
				$fmyear=substr($start_date,0,4);
				$fmmonth=substr($start_date,5,2);
				$fmday=substr($start_date,8,2);	
				$toyear = date("Y");	
				$tomonth = date("m");
				$today = date("d");
				$end_date=gregoriantojd($tomonth, $today, $toyear);	
				$reupdateyear=substr($reupdate_date,0,4);
				$reupdatemonth=substr($reupdate_date,5,2);
				$reupdateday=substr($reupdate_date,8,2);	
				$reupdate_date1=gregoriantojd($reupdatemonth, $reupdateday, $reupdateyear);
				$daysdiff1 = $end_date - $reupdate_date1 + 1;
				$month3=date('m');
				$year3=date('Y');
				$CategoryRow2=$this->general_model->_get(array('table'=>'spending_category','id'=>$category_id,'limit'=>1));
				if($CategoryRow2) {
					$CategoryDesc=$CategoryRow2[0]->category;
				}	
				$CurrResult=$this->general_model->_get(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1));
				if($CurrResult) {
					$fq_income2 = 0;
					$fq_expenses3 = 0;
					if($category_id == 1) {	
						$fq_income2 = $e->new_decode($CurrResult[0]->monthly_income);
					}
					else {
						$fq_expenses3 = $e->new_decode($CurrResult[0]->monthly_expenese);
					}
				}
				$fq_Cash_to = 0;
				$GetToBank4=$this->general_model->_get(array('table'=>'sp_bank','id'=>$to_bank_id,'limit'=>1));
				if($GetToBank4) {						
					if($GetToBank4[0]->balance=='') $fq_Cash_to = 0;
					else $fq_Cash_to = $e->new_decode($GetToBank4[0]->balance);
				}

				$Row3=$this->general_model->_get(array('table'=>'sp_monthly_detail','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'month'=>$month3,'year'=>$year3,'limit'=>1));
				if ($Row3) {
					$fq_Monthly_id = $Row3[0]->id;
					$fq_expenses6 = $e->new_decode($Row3[0]->expenses);
				}
				else {
					$this->general_model->_add(array('table'=>'sp_monthly_detail','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$zero,'month'=>$month3,'year'=>$year3));
					$Row1=$this->general_model->_get(array('table'=>'sp_monthly_detail','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'month'=>$month3,'year'=>$year3,'limit'=>1));
					if($Row1) {
						$fq_Monthly_id = $Row1[0]->id;
					}
					$fq_expenses6 = 0;
				}
				
				$fq_Cash = 0;
				$fq_OnHand=$this->general_model->_get(array('table'=>'sp_bank','id'=>$bank_id));
				if($fq_OnHand) {
					if($fq_OnHand[0]->balance=='') $fq_Cash = 0;
					else $fq_Cash = $e->new_decode($fq_OnHand[0]->balance);
				}
				$fq_CashOnHand = 0;
				$fq_CashOnHand_to = 0;
				$fq_adjustment = 0;
				$fq_adjustment_neg = 0;
				$fq_amount_tot = $fq_income2;
				$fq_expenses_tot = $fq_expenses3;
				/* not do multiple insert like HERDisp.php because this just a reminder and it may not need to insert every time */
				if($frequency_id==2) {
					if($category_id==1 && $item_id != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount;
					}
					if($item_id != 77) {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses_neg,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank_id,'paid'=>$paid));
					}
					$fq_adjustment = $fq_adjustment + $fq_amount;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount;
					$fq_amount_tot = $fq_amount_tot + $fq_amount;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount;
					$fq_expenses6 = $fq_expenses6 + $fq_amount;			
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id==1) {
						if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$itemRow=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id,'limit'=>1));
						if($itemRow) {
							$itemDesc = $itemRow[0]->category_item;
							$description = "$CategoryDesc=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$fq_balance));
							if($item_id == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id),array('balance'=>$fq_balance_to));
							if($item_id != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$to_bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}
						}
					}		
					$newdate = strtotime ( "+1 day" , strtotime (date("Ymd")."000000")) ;
					$activated = date('Y-m-d', $newdate);
				}
				elseif($frequency_id==3) {
					for ($i = 1; $i <= $daysdiff1; $i=$i+7) {
						$j++;
					}
					if($category_id==1 && $item_id != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount;
					}		
					if($item_id != 77) {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank_id,'paid'=>$paid));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses_neg,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank_id,'paid'=>$paid));
					}		
					$fq_adjustment = $fq_adjustment + $fq_amount;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount;
					$fq_amount_tot = $fq_amount_tot + $fq_amount;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount;
					$fq_expenses6 = $fq_expenses6 + $fq_amount;		
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id==1) {
						if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id));
						              // if any error is there that will be printed to the screen 
						if($ItemResult) {
							$itemDesc = $ItemResult[0]->category_item;
							$description = "$CategoryDesc=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$fq_balance));
							if($item_id == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id),array('balance'=>$fq_balance_to));
							if($item_id != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$to_bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}			
					}		
					$adday = $j * 7;
					$newdate = strtotime ( "+$adday day" , strtotime (date("Ymd")."000000")) ;
					$activated = date('Y-m-d', $newdate);
				}
				elseif($frequency_id==4) {
					$j = 0;
					for ($i = 1; $i <= $daysdiff1; $i=$i+14) {
						$j++;
					}
					if($category_id==1 && $item_id != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount;
					}		
					if($item_id != 77) {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses_neg,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank_id,'paid'=>$paid));
					}
					$fq_adjustment = $fq_adjustment + $fq_amount;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount;
					$fq_amount_tot = $fq_amount_tot + $fq_amount;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount;
					$fq_expenses6 = $fq_expenses6 + $fq_amount;		
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id==1) {
						if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id));
						              // if any error is there that will be printed to the screen 
						if($ItemResult) {
							$itemDesc = $ItemResult[0]->category_item;
							$description = "$CategoryDesc=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$fq_balance));
							if($item_id == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id),array('balance'=>$fq_balance_to));
							if($item_id != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$to_bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}			
					}		
					$adday = $j * 14;
					$newdate = strtotime ( "+$adday day" , strtotime (date("Ymd")."000000")) ;
					$activated = date('Y-m-d', $newdate);
				}
				elseif($frequency_id==5) {
					if($category_id==1 && $item_id != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount;
					}	
					$fq_adjustment = $fq_adjustment + $fq_amount;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount;
					if($item_id != 77) {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses_neg,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank_id,'paid'=>$paid));
					}
					$fq_amount_tot = $fq_amount_tot + $fq_amount;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount;
					$fq_expenses6 = $fq_expenses6 + $fq_amount;
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id==1) {
						if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id));
						              // if any error is there that will be printed to the screen 
						if($ItemResult) {
							$itemDesc = $ItemResult[0]->category_item;
							$description = "$CategoryDesc=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$fq_balance));
							if($item_id == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id),array('balance'=>$fq_balance_to));
							if($item_id != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$to_bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}			
					}	
					$orgdate = strtotime ($start_date);
					$newdate = strtotime ( "+1 month" , strtotime ( $reupdate_date ) ) ;
					$last_activated_date = strtotime (date("Ymd")."000000");
					$activated = date('Ymd', $newdate);
					if(date('d',$orgdate) != date('d',$newdate)) $activated = date('Ymd', strtotime('last day of next month', $last_activated_date));
					while($activated<date('Ymd')){
						$activated = date('Ymd',strtotime ( "+1 month" , strtotime ( $activated ) )) ;
					}
				}
				elseif($frequency_id==9) {
					if($category_id==1 && $item_id != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount;
					}	
					$fq_adjustment = $fq_adjustment + $fq_amount;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount;
					if($item_id != 77) {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses_neg,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank_id,'paid'=>$paid));
					}
					$fq_amount_tot = $fq_amount_tot + $fq_amount;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount;
					$fq_expenses6 = $fq_expenses6 + $fq_amount;
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id==1) {
						if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id));
						              // if any error is there that will be printed to the screen 
						if($ItemResult) {
							$itemDesc = $itemRow['category_item'];
							$description = "$CategoryDesc=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$fq_balance));
							if($item_id == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id),array('balance'=>$fq_balance_to));
							if($item_id != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$to_bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}			
					}	
					$orgdate = strtotime ($start_date);
					$newdate = strtotime ( "+2 month" , strtotime ( $reupdate_date ) ) ;
					$newdate2 = strtotime ( "+1 month" , strtotime ( $reupdate_date ) ) ;
					$last_activated_date = strtotime (date("Ymd")."000000");
					$activated = date('Ymd', $newdate);
					if(date('d',$orgdate) != date('d',$newdate)) $activated = date('Ymd', strtotime('last day of next month', $newdate2));
					
				}
				elseif($frequency_id==6) {
					if($category_id==1 && $item_id != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount;
					}	
					$fq_adjustment = $fq_adjustment + $fq_amount;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount;
					if($item_id != 77) {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses_neg,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank_id,'paid'=>$paid));
					}
					$fq_amount_tot = $fq_amount_tot + $fq_amount;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount;
					$fq_expenses6 = $fq_expenses6 + $fq_amount;
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id==1) {
						if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id));
						              // if any error is there that will be printed to the screen 
						if($ItemResult) {
							$itemDesc = $ItemResult[0]->category_item;
							$description = "$CategoryDesc=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$fq_balance));
							if($item_id == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id),array('balance'=>$fq_balance_to));
							if($item_id != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$to_bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}			
					}	
					//$last_activated_date = strtotime (date("Ymd")."000000");
					//$newdate = strtotime ( "+3 month" , $last_activated_date ) ;
					$newdate = strtotime ( "+3 month" , strtotime ( $reupdate_date ) ) ;
					$activated = date('Ymd', $newdate);
					while($activated<date('Ymd')){
						$activated = date('Ymd',strtotime ( "+3 month" , strtotime ( $activated ) )) ;
					}
				}
				elseif($frequency_id==7) {
					if($category_id==1 && $item_id != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount;
					}	
					$fq_adjustment = $fq_adjustment + $fq_amount;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount;
					if($item_id != 77) {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses_neg,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank_id,'paid'=>$paid));
					}
					$fq_amount_tot = $fq_amount_tot + $fq_amount;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount;
					$fq_expenses6 = $fq_expenses6 + $fq_amount;
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id==1) {
						if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id));
						              // if any error is there that will be printed to the screen 
						if($ItemResult) {
							$itemDesc = $ItemResult[0]->category_item;
							$description = "$CategoryDesc=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','balance'=>$fq_balance),array('id'=>$bank_id));
							if($item_id == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id),array('balance'=>$fq_balance_to));
							if($item_id != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$to_bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}			
					}	
					//$last_activated_date = strtotime (date("Ymd")."000000");
					//$newdate = strtotime ( "+6 month" , $last_activated_date ) ;
					//$activated = date('Ymd', $newdate);
					$newdate = strtotime ( "+6 month" , strtotime ( $reupdate_date ) ) ;
					$activated = date('Ymd', $newdate);
					while($activated<date('Ymd')){
						$activated = date('Ymd',strtotime ( "+6 month" , strtotime ( $activated ) )) ;
					}
					
				}
				elseif ($frequency_id == 8) {
				if($category_id==1 && $item_id != 77) {
						$fq_CashOnHand = $fq_Cash + $fq_amount;
						$fq_CashOnHand_to = 0;
					}
					else {
						$fq_CashOnHand = $fq_Cash - $fq_amount;
						$fq_CashOnHand_to = $fq_Cash_to + $fq_amount;
					}	
					$fq_adjustment = $fq_adjustment + $fq_amount;
					$fq_adjustment_neg = $fq_adjustment_neg - $fq_amount;
					if($item_id != 77) {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
					}
					else {
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses_neg,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
						$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$to_bank_id,'paid'=>$paid));
					}
					$fq_amount_tot = $fq_amount_tot + $fq_amount;
					$fq_expenses_tot = $fq_expenses_tot + $fq_amount;
					$fq_expenses6 = $fq_expenses6 + $fq_amount;
					$fq_income9 = $e->new_encode("$fq_amount_tot");
					$fq_expenses9 = $e->new_encode("$fq_expenses_tot");
					$fq_expenses_detail = $e->new_encode("$fq_expenses6");
					if($category_id==1) {
						if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_income'=>$fq_income9));
					}
					else $this->general_model->_update(array('table'=>'sp_monthly','user_id'=>$user_id,'sortBy'=>'reset_date','sortDirection'=>'desc','limit'=>1),array('monthly_expenese'=>$fq_expenses9));
					if($item_id != 77) $this->general_model->_update(array('table'=>'sp_monthly_detail','id'=>$fq_Monthly_id),array('expenses'=>$fq_expenses_detail));
					if($type_id == 1) {
						$fq_balance = $e->new_encode("$fq_CashOnHand");
						$fq_balance_to = $e->new_encode("$fq_CashOnHand_to");
						$fq_adjustment_tot = $e->new_encode("$fq_adjustment");
						$fq_adjustment_tot_neg = $e->new_encode("$fq_adjustment_neg");
						$ItemResult=$this->general_model->_get(array('table'=>'category_item','id'=>$item_id));
						              // if any error is there that will be printed to the screen 
						if($ItemResult) {
							$itemDesc = $ItemResult[0]->category_item;
							$description = "$CategoryDesc=>$itemDesc";
							$this->general_model->_update(array('table'=>'sp_bank','id'=>$bank_id),array('balance'=>$fq_balance));
							if($item_id == 77) $this->general_model->_update(array('table'=>'sp_bank','id'=>$to_bank_id),array('balance'=>$fq_balance_to));
							if($item_id != 77) $this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance,'description'=>$description));
							if($item_id == 77) {
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$bank_id,'adjust_amount'=>$fq_adjustment_tot_neg,'balance'=>$fq_balance,'description'=>$description));
								$this->general_model->_add(array('table'=>'sp_bank_detail','user_id'=>$user_id,'bank_id'=>$to_bank_id,'adjust_amount'=>$fq_adjustment_tot,'balance'=>$fq_balance_to,'description'=>$description));
							}			
						}			
					}	
					$this->general_model->_add(array('table'=>'spending','user_id'=>$user_id,'category_id'=>$category_id,'item_id'=>$item_id,'expenses'=>$expenses,'date'=>date('Y-m-d'),'paid_date'=>date('Y-m-d'),'spender_id'=>$spender_id,'type_id'=>$type_id,'bank_id'=>$bank_id,'paid'=>$paid));
					$newdate = strtotime ( "+1 year" , strtotime ( $reupdate_date ) ) ;
					$activated = date('Ymd', $newdate);
					while($activated<date('Ymd')){
						$activated = date('Ymd',strtotime ( "+1 year" , strtotime ( $activated ) )) ;
					}
				}
				$this->general_model->_update(array('table'=>'sp_reminder','id'=>$reminder_id),array('activated'=>$activated));
				$this->load->model('sp_reminder');
				$queryResult3=$this->sp_reminder->Get(array('user_id'=>$user_id));

				echo '<form name="ReminderDel" method="Post">
				<input type="hidden" name="reminder_id" id="reminder_id" value=""/>';
				if($queryResult3) {
					echo "<h1 style='color:red;'>It's time to make payment for the following items</h1>";
					echo "<ul>";
					foreach($queryResult3 as $typeResult3) {
						$reminder_id=$typeResult3->id;
						$CategoryResult1=$this->general_model->_get(array('table'=>'spending_category','id'=>$typeResult3->category_id,'limit'=>1));
						echo "<li><input type='image' src='/images/delete.png' name='Delete' value='$reminder_id' onClick=\"assign_value($reminder_id);\"/>";
						if($CategoryResult1){
							echo $CategoryResult1[0]->category."=>";
							$ItemResult1=$this->general_model->_get(array('table'=>'category_item','id'=>$typeResult3->item_id,'limit'=>1));
							if($ItemResult1){
								echo $ItemResult1[0]->category_item;
							}
							$TypeResult4=$this->general_model->_get(array('table'=>'sp_payment_type','id'=>$typeResult3->type_id,'limit'=>1));
							if($TypeResult4) {
								echo "with <font color=\"red\"><b>".$TypeResult4[0]->Type."</b></font> ";
							}
							$BankResult1=$this->general_model->_get(array('table'=>'sp_bank','id'=>$typeResult3->bank_id,'limit'=>1));
							if($BankResult1) {
								echo "pay from <font color=\"red\"><b>".$BankResult1[0]->bank."</b></font>&nbsp;";
							}
							$BankResult2=$this->general_model->_get(array('table'=>'sp_bank','id'=>$typeResult3->to_bank_id,'limit'=>1));
							if($BankResult2) {
								echo " to <font color=\"red\"><b>".$BankResult2[0]->bank."</b></font> ";
							}
							$amount=$e->new_decode($typeResult3->amount);
						}
						echo "with amount: <input type='text' class='reminder_amount' id='amt$reminder_id' value='$amount' readonly=\"readonly\"/>";
						echo "<input type='image' src='/images/paynow.png' name='$reminder_id' class='paynow' id='$reminder_id' value='$amount'/></li>";
					}
					echo "</ul>";
				}
				if(isset($queryResult1)){
					echo "<h1>It's time to pay the overdue amount on your</h1><ul>";
					foreach($queryResult1 as $typeResult1) {
						$typeResult2=$this->general_model->_get(array('table'=>'sp_payment_type','user_id'=>0,'id'=>$typeResult1->type_id,'limit'=>1));
						if($typeResult2) {
							echo "<li>".$typeResult2[0]->Type."</li>";
						}
					}
					echo "</ul>";
				}
				echo "</form>";
				$todate1 = date('Ymd')."000000";
				$newdate1 = strtotime ( "-1 month" , strtotime ( $todate1 ) ) ;
				$fmdate1 = date ( 'Ymd' , $newdate1 )."000000";
				$this->load->model('spending');
				$queryResult1=$this->spending->GetCredit(array('user_id'=>$user_id),$fmdate1);
				if($queryResult1){
					echo "<h3>It's time to pay the overdue amount on your</h3><ul>";
					foreach($queryResult1 as $typeResult1) {
						$typeResult2=$this->general_model->_get(array('table'=>'sp_payment_type','user_id'=>0,'id'=>$typeResult1->type_id,'limit'=>1));
						if($typeResult2) {
							echo "<li>".$typeResult2[0]->Type."</li>";
						}
					}
					echo "</ul>";
				}				
			}
		}
	}
}
