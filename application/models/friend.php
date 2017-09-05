<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Friend extends CI_model {
	function get($id) {
		$options=array(
			'table'=>'view_permission',
			'viewer_id'=>$id,
			'groupBy'=>'owner_email'
		);
		$get=$this->general_model->_get($options);
		foreach($get as $row){
			$options2=array(
				'table'=>'user',
				'email_address'=>$row->owner_email
			);			
			$get2=$this->general_model->_get($options2);			
			$profile_picture[$row->owner_path]=$get2[0]->profile_picture;
		}

		$profile_picture['public'] = "../images/profile/public.jpg";
		$profile_picture['owner_path']=$this->session->userdata('profile_picture');
		return $profile_picture;
	}
}