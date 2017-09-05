<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pv_share extends CI_model
{
	function Get($shareto_id) {
		$query = $this->db->query("SELECT * FROM pv_share where shareto_id = $shareto_id order by id desc");
		if($query->num_rows() == 0) return false;
		return $query->result(0);
	}
	function Get1($pv_id,$sharefm_id) {
		$query = $this->db->query("SELECT * FROM pv_share WHERE pv_id=$pv_id and sharefm_id <> $sharefm_id");
		if($query->num_rows() == 0) return false;
		return $query->result(0);
	}
}