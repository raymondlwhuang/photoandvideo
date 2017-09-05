<?php 
class ResizeImg{
 var $imgSrc,$myImage,$newwidth,$newheight,$width,$height; 
	public static function ResizeImage($image,$max_longer_length,$max_shorter_length,$toNewPic)
	{
	   list($width, $height,$type, $attr) = getimagesize($image); 	//getting the image dimensions
	   $myImage = imagecreatefromstring(file_get_contents($image)) or die("Error: Cannot find image!"); 	//create image from the jpeg
	   if($width>=$height) {
			if($width>$max_longer_length) {
			   $rate=$max_longer_length/$width;
			   $newwidth = $max_longer_length;
			   $newheight = $height * $rate;
		    }
			else {
			   $newwidth = $width;
			   $newheight = $height;
			}
	   }
	   else{
			if($height>$max_longer_length) {
			   $rate=$max_longer_length/$height;
			   $newwidth = $width * $rate;
			   $newheight = $max_longer_length;
		    }
			else {
			   $newwidth = $width;
			   $newheight = $height;
			}
	   }
		$newimage = imageCreateTrueColor($newwidth, $newheight) or die ('failed imageCreateTrueColor'); 

		imageCopyResampled($newimage, $myImage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height) or die ('failed imageCopyResampled');

		imagejpeg($newimage,$toNewPic);
		imagedestroy($newimage); 
	}	
}