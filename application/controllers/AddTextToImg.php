<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AddTextToImg extends MY_Controller {
	public function index()
	{
		// <img src="AddTextToImg.php?x=+new Date().getTime()&text_to_display=this is testing&font=../fonts/timesbi.TTF&fontsize=24&rotation=0&pad=0&red=0&green=0&FontB=0&bg_red=255&bg_green=255&bg_blue=255&tr=1"  id="image" />
			$text = new Textjpeg;
			$text->filename = $this->input->get('filename'); // text to display
			$text->text_to_display = $this->input->get('text_to_display'); // text to display
			$text->font = '.'.$this->input->get('font'); // font to use (include directory if needed).
			$text->fontsize = $this->input->get('fontsize'); // fontsize in points
			$text->textrotate = $this->input->get('textrotate'); // rotation
			$text->pad = $this->input->get('pad'); // padding in pixels around text.
			$text->FontR = $this->input->get('FontR'); // text color
			$text->FontG = $this->input->get('FontG'); // ..
			$text->FontB = $this->input->get('FontB'); // ..
			$text->bg_red = $this->input->get('bg_red'); // background color.
			$text->bg_green = $this->input->get('bg_green'); // ..
			$text->bg_blue = $this->input->get('bg_blue'); // ..
			$text->transparent = $this->input->get('tr'); // transparency flag (boolean).
			$text->positionx = $this->input->get('positionx'); // ..
			$text->positiony = $this->input->get('positiony'); // ..
			if($this->input->get('toNewPic')) {
				$text->toNewPic = $this->input->get('toNewPic');
		//		unlink($toNewPic);
			}
			else $text->toNewPic = "";
			$text->draw(); // GO!!!!!
	}
}
