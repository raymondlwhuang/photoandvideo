<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Viewer_group extends CI_model {
	function Get($user_id,$viewer_group) {
		$query = $this->db->query("SELECT * FROM `viewer_group` where user_id=$user_id and viewer_group = '$viewer_group' limit 1");
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	function Get1($user_id) {
		$query = $this->db->query("SELECT * FROM `viewer_group` where user_id = $user_id");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
}