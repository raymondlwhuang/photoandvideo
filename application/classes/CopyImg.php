<?php 
/*
$filename = "../images/Question.jpg";
$targetfilepath = "../".date('YMd')."/";
@mkdir("$targetfilepath");
	$image = new CopyImg;
	$image->setImage($filename,7,$targetfilepath,"temp","3");
	// use sequence table for termperarly name
	*/
class CopyImg{
 var $myImag; 
	function setImage($image,$nCopy=1,$targetfilepath="",$prefix="temp",$tempname="")
	{
	   list($width, $height,$type, $attr) = getimagesize($image); 	//getting the image dimensions
	   $this->myImage = imagecreatefromstring(file_get_contents($image)) or die("Error: Cannot find image!"); 	//create image from the jpeg
	   $dest = imageCreateTrueColor($width, $height);
	   imagecopy($dest, $this->myImage, 0, 0, 0, 0, $width, $height);
	   if(substr($targetfilepath,0,1)!='.') $targetfilepath='.'.$targetfilepath;
	   $CopyToName="$targetfilepath"."$prefix"."$tempname".".jpg";
	   if($image!=$CopyToName) {
		   //unlink($CopyToName);
		   imagejpeg($dest,$CopyToName);
	   }
	   if($nCopy > 1) {
			for($i=1;$i<$nCopy;$i++) {
				$CopyToName=$targetfilepath.$prefix.$tempname.$i.".jpg";
				//$CopyToName=ltrim($CopyToName,'.');
				if($image!=$CopyToName) {
					//unlink($CopyToName);
					sleep(1);
					imagejpeg($dest,$CopyToName);
				}
				//imagedestroy($CopyToName); 
			}
	   }
	   imagedestroy($dest); 
	} 
}
