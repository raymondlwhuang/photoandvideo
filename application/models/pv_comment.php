<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pv_comment extends CI_model
{
	function get_comment($owner_path) {
		$query = $this->db->query("SELECT * FROM picture_video where owner_path = '$owner_path' and viewer_group <> 'Public' and viewer_group <> 'Temporary'");
		if($query->num_rows() == 0) return '';
		$GetSomething="";
		foreach ($query->result() as $row)
		{
			if($GetSomething=="") $GetSomething=$row['picture_video'];
			if($GetSomething!="" && $GetSomething!=$row['picture_video']) $GetSomething="both";
		}
		
		return $GetSomething;
	}
	function add_comment($options = array()) {
		// required values
		if(!$this->_required(array('table'), $options)) return false;
		$options = $this->_default(array('allowduplicate' => no), $options);
		if (!$options['allowduplicate'] && $this->_Get($options)) return false;
		foreach ($this->db->field_data($options['table']) as $field) {	if(isset($options[$field->name])) $this->db->set($field->name, $options[$field->name]);	}		
		$this->db->insert($options['table']);
		return $this->db->insert_id();
	}	
}