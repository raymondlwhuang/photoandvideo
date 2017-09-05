<?php
class ImageOnImage{
 var $firstImgSrc,$firstImage,$secondImgSrc,$secondImage,$firstImgWidth,$firstImgHeight,$secondImgWidth,$secondImgHeight,$ratio,$secondNewWith,$secondNewHeight,$SecondImageSmall,$newimage; 
	function setImage($first_img,$second_img)
	{
	   $this->firstImgSrc = $first_img; // the first_img source
	   list($firstWidth, $firstHeight,$firstType, $firstAttr) = getimagesize($this->firstImgSrc); 	//getting the image dimensions
	   $this->firstImage = imagecreatefromstring(file_get_contents($this->firstImgSrc)) or die("Error: Cannot find image!"); 	//create image from the jpeg
	   $this->secondImgSrc = $second_img; // the second_img source
	   list($secondWidth, $secondHeight,$secondType, $secondAttr) = getimagesize($this->secondImgSrc); 	//getting the image dimensions
	   $this->secondImage = imagecreatefromstring(file_get_contents($this->secondImgSrc)) or die("Error: Cannot find image!"); 	//create image from the jpeg
	   $this->firstImgWidth = $firstWidth;
	   $this->firstImgHeight = $firstHeight;
	   $this->secondImgWidth = $secondWidth;
	   $this->secondImgHeight = $secondHeight;
	   $this->ratio=$firstWidth/$secondWidth;
	} 
	function ResizeSecondToPercentOfFistImage($Percent)
	{
	    $this->secondNewWith = $this->secondImgWidth * $this->ratio * $Percent/100;
	    $this->secondNewHeight = $this->secondImgHeight * $this->ratio  * $Percent/100;
		$this->SecondImageSmall = imageCreateTrueColor($this->secondNewWith, $this->secondNewHeight) or die ('failed imageCreateTrueColor'); 
		imageCopyResampled($this->SecondImageSmall, $this->secondImage, 0, 0, 0, 0, $this->secondNewWith, $this->secondNewHeight, $this->secondImgWidth, $this->secondImgHeight) or die ('failed imageCopyResampled');
		$img_adj_width=$this->secondNewWith+10; 
		$img_adj_height=$this->secondNewHeight+10;
		$this->newimage=imagecreatetruecolor($img_adj_width,$img_adj_height);
		// add border to image
		imagefilledrectangle($this->newimage,0,0,$img_adj_width,$img_adj_height,16777215);

		imageCopyResampled($this->newimage,$this->SecondImageSmall,2,2,0,0,$this->secondNewWith-4,$this->secondNewHeight-4,$this->secondNewWith,$this->secondNewHeight); 
	
	}
	function renderImage($Xposition, $Yposition,$pct)
	{
	   imagecopymerge($this->firstImage, $this->newimage, $Xposition, $Yposition, 0, 0, $this->secondNewWith, $this->secondNewHeight, $pct); 
	   header('Content-type: image/jpeg');
	   imagejpeg($this->firstImage);
	   imagedestroy($this->firstImage); 
	}	
}