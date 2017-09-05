<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sp_payment_type extends CI_model {
	function _get1($options = array(),$likes=array()) {
		if(isset($options['activeFlag'])){
			if($options['activeFlag']==0) $this->db->where('is_active >',0);
		}
		foreach ($this->db->field_data('sp_payment_type') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		$this->db->limit(1);
		foreach ($this->db->field_data('sp_payment_type') as $field) {if(isset($likes[$field->name])) $this->db->like($field->name, $likes[$field->name]);	}		
		$query = $this->db->get('sp_payment_type');
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}

	function Get($user_id) {
		$query = $query = $this->db->query("SELECT * FROM sp_payment_type where (user_id = $user_id or user_id = 0)");          // query executed 
		if($query->num_rows() == 0) return false;
		return $query->result();
	}

	
}