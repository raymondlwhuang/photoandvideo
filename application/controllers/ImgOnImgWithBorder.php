<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImgOnImgWithBorder extends MY_Controller {
	public function index()
	{
			if($this->input->get('first_img')) $first_img = $this->input->get('first_img');
			else $first_img = "./images/Folder.png";
			if($this->input->get('second_img')) $second_img = $this->input->get('second_img');
			else $second_img = "./images/Question.jpg";
			list($FirstImg_w, $FirstImg_h, $FirstImg_t, $FirstImg_a) = getimagesize($first_img);
			// pointer position:
			if($this->input->get('Xposition')) $Xposition = $this->input->get('Xposition');
			else $Xposition = 25;
			if($this->input->get('Yposition')) $Yposition = $this->input->get('Yposition');
			else $Yposition = 20;
			if($this->input->get('pct')) $pct = $this->input->get('pct');
			else $pct = 100;
			if($this->input->get('ResizePct')) $ResizePct = $this->input->get('ResizePct');
			else $ResizePct = 75;
			$image = new ImageOnImage;
			$image->setImage($first_img,$second_img);
			$image->ResizeSecondToPercentOfFistImage($ResizePct);
			$image->renderImage($Xposition,$Yposition,$pct);
	}
}
