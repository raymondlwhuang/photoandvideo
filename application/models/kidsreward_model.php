<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kidsreward_model extends CI_model {
	function Get($options = array(),$fmdate,$todate) {
		$options = $this->general_model->_default(array('sortBy' => 'rewardid'), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'desc'), $options);
		foreach ($this->db->field_data('kidsreward') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		$this->db->where('date >=', "$fmdate");
		$this->db->where('date <', "$todate");
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		$query = $this->db->get('kidsreward');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function navigate($options = array()) {
		$options = $this->general_model->_default(array('navigate' => 'next'), $options);
		foreach ($this->db->field_data('kidsreward') as $field)
		{
			if(isset($options[$field->name])) {
				if($field->name!='id') $this->db->where($field->name, $options[$field->name]);
				else {
					if($options['navigate']=='Previous' || $options['navigate']=='Next') {
						$this->db->where($field->name,$options[$field->name]);
						$this->db->order_by('id','desc');
					}
				}
			}
		}
		$query = $this->db->get('kidsreward');
		if($query->num_rows()==0) return false;
		if($options['navigate']=='Previous')
			return  $query->previous_row();		
		elseif($options['navigate']=='Next') {
			return  $query->next_row();
		}
		elseif($options['navigate']=='First')
			return  $query->first_row();
		elseif($options['navigate']=='Last')
			return  $query->last_row();
	}
}