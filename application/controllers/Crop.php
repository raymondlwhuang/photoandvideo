<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crop extends MY_Controller {
	public function index()
	{
		if($this->input->get('filename')) {
			if ($this->input->get('filename')) $filename = $this->input->get('filename'); // text to display
			if ($this->input->get('x')) $x = $this->input->get('x'); // font to use (include directory if needed).
			if ($this->input->get('y')) $y = $this->input->get('y'); // fontsize in points
			if ($this->input->get('cropWidth')) $cropWidth = $this->input->get('cropWidth'); // cropWidth
			if ($this->input->get('cropHeight')) $cropHeight = $this->input->get('cropHeight'); // cropHeightding in pixels around text.
			if($this->input->get('CropPic')) {
			$CropPic = $this->input->get('CropPic');
		//	unlink($CropPic);
			}
			else $CropPic = "";	
			$image = new CropImage;
			$image->setImage($filename,$x,$y,$cropWidth,$cropHeight);
			$image->createThumb();
			$image->renderImage($CropPic);
		}
	}
}
