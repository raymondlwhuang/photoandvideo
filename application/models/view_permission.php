<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class View_permission extends CI_model {
	function _get($options = array(),$likes=array()) {
		$options = $this->general_model->_default(array('sortBy' => 'owner_email'), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'desc'), $options);
		$options = $this->general_model->_default(array('groupBy' => 'viewer_group'), $options);
		if(isset($options['activeFlag'])){
			if($options['activeFlag']==0) $this->db->where('is_active >',0);
		}
		foreach ($this->db->field_data('view_permission') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		foreach ($this->db->field_data('view_permission') as $field) {if(isset($likes[$field->name])) $this->db->like($field->name, $likes[$field->name]);	}		
		$query = $this->db->get('view_permission');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function _get1($options = array(),$likes=array()) {
		if(isset($options['activeFlag'])){
			if($options['activeFlag']==0) $this->db->where('is_active >',0);
		}
		foreach ($this->db->field_data('view_permission') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		$this->db->limit(1);
		foreach ($this->db->field_data('view_permission') as $field) {if(isset($likes[$field->name])) $this->db->like($field->name, $likes[$field->name]);	}		
		$query = $this->db->get('view_permission');
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}

	function Get0($id) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE  id = $id");
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	function Get($email_address,$viewer_email) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE owner_email = '$email_address' and viewer_email='$viewer_email' and viewer_group='' limit 1");
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	function Get1($viewer_id) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE viewer_id = $viewer_id");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get2($owner_email) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE owner_email = '$owner_email'");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get3($owner_email) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE owner_email = '$owner_email' and is_active<0");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_waiting($owner_email,$is_active) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE  owner_email = '$owner_email' and is_active=$is_active");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_friend($owner_email,$viewer_email) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE  owner_email = '$owner_email' and viewer_email='$viewer_email' and is_active>=0");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	/* viewer_group*/
	/*
	function Get_group() {
		$query = $this->db->query("SELECT * FROM view_permission where 1 group by viewer_group");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
*/	
	function Get_group($options = array()) {
		$options = $this->general_model->_default(array('groupBy' => 'viewer_group'), $options);
		foreach ($this->db->field_data('view_permission') as $field) {
			if($field->name!='is_active') if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	
		}		
		if(isset($options['flag']) && $options['flag']==0) $this->db->where('is_active >', 0);
		if(isset($options['flag']) && $options['flag']==1) $this->db->where('viewer_group !=', '');
		if(isset($options['flag']) && $options['flag']==2){
			$this->db->where('is_active >', 0);
			$this->db->where('share_flag >', 0);
		}
		$this->db->group_by($options['groupBy']);
		$query = $this->db->get('view_permission');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_group1($user_id,$viewer_id) {
		$query = $this->db->query("SELECT * FROM view_permission where user_id = $user_id and viewer_id = $viewer_id group by viewer_group");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_group2($user_id) {
		$query = $this->db->query("SELECT * FROM view_permission where user_id = $user_id group by viewer_group");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	/* viewer_id*/
	function Get_group3($user_id) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE user_id=$user_id and is_active>0 group by viewer_id");

		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_group4($user_id,$viewer_id) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE user_id=$user_id and viewer_id<> $viewer_id and is_active>0 group by viewer_id");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_email($id=null) {
		if($id) {
			$query = $this->db->query("SELECT * FROM view_permission where viewer_id = $id group by owner_email");
		}
		else
			$query = $this->db->query("SELECT * FROM view_permission where 1 group by owner_email");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_viewer_email($owner_email=null) {
		if($owner_email) {
			$query = $this->db->query("SELECT * FROM view_permission where owner_email = '$owner_email' group by viewer_email");
		}
		else
			$query = $this->db->query("SELECT * FROM view_permission where 1 group by viewer_email");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_viewer_email1($user_id) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE  user_id=$user_id and is_active>=0 group by viewer_email");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_viewer_email2($user_id) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE  user_id=$user_id and is_active>0 and viewer_group!=''");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Get_inactive($owner_email) {
		$query = $this->db->query("SELECT * FROM view_permission WHERE  owner_email = '$owner_email' and is_active<0");
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function Update($user_id,$owner_email,$viewer_email,$is_active) {
		$this->db->query("update view_permission set is_active = $is_active where user_id=$user_id and owner_email='$owner_email' and viewer_email='$viewer_email'");
		return $this->db->affected_rows();
	}	
	function Update1($user_id,$is_active) {
		$this->db->query("update view_permission set is_active = $is_active where user_id=$user_id and is_active<0");
		return $this->db->affected_rows();
	}	
	function Update_share($share_flag,$user_id,$viewer_id) {
		$this->db->query("UPDATE view_permission SET share_flag=$share_flag WHERE user_id = $user_id and viewer_id=$viewer_id");
		return $this->db->affected_rows();
	}	
	function Set_activity($email_address) {
		$email_address=strtolower($email_address);
		$this->db->query("UPDATE view_permission SET is_active = 1 WHERE owner_email = '$email_address' and is_active > 1 and is_active < 9 order by owner_email");
		$query = $this->db->query("UPDATE view_permission SET is_active = 1 WHERE viewer_email = '$email_address' and is_active > 1 and is_active < 9 order by viewer_email");

		return $this->db->affected_rows();
	}
	function Set_activity1($id) {
		$this->db->query("UPDATE view_permission SET is_active = 1 WHERE id = $id");
		return $this->db->affected_rows();
	}
	function Set_waiting($id) {
		$this->db->query("update view_permission set is_active = 9 where user_id=$id and is_active = -1");
		return $this->db->affected_rows();
	}
	function Set_friendreq($user_id,$viewer_email) {
		$this->db->query("UPDATE view_permission SET is_active=10 WHERE user_id=$user_id and viewer_email='$viewer_email' and is_active=9");
		return $this->db->affected_rows();
	}
	function Delete($user_id,$is_active)	{
		$this->db->query("DELETE FROM view_permission WHERE is_active = $is_active and user_id=$user_id");
	}
	function Delete1($user_id,$viewer_id)	{
		$this->db->query("DELETE FROM view_permission WHERE viewer_id = $viewer_id and user_id=$user_id");
	}
	function Delete2($id)	{
		$this->db->query("DELETE FROM view_permission WHERE id = $id");
	}
	
}