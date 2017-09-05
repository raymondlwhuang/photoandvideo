<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
class User extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}
}
*/
class User extends CI_model {
	function _get($options = array()) {
		$options = $this->general_model->_default(array('sortBy' => 'id'), $options);
		$options = $this->general_model->_default(array('sortDirection' => 'asc'), $options);
		$options = $this->general_model->_default(array('groupBy' => 'id'), $options);
		foreach ($this->db->field_data('user') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		if(isset($options['groupBy'])) $this->db->group_by($options['groupBy']);
		$query = $this->db->get('user');
		if($query->num_rows() == 0) return false;
		return $query->result();
	}
	function _get1($options = array()) {
		foreach ($this->db->field_data('user') as $field) {	if(isset($options[$field->name])) $this->db->where($field->name, $options[$field->name]);	}		
		$this->db->limit(1);
		$query = $this->db->get('user');
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	function AddUser($options = array()) {
		// required values
		if(!$this->_required(array('email_address','first_name'), $options)) return false;
		if ($this->GetUser($options)) return false;
		// default values
		$options = $this->_default(array('is_active' => 1), $options);
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('first_name', 'last_name', 'email_address', 'username', 'profile_picture', 'password', 'is_active', 'is_super_admin', 'discoverable', 'owner_path', 'last_activity', 'profile_count', 'member_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// MD5 the password if it is set
		if(isset($options['password'])) $this->db->set('password', md5($options['password']));
		// Execute the query
		$this->db->insert('user');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function Get($email_address,$password="") {
		if($password!="")
			$query = $this->db->query("SELECT * FROM user where email_address = '$email_address' and password = '$password' and is_active > 0 limit 1;");  // query string stored in a variable
		else
			$query = $this->db->query("SELECT * FROM user where email_address = '$email_address' limit 1;");  // query string stored in a variable
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	function Get1($id) {
		$query = $this->db->query("SELECT * FROM user where id = $id and is_active > 0 limit 1;");  // query string stored in a variable
		if($query->num_rows() == 0) return false;
		return $query->row(0);
	}
	function Update($email_address) {
		$this->db->query("UPDATE user SET is_active=3 WHERE email_address = '$email_address' LIMIT 1");
		return $this->db->affected_rows();
	}	
	function Update1($id,$owner_path) {
		$this->db->query("UPDATE user SET owner_path='$owner_path' WHERE id=$id LIMIT 1");
		return $this->db->affected_rows();
	}	
	function Update_last_activity($id) {
		if(isset($id) && $id!=0 &&$id!='') {
			$this->db->query("UPDATE user SET last_activity=NOW() WHERE id = $id limit 1");
			return $this->db->affected_rows();
		}
	}	
	function Set_activity($id,$is_active) {
		$this->db->query("UPDATE user SET is_active=$is_active WHERE id = $id limit 1");
		return $this->db->affected_rows();
	}	
	function Active() {
		$query = $this->db->query("SELECT id,is_active,TIMESTAMPDIFF(MINUTE,last_activity,NOW()) as TimeDiff from user where password<>''");
		foreach($query->result() as $row)
		{
			if($row->TimeDiff >30){
				$this->user->Set_activity($row->id,1);
			}
			else if($row->is_active == 1){
				$this->user->Set_activity($row->id,3);
			}
		}
	}	
}