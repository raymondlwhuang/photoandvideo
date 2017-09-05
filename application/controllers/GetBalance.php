<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetBalance extends MY_Controller {
	public function index()
	{
		$cloak_keyword = "raymond".$this->session->userdata('pin');
		$e = new endec(); 
		$encodebalance = $e->new_encode("0");
		if(isset($_GET['bank_id'])) $id = $this->input->get('bank_id'); else $id = 0;
		if($id == 0) echo "0.00";
		else {
			$vRes=$this->general_model->_get(array('table'=>'sp_bank','id'=>$id));
			if($vRes) {
				$balance = "$".$e->new_decode($vRes[0]->balance);
			}	
			echo $balance;
		}	
	}
}
