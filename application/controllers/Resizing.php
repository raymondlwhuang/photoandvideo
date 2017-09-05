<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resizing extends MY_Controller {
	public function index()
	{
		if($this->input->get('filename')) {
			$filename = $this->input->get('filename');
			if($this->input->get('toNewPic'))$toNewPic = $this->input->get('toNewPic');
			else $toNewPic = "";
			if($this->input->get('Percent')) $Percent = $this->input->get('Percent');
			else $Percent = 100;
			if($this->input->get('EditMode')) $EditMode = $this->input->get('EditMode');
			else $EditMode = 0;
			if($this->input->get('beforAddText')) $beforAddText = $this->input->get('beforAddText');	
			$image = new ImgResize;
			$image->setImage($filename);
			$image->ResizeImage($Percent);
			if(isset($beforAddText)) $image->renderImage2($beforAddText);
			$image->renderImage($toNewPic);
		}
	}
}
