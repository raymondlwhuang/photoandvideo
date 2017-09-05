<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reminder extends MY_Controller {
	public function index()
	{
		$this->load->model('reminder');
		if(isset($_POST['Save_x']))
		{
				$location = $this->input->post('location');
				$description = $this->input->post('description');
				$repetition = $this->input->post('repetition');
				$hour=$this->input->post('hour');
				$minute=$this->input->post('minute');
				$AM=$this->input->post('AM');
				$to=$this->input->post('to');
				$cc=$this->input->post('cc');
				$refer_date = $this->input->post('set_date');
				$fmyear=substr($refer_date,6,4);
				$fmmonth=substr($refer_date,0,2);
				$fmday=substr($refer_date,3,2);
				$set_date = $fmyear.$fmmonth.$fmday."000000";	
				$refer_date = $this->input->post('remind_date');
				$fmyear=substr($refer_date,6,4);
				$fmmonth=substr($refer_date,0,2);
				$fmday=substr($refer_date,3,2);
				$remind_date=$fmyear.$fmmonth.$fmday."000000";
				$this->general_model->_add(array('table'=>'reminder','user_id'=>$user_id,'location'=>$location,'description'=>$description,'repetition'=>$repetition,'mailto'=>$to,'cc'=>$cc,'set_date'=>$set_date,'hour'=>$hour,'minute'=>$minute,'AM'=>$AM,'remind_date'=>$remind_date));
				redirect('/reminder', 'refresh');
		 }
		$todate = date('Y-m-d');
		$newdate = strtotime ( "-1 year" , strtotime ( $todate ) ) ;
		$fmdate=date ( 'Y-m-d' , $newdate );
		$result=$this->reminder->Get($fmdate,$todate);
		$resultfq=$this->general_model->_get(array('table'=>'rm_frequency','user_id'=>0));
		if($resultfq){
			foreach($resultfq as $Row){
				$optionfq[$Row->id] = $Row->frequency;
			}
		}
		$date = date('Ymd')."000000";
		$newdate = strtotime ( "+1 day" , strtotime ( $date ) ) ;
		$todate = date('Ymd',$newdate);
		$date = date('Ymd')."000000";
		$newdate = strtotime ( $date ) ;
		$fmdate=date ( 'Ymd' , $newdate );
		$resultRm=$this->reminder->Get($fmdate,$todate);
		$APM[0]="AM";
		$APM[1]="PM";
	
		$this->load->view("reminder_view");
	}
}
