<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sp_reminder extends CI_model {
	function _get1($options = array(),$likes=array()) {
		if(isset($options['activeFlag'])){
			if($options['activeFlag']==0) $this->db->where('is_active >',0);
		}
		foreach ($this->db->field_data('sp_reminder') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		$this->db->limit(1);
		foreach ($this->db->field_data('sp_reminder') as $field) {if(isset($likes[$field->name])) $this->db->like($field->name, $likes[$field->name]);	}		
		$query = $this->db->get('sp_reminder');
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}

	function Get($options = array()) {
		$options = $this->general_model->_default(array('sortBy' => 'activated '), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'desc'), $options);
	
		foreach ($this->db->field_data('sp_reminder') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		$this->db->where('activated <=', date('Y-m-d'));
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('sp_reminder');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}

	
}