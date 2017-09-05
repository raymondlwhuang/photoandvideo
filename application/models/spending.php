<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Spending extends CI_model {
	function _get($options = array(),$likes=array()) {
		$options = $this->general_model->_default(array('sortBy' => 'owner_email'), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'desc'), $options);
		$options = $this->general_model->_default(array('groupBy' => 'viewer_group'), $options);
		if(isset($options['activeFlag'])){
			if($options['activeFlag']==0) $this->db->where('is_active >',0);
		}
		foreach ($this->db->field_data('spending') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		foreach ($this->db->field_data('spending') as $field) {if(isset($likes[$field->name])) $this->db->like($field->name, $likes[$field->name]);	}		
		$query = $this->db->get('spending');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function GetUnpaid($options = array(),$likes=array()) {
		foreach ($this->db->field_data('spending') as $field) {	
		}	
		foreach ($this->db->field_data('spending') as $field) {
			if(isset($options[$field->name])) {
				if($field->name!='paid_date') $this->db->where($field->name, $options[$field->name]);	
				else $this->db->where('paid_date <=', $options[$field->name]);
			}
		}		
		$this->db->where('paid', 4); 
		foreach ($this->db->field_data('spending') as $field) {if(isset($likes[$field->name])) $this->db->like($field->name, $likes[$field->name]);	}		
		$query = $this->db->get('spending');
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}

	function Get($options = array(),$fmdate,$todate) {
		$options = $this->general_model->_default(array('sortBy' => 'date'), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'desc'), $options);
		foreach ($this->db->field_data('spending') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		if(isset($options['paid_status'])){
			if($options['paid_status']=="Paid")	$this->db->where('paid', 1);
			elseif($options['paid_status']=="Unpaid") {
				$this->db->where('paid !=', 1); 
				$this->db->where('paid !=', 4);
			}
			elseif($options['paid_status']=="Future")	$this->db->where('paid', 4);
		}
		$this->db->where('date >=', "$fmdate");
		$this->db->where('date <', "$todate");
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		$query = $this->db->get('spending');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function GetCredit($options = array(),$fmdate) {
		$options = $this->general_model->_default(array('sortBy' => 'date'), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'desc'), $options);
		$options = $this->general_model->_default(array('groupBy' => 'type_id'), $options);
		foreach ($this->db->field_data('spending') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		$this->db->where('date <=', $fmdate);
		$this->db->where('paid !=', 1);
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$this->db->order_by('type_id', 'asc');
		$this->db->order_by('paid', 'desc');
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		$query = $this->db->get('spending');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}

	
}