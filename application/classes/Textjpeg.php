<?php
class Textjpeg 
{
	var $font = '../fonts/timesbi.TTF'; //default font. directory relative to script directory.
	var $filename="";
	var $text_to_display = ''; // default text to display.
	var $fontsize = 24; // default font size.
	var $textrotate = 0; // rotation in degrees.
	var $pad = 0; // padding.
	var $transparent = 1; // transparency set to on.
	var $FontR = 0; // black text...
	var $FontG = 0;
	var $FontB = 0;
	var $bg_red = 255; // on white background.
	var $bg_green = 255;
	var $bg_blue = 255;
	var	$positionx = 0;
	var	$positiony = 0;
	var $toNewPic="";
	
	function draw() 
	{
		$width = 0;
		$height = 0;
		$bounds = array();
		$image = "";
	    if($this->text_to_display=="") $this->text_to_display="Output Sample";
		// get the font height.
		$bounds = ImageTTFBBox($this->fontsize, $this->textrotate, $this->font, "W");
		if ($this->textrotate < 0) 
		{
			$font_height = abs($bounds[7]-$bounds[1]);		
		} 
		else if ($this->textrotate > 0) 
		{
		$font_height = abs($bounds[1]-$bounds[7]);
		} 
		else 
		{
			$font_height = abs($bounds[7]-$bounds[1]);
		}
		// determine bounding box.
		$bounds = ImageTTFBBox($this->fontsize, $this->textrotate, $this->font, $this->text_to_display);
		if ($this->textrotate < 0) 
		{
			$width = abs($bounds[4]-$bounds[0]);
			$height = abs($bounds[3]-$bounds[7]);
			$this->offset_y = $font_height+$this->positiony;
			$this->$offset_x = $this->positionx;
		} 
		else if ($this->textrotate > 0) 
		{
			$width = abs($bounds[2]-$bounds[6]);
			$height = abs($bounds[1]-$bounds[5]);
			$this->offset_y = abs($bounds[7]-$bounds[5])+$font_height+$this->positiony;
			$this->offset_x = abs($bounds[0]-$bounds[6])+$this->positionx;
		} 
		else
		{
			$width = abs($bounds[4]-$bounds[6]);
			$height = abs($bounds[7]-$bounds[1]);
			$this->offset_y = $font_height+$this->positiony;
			$this->offset_x = $this->positionx;
		}
		$image = imagecreatefromstring(file_get_contents($this->filename));

//		$image = imagecreate($width+($this->pad*2)+1,$height+($this->pad*2)+1);
		$background = ImageColorAllocate($image, $this->bg_red, $this->bg_green, $this->bg_blue);
		$foreground = ImageColorAllocate($image, $this->FontR, $this->FontG, $this->FontB);
	
		if ($this->transparent) ImageColorTransparent($image, $background);
		ImageInterlace($image, false);
	
		// render the image
		imagettftext($image, $this->fontsize, $this->textrotate, $this->offset_x+$this->pad, $this->offset_y+$this->pad, $foreground, $this->font, $this->text_to_display);
		Header("Content-type: image/jpeg");
		// output jpeg object.
		if($this->toNewPic!="")  imagejpeg($image,$this->toNewPic);
		else imagejpeg($image);
		imagedestroy($image);
		if($this->toNewPic!="") readfile($this->toNewPic); 
		}
	}
