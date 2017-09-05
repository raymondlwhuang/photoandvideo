<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Picture_video extends CI_model
{
	function _get($options = array(),$likes=array()) {
		$options = $this->general_model->_default(array('sortBy' => 'id'), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'desc'), $options);
//		$options = $this->general_model->_default(array('groupBy' => 'upload_id'), $options);
		foreach ($this->db->field_data('picture_video') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		foreach ($this->db->field_data('picture_video') as $field) {if(isset($likes[$field->name])) $this->db->like($field->name, $likes[$field->name]);	}		
		$query = $this->db->get('picture_video');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function _get1($options = array()) {
		foreach ($this->db->field_data('picture_video') as $field) {if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);}
		$this->db->limit(1);
		$query = $this->db->get('picture_video');
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	
	function GetPV($options = array()) {
		foreach ($this->db->field_data('picture_video') as $field) {
			if(isset($options[$field->name])) {
				if($field->name!='viewer_group') $this->db->where($field->name, $options[$field->name]);
			}
		}
		$this->db->where('viewer_group !=', 'Public');
		$this->db->where('viewer_group !=', 'Temporary');
		$query = $this->db->get('picture_video');
		if($query->num_rows() == 0) return "";
		$picture_found=false;
		$video_found=false;
		foreach ($query->result() as $row)
		{
			if($row->picture_video=="pictures" ) $picture_found=true;
			if($row->picture_video=="videos" ) $video_found=true;
		}
		if($picture_found && $video_found) return "both";
		elseif($picture_found) return "pictures";
		elseif($video_found) return "videos";
		else return "";
	}
	function Get($PicturePath,$owner_path) {
		$query = $this->db->query("SELECT * FROM picture_video where picture_video = 'pictures' and viewer_group <> 'Public' and viewer_group <> 'Temporary' and ((name like '$PicturePath%' and owner_path <> '$owner_path') or owner_path = '$owner_path') group by upload_id desc limit 1"); 
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	function Get0($owner_path,$picture_video='pictures') {
		$query = $this->db->query("SELECT * FROM picture_video where owner_path='$owner_path' and picture_video = '$picture_video' order by id desc");  // query string stored in a variable
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get1($name,$picture_video='pictures') {
		$query = $this->db->query("SELECT * FROM picture_video where picture_video='$picture_video' and name = '$name' limit 1");
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	function Get2($owner_path,$upload_id) {
		$query = $this->db->query("SELECT * FROM picture_video where owner_path='$owner_path' and upload_id=$upload_id");  // query string stored in a variable
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_picture($viewer_group,$owner_path=null) {
		if(isset($owner_path))
			$query = $this->db->query("SELECT * FROM picture_video where  owner_path = '$owner_path' and viewer_group = '$viewer_group' order by id desc");  // query string stored in a variable
		else
			$query = $this->db->query("SELECT * FROM picture_video where  viewer_group = '$viewer_group' order by id desc");  // query string stored in a variable
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_privatepv($result_path) {
		$query = $this->db->query("SELECT * FROM picture_video where owner_path = '$result_path' and picture_video = 'pictures' and viewer_group <> 'Public' and viewer_group <> 'Temporary' order by upload_id desc limit 1");
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	
	function getgroup_upload_id($PicturePath,$owner_path) {
		$query = $this->db->query("SELECT * FROM picture_video where picture_video = 'pictures' and viewer_group <> 'Public' and viewer_group <> 'Temporary' and ((name like '$PicturePath%' and owner_path <> '$GV_owner_path') or owner_path = '$GV_owner_path') group by upload_id desc limit 1");  // query string stored in a variable
		if($query->num_rows() == 0) return false;
		return $query->row(0);;
	}
	
}