<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImgFilter extends MY_Controller {
	public function index()
	{
		if($this->input->get('filename')) $filename = $this->input->get('filename');
		else $filename = "/images/Question.jpg";
		if($this->input->get('Smoothvalue')) $Smoothvalue =$this->input->get('Smoothvalue');
		if($this->input->get('imgEffect')) $imgEffect = $this->input->get('imgEffect');
		if($this->input->get('red')) $red = $this->input->get('red');
		else $red=0;
		if($red>255) $red=255;
		if($red<0) $red=0;
		if($this->input->get('green')) $green = $this->input->get('green');
		else $green = 0;
		if($green>255) $green=255;
		if($green<0) $green=0;
		if($this->input->get('blue')) $blue = $this->input->get('blue');
		else $blue = 0;
		if($blue>255) $blue=255;
		if($blue<0) $blue=0;
		if($this->input->get('Contrast')) $Contrast = $this->input->get('Contrast');
		else $Contrast=0;
		if($Contrast>255) $Contrast=255;
		if($Contrast<-255) $Contrast=-255;
		if($this->input->get('Brightness')) $Brightness = $this->input->get('Brightness');
		else $Brightness = 0;
		if($Brightness>255) $Brightness=255;
		if($Brightness<-255) $Brightness=-255;
		if($this->input->get('Pixelate')) $Pixelate = $this->input->get('Pixelate');
		if($this->input->get('toNewPic')) {
			$toNewPic = $this->input->get('toNewPic');
		}
		else $toNewPic = "";
		if($this->input->get('beforAddText')) {
			$beforAddText = $this->input->get('beforAddText');
		}

		$pieces = explode(",", $imgEffect);

		header('Content-type: image/jpeg');
		$image = imagecreatefromjpeg("$filename");
		foreach($pieces as $key=>$value){
		  if($value==10) imagefilter($image, (int)$value,$Smoothvalue);
		  elseif($value==2) imagefilter($image, (int)$value,$Brightness);
		  elseif($value==3) imagefilter($image, (int)$value,$Contrast);
		  elseif($value==4) imagefilter($image, (int)$value,$red,$green,$blue);
		  elseif($value==11) imagefilter($image, (int)$value,$Pixelate);
		  else imagefilter($image, (int)$value);
		} 
		if(isset($beforAddText)) imagejpeg($image,$beforAddText);
		if($toNewPic=="") {
		  imagejpeg($image);
		  imagedestroy($image);
		 }
		else {
		  imagejpeg($image,$toNewPic);
		  imagedestroy($image);
		  readfile($toNewPic);
		}  
	}
}
