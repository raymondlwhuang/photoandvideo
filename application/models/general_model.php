<?php
class General_model extends CI_model {
	function _required($required, $data) {
		foreach ($required as $field) {	
		}		
		foreach($required as $field) if(!isset($data[$field])) return false;
		return true;
	}
	function _default($defaults, $options) {
		return array_merge($defaults, $options);
	}	
	function _add($options = array()) {
		// required values
		if(!$this->_required(array('table'), $options)) return false;
		$options = $this->_default(array('allowduplicate' => false), $options);
		if (!$options['allowduplicate'] && $this->_Get($options)) return false;
		foreach ($this->db->field_data($options['table']) as $field) {	
		}		
		foreach ($this->db->field_data($options['table']) as $field) {	if(isset($options[$field->name])) $this->db->set($field->name, $options[$field->name]);	}		
		$this->db->insert($options['table']);
		return $this->db->insert_id();
	}
	function _update($options = array(),$set = array())	{
		if(!$this->_required(array('table'), $options)) return false;
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		foreach ($this->db->field_data($options['table']) as $field) {	
		}
		foreach ($this->db->field_data($options['table']) as $field) {
		if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);
		if(isset($set[$field->name])) $this->db->set($field->name, $set[$field->name]);	
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		// Execute the query
		$this->db->update($options['table']);
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function _get($options = array()) {
		if(!$this->_required(array('table'), $options)) return false;
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		foreach ($this->db->field_data($options['table']) as $field) {	
		}		
		foreach ($this->db->field_data($options['table']) as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		$query = $this->db->get($options['table']);
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function _delete($options = array())	{
		foreach ($this->db->field_data($options['table']) as $field) {	
		}		
		foreach ($this->db->field_data($options['table']) as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		$this->db->delete($options['table']);
		return $this->db->affected_rows();
	}	
}