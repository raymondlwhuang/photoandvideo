<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HERecorder extends MainController {
	public function index()
	{
		$this->db->cache_off();
		$this->session->set_userdata('page','HER');
		if(!$this->session->userdata('id'))
		{
			redirect('/', 'refresh');
		}
		if(!$this->session->userdata('pin'))
		{
			redirect('/getPin', 'refresh');
		}
		$this->load->model('spending');
		$cloak_keyword = "raymond".$this->session->userdata('pin');
		$e = new endec(); 
		$zero = $e->new_encode("0");
		$month_array = array ();
		$month_array[1] = "January";
		$month_array[2] = "February";
		$month_array[3] = "March";
		$month_array[4] = "April";
		$month_array[5] = "May";
		$month_array[6] = "June";
		$month_array[7] = "July";
		$month_array[8] = "August";
		$month_array[9] = "September";
		$month_array[10] = "October";
		$month_array[11] = "November";
		$month_array[12] = "December";
		$month = date('m');
		$year = date('Y');
		if($month == 12){
			$month = 1;
			$year = $year + 1; 
		}
		else {
			$month = $month + 1;
		}
		if(isset($_POST['Delete_x']))
		{
			$reminder_id = $this->input->post('reminder_id');
			$this->general_model->_delete(array('table'=>'sp_reminder','id'=>$reminder_id));
			redirect('/HERecorder', 'refresh');
		}
		$reset_date = $year.str_pad($month, 2, "0", STR_PAD_LEFT)."01";
		$vRes = $this->general_model->_get(array('table'=>'spender','user_id'=>$this->session->userdata('id')));
		if (!$vRes){
			$this->general_model->_add(array('table'=>'spender','user_id'=>$this->session->userdata('id'),'name'=>'Me'));
			$SaveCheck=$this->general_model->_get(array('table'=>'spender','user_id'=>$this->session->userdata('id'),'limit'=>1));
			if($SaveCheck){
				$spender_id = $SaveCheck[0]->id;
				$this->general_model->_add(array('table'=>'sp_bank',"user_id"=>$this->session->userdata('id'),'spender_id'=>$SaveCheck[0]->id,'bank'=>'Cash On Hand(Me)','pay_now'=>1));
			}
			$this->general_model->_add(array('table'=>'sp_monthly',"user_id"=>$this->session->userdata('id'),'monthly_income'=>$zero,'monthly_expenese'=>$zero,'reset_date'=>$reset_date));
		}
		$insertCheck = $this->general_model->_get(array('table'=>'sp_monthly','user_id'=>$this->session->userdata('id'),'limit'=>1));
		if($insertCheck){
			$resetyear=substr($insertCheck[0]->reset_date,0,4);
			$resetmonth=substr($insertCheck[0]->reset_date,5,2);
			$resetday=substr($insertCheck[0]->reset_date,8,2);
			$currdate2 =  date("YmdHis"); 
			$newdate = strtotime ( "+5 hours" , strtotime ( $currdate2 ) ) ;
			$curryear = date("Y",$newdate);
			$currmonth = str_pad(date("m",$newdate), 2, "0", STR_PAD_LEFT);
			$currday = str_pad(date("d",$newdate), 2, "0", STR_PAD_LEFT);
			$reset_date2=gregoriantojd($resetmonth, $resetday, $resetyear);   
			$curr_date2=gregoriantojd($currmonth, $currday, $curryear);   
			$daysdiff2 = $curr_date2 - $reset_date2;
			if($daysdiff2 >=0) {
				$get=$this->general_model->_get(array('table'=>'sp_monthly',"user_id"=>$this->session->userdata('id'),'reset_date'=>$reset_date));
				if(!$get) $this->general_model->_add(array('table'=>'sp_monthly',"user_id"=>$this->session->userdata('id'),'monthly_income'=>$zero,'monthly_expenese'=>$zero,'reset_date'=>$reset_date));
			}
		}

		$newdate = strtotime ( "+1 day" , strtotime (date('Ymd')) ) ;
		$todate=date('Y-m-d',$newdate);
		$fmdate = date('Y-m')."-01";
		$daysdiff = date('d') + 1;
		$RangResult=$this->spending->Get(array('user_id'=>$this->session->userdata('id'),'sortBy'=>'id','sortDirection'=>'desc'),"$fmdate","$todate");
		$SpenderResult=$this->general_model->_get(array('table'=>'spender','user_id'=>$this->session->userdata('id')));
		if($SpenderResult){
			foreach($SpenderResult as $option) {
				$optionSpender[$option->id] = $option->name;
			}
		}
		$this->load->model('sp_payment_type');
		$TypeResult=$this->sp_payment_type->Get($this->session->userdata('id')); 
		if($TypeResult){
			foreach($TypeResult as $option6) {
				$optionType[$option6->id] = $option6->Type;
			}
		}
		$BankResult=$this->general_model->_get(array('table'=>'sp_bank','user_id'=>$this->session->userdata('id')));
		if($BankResult){
			foreach($BankResult as $option7) {
				$optionBank[$option7->id] = $option7->bank;
				if($option7->balance=='') $balance = 0; else $balance = $e->new_decode($option7->balance);
				$optionBalance[$option7->id] = $balance;
			}
		}
		$this->load->model('spending_category');
		$CategoryResult=$this->spending_category->_get(array('user_id'=>$this->session->userdata('id'),'sortBy'=>'category'));
		if($CategoryResult){
			foreach($CategoryResult as $option1) {
				$optionCategory[$option1->id] = $option1->category;
			}
		}
		foreach($optionCategory as $category_id2=>$category) {
			$querySpending=$this->spending->Get(array('user_id'=>$this->session->userdata('id'),'category_id'=>$category_id2,'sortBy'=>'category_id'),$fmdate,$todate);
			$categoryTotal["$category_id2"] = 0;
			if($querySpending){
				foreach($querySpending as $TotalResult) {
					if($category_id2 == 1) $income = $e->new_decode($TotalResult->expenses);
					else $expenses2 = $e->new_decode($TotalResult->expenses);
					$item_id = $TotalResult->item_id;
					if($category_id2 == 1) $categoryTotal["$category_id2"] += $income;
					else $categoryTotal["$category_id2"] += $expenses2;
					if(!isset($itemTotal["$category_id2"]["$item_id"])) $itemTotal["$category_id2"]["$item_id"] = 0;
					if($category_id2 == 1) $itemTotal["$category_id2"]["$item_id"] += $income;
					else $itemTotal["$category_id2"]["$item_id"] += $expenses2;
					$spender_id2 = $TotalResult->spender_id;
					if(!isset($SpenderTotal["$spender_id2"])) $SpenderTotal["$spender_id2"] = 0;
					if($category_id2 == 1) {
						if(!isset($SpenderIncome["$spender_id2"])) $SpenderIncome["$spender_id2"] = 0;
						$SpenderIncome["$spender_id2"] += $income;
					}
					else $SpenderTotal["$spender_id2"] += $expenses2;
				}  
			}
		}
		$ItemResult=$this->general_model->_get(array('table'=>'category_item','user_id'=>0,'category_id'=>7,'sortBy'=>'category_id'));
		if($ItemResult){
			foreach($ItemResult as $option2) {
				$optionItem[$option2->id] = $option2->category_item;
			}
		}
		$CommentResult=$this->general_model->_get(array('table'=>'sp_comment','category_id'=>7,'item_id'=>1,'sortBy'=>'item_id'));
		if($CommentResult){
			foreach($CommentResult as $option3) {
				$optionComment["$option3->id"] = $option3->comment;
			}
		}
		$frequencyResult=$this->general_model->_get(array('table'=>'sp_frequency','user_id'=>0));
		if($frequencyResult){
			foreach($frequencyResult as $option8) {
				$optionfrequency[$option8->id] = $option8->frequency;
			}
		}
		$getitem_frequency=$this->general_model->_get(array('table'=>'item_frequency','user_id'=>$this->session->userdata('id'),'sortBy'=>'id','sortDirection'=>'desc'));
		if($getitem_frequency){
			foreach($getitem_frequency as $option9) {
				$frequency_id9 = $option9->frequency_id;
				$spender_id9 = $option9->spender_id;
				$id9 = $option9->id;
				$amount9 = $e->new_decode($option9->amount);
				$date9 = substr($option9->start_date,0,10);
				$category_id9 = $option9->category_id;
				$bank_id9 = $option9->bank_id;
				$get_category_item=$this->general_model->_get(array('table'=>'category_item','user_id'=>0,'id'=>$option9->item_id,'limit'=>1));
				if($get_category_item){
					$category_item9 = $get_category_item[0]->category_item;
				}
				$disp9 = "$optionSpender[$spender_id9]($optionCategory[$category_id9]=>$category_item9) \$$amount9 will recorded $optionfrequency[$frequency_id9] starting at $date9=>$optionBank[$bank_id9].";
				$optionPayFrq["$id9"] = $disp9;
			}
		}
		$Income = '';
		$Expenese = '';
		$getMonthlyResult=$this->general_model->_get(array('table'=>'sp_monthly','user_id'=>$this->session->userdata('id'),'sortBy'=>'reset_date'));
		if($getMonthlyResult){
			foreach($getMonthlyResult as $monthly){
				$month = substr($monthly->reset_date,5,2);
				$year = substr($monthly->reset_date,0,4);
				if($month == 1){
					$month = 12;
					$year = $year - 1; 
				}
				else {
				 $month = $month - 1;
				}
				$year_month = $year.str_pad($month, 2, "0", STR_PAD_LEFT);
				$this_year_month=date('Ym');
				$spender_id4 = $monthly->id;
				$monthly_income = $e->new_decode($monthly->monthly_income);
				$monthly_expenese = $e->new_decode($monthly->monthly_expenese);
				if($year_month==$this_year_month) $Income .= "<option value='$year_month' selected='selected'>$month_array[$month]/$year=>\$$monthly_income</option>";
				else $Income .= "<option value='$year_month'>$month_array[$month]/$year=>\$$monthly_income</option>";
				if($year_month==$this_year_month) $Expenese .= "<option value='$year_month' selected='selected'>$month_array[$month]/$year=>\$$monthly_expenese</option>";
				else $Expenese .= "<option value='$year_month'>$month_array[$month]/$year=>\$$monthly_expenese</option>";
			}
		}
		$GetSpender = $this->general_model->_get(array('table'=>'spender','user_id'=>$this->session->userdata('id')));
		if($GetSpender) {
			foreach($GetSpender as $spender){
				$optionName[$spender->id] = $spender->name;
			}
		}	
		
		$this->load->model('sp_reminder');
		$queryResult3=$this->sp_reminder->Get(array('table'=>'sp_reminder','user_id'=>$this->session->userdata('id')));
		if($queryResult3) $this->data['queryResult3']=$queryResult3;
		$newdate1 = strtotime ( "-1 month" , strtotime(date('Ymd')));
		$fmdate1 = date('Ymd',$newdate1);
		$queryResult1=$this->spending->GetCredit($options = array('user_id'=>$this->session->userdata('id')),$fmdate1);
		if($queryResult1) $this->data['queryResult1']=$queryResult1;
		$this->data['optionSpender']=$optionSpender;
		$this->data['optionCategory']=$optionCategory;
		$this->data['optionItem']=$optionItem;
		$this->data['optionType']=$optionType;
		$this->data['optionfrequency']=$optionfrequency;
		$this->data['optionBank']=$optionBank;
		$this->data['optionPayFrq']=$optionPayFrq;
		$this->data['optionName']=$optionName;
		$this->data['Income']=$Income;
		$this->data['cloak_keyword']=$cloak_keyword;
		if(isset($income))$this->data['income']=$income;
		$this->data['Expenese']=$Expenese;
		if(isset($categoryTotal)) $this->data['categoryTotal']=$categoryTotal;
		if(isset($itemTotal)) $this->data['itemTotal']=$itemTotal;
		if(isset($RangResult)) $this->data['RangResult']=$RangResult;
	
	
		$this->load->view("herecorder_view",$this->data);
	}
}
