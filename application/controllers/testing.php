<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testing extends MY_Controller {
	public function index()
	{
		$cloak_keyword = "raymondhuang";
		$e = new endec(); 
		$RangResult=$this->general_model->_get(array('table'=>'sp_monthly','user_id'=>3));
		if($RangResult){
			foreach($RangResult as $option) {
				$amount= $e->new_encode($option->temp);
				
				echo $e->new_decode($amount)."<br/>";
				$this->general_model->_update(array('table'=>'sp_monthly','id'=>$option->id),array('monthly_expenese'=>$amount));
			}
		}
		$RangResult1=$this->general_model->_get(array('table'=>'sp_monthly','user_id'=>3));
		if($RangResult1){
			foreach($RangResult1 as $option1) {
				echo $e->new_decode($option1->monthly_expenese)."<br/>";
			}
		}		
		echo "done!<br/>";
	}
}
