<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
	public function __construct() 
	{ 
		parent::__construct();
		ini_set('display_errors', 'off'); //On
		error_reporting(0);	// -1
		define('MP_DB_DEBUG', false);  // true
		if($this->session->userdata('private')) {
			$private=$this->function_model->newdecode($this->session->userdata('private'));
			if(substr($private,0,-4)==($this->session->userdata('first_name').date("Ymd"))){
			$cur=(int)date("Hi");
			$org=(int)substr($private,strlen($private)-4);
				if (abs($cur-$org) >360) {
					$this->session->sess_destroy();
					redirect('/', 'refresh');
					die();
				}
			}
		}
	} 
}
class MainController extends MY_Controller
{
	public $data=array();
	public $cookie_name;
	public $cookie_time;
	public function __construct() 
	{ 
		parent::__construct();
		$this->cookie_name = 'siteAuth';
		$this->cookie_time = 2592000; // 30 days
		$newdate = strtotime ( "-2 day" , time() ) ;
		$dir = "/".date('YMd',"$newdate");
		$this->function_model->SureRemoveDir($dir, true);

		if(!$this->session->userdata('id'))
		{
				// Check if the cookie exists
			if(isSet($_COOKIE[$this->cookie_name]))
			{
				parse_str($_COOKIE[$this->cookie_name]);
				//$usr = $usr;
				$get=$this->user->Get($usr,$hash);
				if($get) {
					$config_username = $usr;
					$config_password = $hash;
					$gethash=ucfirst(strtolower($get->first_name)).date("YmdHi");
					$private=$this->function_model->newencode($gethash);
					$this->session->set_userdata('first_name',ucfirst(strtolower($get->first_name)));
					$this->session->set_userdata('last_name',ucfirst(strtolower($get->last_name)));
					$this->session->set_userdata('id',$get->id);
					$this->session->set_userdata('owner_path',$get->owner_path);
					$this->session->set_userdata('name',ucfirst(strtolower($get->first_name))." ".ucfirst(strtolower($get->last_name)));
					$this->session->set_userdata('profile_picture',$get->profile_picture);
					$this->session->set_userdata('admin',$get->is_super_admin);
					$this->session->set_userdata('discoverable',$get->discoverable);
					$this->session->set_userdata('username',$usr);
					$this->session->set_userdata('email_address',$usr);
					$this->session->set_userdata('private',$private);
					$this->session->set_userdata('MsgBox',"no");
					$this->user->Update($usr);
					$this->view_permission->Set_activity($usr);
				}
				  
			}
			if(isSet($_COOKIE['Pid']))
			{
				parse_str($_COOKIE['Pid']);
				$this->session->set_userdata('pin',$this->function_model->newdecode($pin));
			}
		}
	}
}
