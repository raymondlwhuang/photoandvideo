<?php

class Data_model extends CI_model {
	function _required($required, $data) {
		foreach($required as $field) if(!isset($data[$field])) return false;
		return true;
	}
	function _default($defaults, $options) {
		return array_merge($defaults, $options);
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
	function UpdateUser($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('first_name', 'last_name', 'email_address', 'username', 'profile_picture', 'password', 'is_active', 'is_super_admin', 'discoverable', 'owner_path', 'last_activity', 'profile_count', 'member_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// MD5 the password if it is set
		if(isset($options['password'])) $this->db->set('password', md5($options['password']));
		$whereArray = array('id', 'email_address', 'owner_path');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('user');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetUser($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'email_address', 'owner_path');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('user');
		if($query->num_rows() == 0) return false;
		if(isset($options['id']) || isset($options['email_address']) || isset($options['owner_path'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteUser($options = array())	{
		$qualificationArray = array('id', 'email_address', 'owner_path');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('user');
	}	
	function AddPermission($options = array()) {
		// required values
		if(!$this->_required(array('user_id', 'viewer_id', 'owner_email', 'owner_path', 'viewer_email', 'first_name', 'last_name'), $options)) return false; //viewer's first and last name
		if ($this->GetPermission($options)) return false;
		// default values
		$options = $this->_default(array('viewer_group' => '','is_active'=>1), $options);
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('user_id', 'viewer_id', 'owner_email', 'owner_path', 'viewer_group', 'viewer_email', 'is_active', 'first_name', 'last_name', 'share_flag');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('view_permission');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdatePermission($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('first_name', 'last_name', 'viewer_email', 'view_permissionname', 'profile_picture', 'password', 'is_active', 'is_super_admin', 'discoverable', 'owner_path', 'last_activity', 'profile_count', 'member_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'user_id', 'viewer_email','viewer_group');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('view_permission');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetPermission($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'user_id',' viewer_id', 'viewer_email','viewer_group');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('view_permission');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeletePermission($options = array())	{
		$qualificationArray = array('id', 'user_id', 'viewer_id','viewer_email','viewer_group');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('view_permission');
	}
	function AddGroup($options = array()) {
		// required values
		if(!$this->_required(array('user_id', 'owner_path','viewer_group'), $options)) return false;
		if ($this->GetGroup($options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('user_id', 'owner_path', 'viewer_group');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('viewer_group');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateGroup($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('user_id', 'owner_path', 'viewer_group');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'user_id', 'owner_path', 'viewer_group');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('viewer_group');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetGroup($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'user_id', 'owner_path', 'viewer_group');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('viewer_group');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteGroup($options = array()) {
		$qualificationArray = array('id', 'user_id', 'owner_path', 'viewer_group');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('viewer_group');
	}	
	function AddUploadInfor($options = array()) {
		// required values
		if(!$this->_required(array('user_id', 'viewer_group'), $options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('user_id', 'upload_date', 'description', 'viewer_group', 'name');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('upload_infor');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateUploadInfor($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('user_id', 'upload_date', 'description', 'viewer_group', 'name');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'user_id', 'upload_date', 'description', 'viewer_group', 'name');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('upload_infor');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetUploadInfor($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'user_id', 'upload_date', 'description', 'viewer_group', 'name');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('upload_infor');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteUploadInfor($options = array()) {
		$qualificationArray = array('id', 'user_id', 'upload_date', 'description', 'viewer_group', 'name');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('upload_infor');
	}
	function AddSequence($options = array()) {
		// required values
		if(!$this->_required(array('flag', 'date'), $options)) return false;
		if ($this->GetSequence($options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('flag', 'date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('sequence');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateSequence($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('flag', 'date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'flag', 'date');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('sequence');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetSequence($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'flag', 'date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('sequence');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteSequence($options = array())	{
		$qualificationArray = array('id', 'flag', 'date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('sequence');
	}	
	function AddShare($options = array()) {
		// required values
		if(!$this->_required(array('sharefm_id', 'shareto_id', 'pv_name'), $options)) return false;
		if ($this->GetShare($options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('pv_id', 'sharefm_id', 'shareto_id', 'pv_name', 'accept', 'is_video');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('pv_share');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateShare($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('pv_id', 'sharefm_id', 'shareto_id', 'pv_name', 'accept', 'is_video');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'sharefm_id', 'shareto_id', 'pv_name');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('pv_share');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetShare($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'pv_id', 'sharefm_id', 'shareto_id', 'pv_name', 'accept', 'is_video');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('pv_share');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteShare($options = array())	{
		$qualificationArray = array('id', 'pv_id', 'sharefm_id', 'shareto_id', 'pv_name', 'accept', 'is_video');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('pv_share');
	}	
	function AddComment($options = array()) {
		// required values
		if(!$this->_required(array('PV_id', 'upload_id', 'user_id', 'viewer_user_id', 'comment'), $options)) return false;
		if ($this->GetComment($options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('PV_id', 'upload_id', 'user_id', 'viewer_user_id', 'name', 'type', 'comment', 'comment_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('pv_comment');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateComment($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('PV_id', 'upload_id', 'user_id', 'viewer_user_id', 'name', 'type', 'comment', 'comment_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'PV_id', 'upload_id', 'user_id', 'viewer_user_id', 'name', 'type', 'comment', 'comment_date');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('pv_comment');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetComment($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'PV_id', 'upload_id', 'user_id', 'viewer_user_id', 'name', 'type', 'comment', 'comment_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('pv_comment');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteComment($options = array())	{
		$qualificationArray = array('id', 'PV_id', 'upload_id', 'user_id', 'viewer_user_id', 'name', 'type', 'comment', 'comment_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('pv_comment');
	}
	function AddProfilePicture($options = array()) {
		// required values
		if(!$this->_required(array('user_id', 'profile_picture'), $options)) return false;
		if ($this->GetProfilePicture($options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('user_id', 'profile_picture');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('profile_picture');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateProfilePicture($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('user_id', 'profile_picture');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'user_id', 'profile_picture');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('profile_picture');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetProfilePicture($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'user_id', 'profile_picture');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('profile_picture');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteProfilePicture($options = array())	{
		$qualificationArray = array('id', 'user_id', 'profile_picture');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('profile_picture');
	}	
	function AddPictureVideo($options = array()) {
		// required values
		if(!$this->_required(array('owner_path', 'name'), $options)) return false;
		if ($this->GetPictureVideo($options)) return false;
		// default values
		$options = $this->_default(array('picture_video' => 'pictures'), $options);
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('owner_path', 'picture_video', 'viewer_group', 'name', 'comments', 'upload_id');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('picture_video');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdatePictureVideo($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('owner_path', 'picture_video', 'viewer_group', 'name', 'comments', 'upload_id');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'owner_path', 'picture_video', 'viewer_group', 'name', 'comments', 'upload_id');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('picture_video');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetPictureVideo($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'owner_path', 'picture_video', 'viewer_group', 'name', 'comments', 'upload_id');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('picture_video');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeletePictureVideo($options = array())	{
		$qualificationArray = array('id', 'owner_path', 'picture_video', 'viewer_group', 'name', 'comments', 'upload_id');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('picture_video');
	}
	function AddMain($options = array()) {
		// required values
		if(!$this->_required(array('ShortDesc', 'Source', 'Name', 'Ext'), $options)) return false;
		if ($this->GetMain($options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('ShortDesc', 'Source', 'Name', 'Ext', 'SearchGroup');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('main');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateMain($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('ShortDesc', 'Source', 'Name', 'Ext', 'SearchGroup');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'ShortDesc', 'Source', 'Name', 'Ext', 'SearchGroup');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('main');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetMain($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'ShortDesc', 'Source', 'Name', 'Ext', 'SearchGroup');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('main');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteMain($options = array())	{
		$qualificationArray = array('id', 'ShortDesc', 'Source', 'Name', 'Ext', 'SearchGroup');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('main');
	}
	function AddVisitor($options = array()) {
		// required values
		if(!$this->_required(array('visitdate', 'visitcount', 'ip', 'os', 'city', 'region', 'country', 'postal_code'), $options)) return false;
		if ($this->GetVisitor($options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('visitdate', 'visitcount', 'ip', 'os', 'city', 'region', 'country', 'postal_code');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// MD5 the password if it is set
		$this->db->insert('visitor');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateVisitor($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('visitdate', 'visitcount', 'ip', 'os', 'city', 'region', 'country', 'postal_code');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'visitdate', 'visitcount', 'ip', 'os', 'city', 'region', 'country', 'postal_code');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('visitor');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetVisitor($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'visitdate', 'visitcount', 'ip', 'os', 'city', 'region', 'country', 'postal_code');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('visitor');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteVisitor($options = array())	{
		$qualificationArray = array('id', 'visitdate', 'visitcount', 'ip', 'os', 'city', 'region', 'country', 'postal_code');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('visitor');
	}	
	function AddUserScreen($options = array()) {
		// required values
		if(!$this->_required(array('user_id', 'screen_width', 'screen_height', 'screen_availWidth', 'screen_availHeight', 'screen_colorDepth', 'screen_pixelDepth'), $options)) return false;
		if ($this->GetUserScreen($options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('user_id', 'screen_width', 'screen_height', 'screen_availWidth', 'screen_availHeight', 'screen_colorDepth', 'screen_pixelDepth', 'activity_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('user_screen');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateUserScreen($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('user_id', 'screen_width', 'screen_height', 'screen_availWidth', 'screen_availHeight', 'screen_colorDepth', 'screen_pixelDepth', 'activity_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'user_id', 'screen_width', 'screen_height', 'screen_availWidth', 'screen_availHeight', 'screen_colorDepth', 'screen_pixelDepth', 'activity_date');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('user_screen');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetUserScreen($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'user_id', 'screen_width', 'screen_height', 'screen_availWidth', 'screen_availHeight', 'screen_colorDepth', 'screen_pixelDepth', 'activity_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('user_screen');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteUserScreen($options = array())	{
		$qualificationArray = array('id', 'user_id', 'screen_width', 'screen_height', 'screen_availWidth', 'screen_availHeight', 'screen_colorDepth', 'screen_pixelDepth', 'activity_date');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('user_screen');
	}	
	function AddTalkMessage($options = array()) {
		// required values
		if(!$this->_required(array('message'), $options)) return false;
		if ($this->GetTalkMessage($options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('message');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('talk_message');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateTalkMessage($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('message');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'message');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('talk_message');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetTalkMessage($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'message');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('talk_message');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteTalkMessage($options = array())	{
		$qualificationArray = array('id', 'message');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('talk_message');
	}	
	function AddSChatMessages($options = array()) {
		// required values
		if(!$this->_required(array('user', 'when', 'msg_time'), $options)) return false;
		if ($this->GetSChatMessages($options)) return false;
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array('user', 'msg_id', 'when', 'msg_time', 'talk_to', 'show_flag');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->insert('s_chat_messages');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}
	function UpdateSChatMessages($options = array())	{
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array('user', 'msg_id', 'when', 'msg_time', 'talk_to', 'show_flag');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
		}
		$whereArray = array('id', 'user', 'msg_id', 'when', 'msg_time', 'talk_to', 'show_flag');
		foreach($whereArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// Execute the query
		$this->db->update('s_chat_messages');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}
	function GetSChatMessages($options = array()) {
		// default values
		$options = $this->_default(array('sortDirection' => 'asc'), $options);
		// Add where clauses to query
		$qualificationArray = array('id', 'user', 'msg_id', 'when', 'msg_time', 'talk_to', 'show_flag');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit'])) $this->db->limit($options['limit']);
		// sort
		if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
		$query = $this->db->get('s_chat_messages');
		if($query->num_rows() == 0) return false;
		if(isset($options['id'])) {
			// If we know that we're returning a singular record, then let's just return the object
			return $query->row(0);
		}
		else {
			// If we could be returning any number of records then we'll need to do so as an array of objects
			return $query->result();
		}
	}
	function DeleteSChatMessages($options = array())	{
		$qualificationArray = array('id', 'user', 'msg_id', 'when', 'msg_time', 'talk_to', 'show_flag');
		foreach($qualificationArray as $qualifier) {
			if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
		}	
		$this->db->delete('s_chat_messages');
	}		
}