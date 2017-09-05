<?php
class CropImage{
	function setImage($filename,$x,$y,$cropWidth,$cropHeight)
	{
	   list($width, $height) = getimagesize($filename); 
	   $this->myImage = imagecreatefromstring(file_get_contents($filename)) or die("Error: Cannot find image!"); 
						 
	//The crop size will be half that of the largest side 
	   $this->cropWidth   = $cropWidth; 
	   $this->cropHeight  = $cropHeight; 
	   $this->Width   = $width; 
	   $this->Height  = $height; 
						 
	//getting the top left coordinate
	   $this->x = $x;
	   $this->y = $y;
				 
	}  

	function createThumb()
	{
	  $this->thumb = imageCreateTrueColor($this->cropWidth, $this->cropHeight); 
	  imageCopyResampled($this->thumb, $this->myImage, 0, 0,$this->x, $this->y, $this->Width, $this->Height, $this->Width, $this->Height); 
	}
	function renderImage($CropPic="")
	{
	   header('Content-type: image/jpeg');
	   if($CropPic=="") {
		   imagejpeg($this->thumb);
		   imagedestroy($this->thumb); 
	   }
	   else { 
		   imagejpeg($this->thumb,$CropPic);
		   readfile($CropPic); 
		   imagedestroy($CropPic);
	   }
	}
}
