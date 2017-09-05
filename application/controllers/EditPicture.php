<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EditPicture extends MainController {
	public function index()
	{
		if($this->session->userdata('id')) $tempname=$this->session->userdata('id'); else $tempname=$this->session->userdata('session_id');
		if ($_FILES){
			$limit_size=10* 1024*1000;
			$name = $_FILES['infile']['name']; 
			$thisPicture = "./Orgpictures/";
			@mkdir("$thisPicture");
			
			$targetfilepath= $thisPicture . $name;
			$file_size=$_FILES['infile']['size'];
			if($file_size <= $limit_size){
				$result = move_uploaded_file($_FILES['infile']['tmp_name'], $targetfilepath); 
				if ($result){
					echo "
						<script type=\"text/javascript\">
							window.open('EditPicture?thisPicture=$targetfilepath',target='_top');
						</script>";
					exit();
				}
			}
		}

		if($this->input->get('thisPicture')) $thisPicture=$this->input->get('thisPicture');
		else $thisPicture='./Orgpictures/demo.jpg';
		if($this->input->get('org_picture')) $org_picture=$this->input->get('org_picture');
		else $org_picture=$thisPicture;
		$targetfilepath = "./".date('YMd')."/";
		@mkdir("$targetfilepath");
		$pieces = explode("/", $thisPicture);
		if(isset($pieces[4])) {
		 $ext = explode(".", $pieces[4]);
		}
		elseif(isset($pieces[3])) {
		 $ext = explode(".", $pieces[3]);
		}
		else {
		 $ext = explode(".", $pieces[2]);
		}
		if(isset($tempname)) $this->data['tempname']=$tempname;
		$this->data['CropPic']=$targetfilepath."temp".$tempname.".jpg";
		$this->data['firstPic']=$targetfilepath."temp5".$tempname.".jpg";
		$this->data['toNewPic']=$targetfilepath."temp1".$tempname.".jpg";
		$this->data['toNewPic2']=$targetfilepath."temp2".$tempname.".jpg";
		$this->data['toNewPic3']=$targetfilepath."temp3".$tempname.".jpg";
		$this->data['toNewPic4']=$targetfilepath."temp4".$tempname.".jpg";
		$this->data['beforAddText']=$targetfilepath."temp6".$tempname.".jpg";
		if(isset($thisPicture)) $this->data['thisPicture']=$thisPicture;
		if(isset($org_picture)) $this->data['org_picture']=$org_picture;
		$image = new CopyImg;
		$image->setImage("$thisPicture",7,"$targetfilepath","temp","$tempname");
		$EditMode=true;
		$this->load->view("editPicture_header",$this->data);
		if($this->session->userdata('id')) {
			$detect = new Mobiledtect;
			$this->data['layout'] = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
			$this->load->view("header",$this->data);
		}
		else {
			$this->load->view("header2",$this->data);
		}		
		$this->load->view("editPicture_view",$this->data);
	}
}
