<?php	
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