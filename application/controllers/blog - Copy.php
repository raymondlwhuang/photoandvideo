<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends MY_Controller {
	public function __construct() 
	{ 
		parent::__construct(); 
		@session_start();
		$cookie_name = 'siteAuth';
		$cookie_time = 2592000; // 30 days
//		ini_set('display_errors', 'On'); //off
//		error_reporting(-1);	// 0
//		define('MP_DB_DEBUG', true);  // false
		if(!isset($_SESSION['username'])) $this->load->view('autologin.php');
		if (get_magic_quotes_gpc())		{
			$_GET = $this->function_model->_stripslashes_rcurs($_GET);////
			$_POST = $this->function_model->_stripslashes_rcurs($_POST);
		}
		if(!isset( $_COOKIE["greeting"] )) setcookie( "greeting", "Welcome back", time() + 60 * 60 * 24 * 365, "", "", false, true ); 
		
		/*
		if (isset($_SESSION["page"]) && $_SESSION["page"]== "HER") {
			unset($_SESSION["page"]);
			$this->load->view('top');
			$this->load->view('HERecorder');
			$this->load->view('footer');
		}
		elseif (isset($_SESSION["page"]) && $_SESSION["page"] == "HELP") {
			unset($_SESSION["page"]);
			$this->load->view('top');
			$this->load->view('ResultDisp');
			$this->load->view('footer');
		}	*/
	} 
	 
	public function index()
	{
		$orgurl= explode("/",rtrim($_SERVER['REQUEST_URI'],"/"));
		$url='index';
		if(isset($orgurl[1])){
			$pieces = explode("?", $orgurl[1]);
			$url=$pieces[0];
		}
		if(isset($_SESSION['private'])) $header='header';
		else $header='header2';
		$thisurl='index';
		$footer='footer';
		$data=array();
		$standalone=1;
		if(isset($pieces[1])) {
			$pieces2 = explode("=", $pieces[1]);
			for($i=0;$i<count($pieces2);$i+=2){
				$data[$pieces2[$i]]=$pieces2[$i+1];
			}
		}
		if(isset($url) && $url!="") $thisurl=$url;
		if($thisurl=='index' || $thisurl=='index.php') {
			if ($this->input->post('submit'))
			{
				$options=array(
					'table'=>'user',
					'email_address'=>strtolower($this->input->post('email_address')),
					'password'=>MD5($this->input->post('password'))
				);
				$get=$this->general_model->_get($options);
				if($get) {
					foreach($get as $key){
						foreach ($key as $name=>$value) $data[$name]=$value;
					}
					$data['autologin']=$this->input->post('autologin');
					$this->load->view('do_login',$data);
//					$this->load->view('index',$data);
				/*

					echo $this->input->post('password');// do something
					*/
//					exit();
				}
			}		
			$newdate = strtotime ( "-2 day" , time() ) ;
			$dir = "../".date('YMd',"$newdate");
			$this->function_model->SureRemoveDir($dir,true);
			$data['ip']=$this->function_model->getIpAddr();
			if(isset($_SESSION['private'])) {
				$this->load->model('friend');
				$data['profile_picture']=$this->friend->get($_SESSION["id"]);	
			}
		}
		elseif($thisurl=='PictureMain' || $thisurl=='PictureMain.php') {
			$standalone=1;
		}
		if($standalone==0) $this->load->view($header,$data);		
		$this->load->view($thisurl,$data);		
		if($standalone==0) $this->load->view($footer,$data);		
//		$fields = $this->db->field_data('user');
		
//		$this->load->model("user");
//		$condition=array('id >' =>2);
//		$data=array('name' => 3);
//		$test=$this->user->update(array('data'=>$data,'conditions'=>$condition));
//		echo $test;
	//	$this->load->view('test',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */