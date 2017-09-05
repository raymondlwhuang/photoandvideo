<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_infor extends CI_model
{
	function getvideo($options = array(),$likes=array()) {
		$options = $this->general_model->_default(array('sortBy' => 'viewer_group'), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'desc'), $options);
		foreach ($this->db->field_data('upload_infor') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}
		$this->db->where('name !=', '');		
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		foreach ($this->db->field_data('upload_infor') as $field) {if(isset($likes[$field->name])) $this->db->like($field->name, $likes[$field->name]);	}		
		$query = $this->db->get('upload_infor');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}

	function Get($id) {
		$query = $this->db->query("SELECT * FROM upload_infor where id = $id");
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	function Get1($user_id,$description,$upload_date,$viewer_group) {
		$query = $this->db->query("SELECT id FROM upload_infor where user_id=$user_id and description = '$description' and upload_date='$upload_date' and viewer_group='$viewer_group' limit 1");
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	function get_video($viewer_group) {
		$query = $this->db->query("SELECT * FROM upload_infor where viewer_group = '$viewer_group' and name <> ''");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function get_private_video($viewer_group,$user_id) {
		$query = $this->db->query("SELECT * FROM upload_infor where user_id=$user_id and (viewer_group = '$viewer_group' or viewer_group = '') and name <> ''");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
}