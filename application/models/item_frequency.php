<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Item_frequency extends CI_model {
	function _get($options = array(),$likes=array()) {
		$options = $this->general_model->_default(array('sortBy' => 'activated'), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'desc'), $options);
		if(isset($options['activeFlag'])){
			if($options['activeFlag']==0) $this->db->where('is_active >',0);
		}
		foreach ($this->db->field_data('item_frequency') as $field) {
			if($field->name!='activated'){
				if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);
			}
			else $this->db->where('activated <=', $options['activated']);
		}		
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		foreach ($this->db->field_data('item_frequency') as $field) {if(isset($likes[$field->name])) $this->db->like($field->name, $likes[$field->name]);	}		
		$query = $this->db->get('item_frequency');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}

	
}