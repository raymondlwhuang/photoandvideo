<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reminder extends CI_model {
	function Get($fmdate,$todate) {
		$query = $this->db->query("SELECT * FROM reminder where set_date between $fmdate and $todate order by set_date desc");  // query string stored in a variable
		if($query->num_rows() == 0) return false;
		return $query->result();
	}	
}