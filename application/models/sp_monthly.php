<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sp_monthly extends CI_model {


	function _update($options = array(),$where = array())	{
		$options = $this->general_model->_default(array('sortBy' => 'reset_date '), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'desc'), $options);
		foreach ($this->db->field_data('sp_monthly') as $field) {	if(isset($options[$field->name])) $this->db->set($field->name, $options[$field->name]);	}		
		foreach ($this->db->field_data('sp_monthly') as $field) {	if(isset($where[$field->name])) $this->db->where($field->name, $where[$field->name]);	}		
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		// Execute the query
		$this->db->update('sp_monthly');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	
}