<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Spending_category extends CI_model {
	function _get($options = array()) {
		$options = $this->general_model->_default(array('sortDirection' => 'asc'), $options);
		foreach ($this->db->field_data('spending_category') as $field) {
			if(isset($options[$field->name]) && $field->name!='user_id') $this->db->where($field->name, $options[$field->name]);
		}
		$this->db->where_in('user_id', array(0, $options['user_id']));
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		$query = $this->db->get('spending_category');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
}
