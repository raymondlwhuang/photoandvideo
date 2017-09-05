<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImgRoundCorner extends MY_Controller {
	public function index()
	{
		$filename = $this->input->get('filename');
		if(substr($filename,0,1)!='.') $filename='.'.$filename;
		$radius=$this->input->get('radius'); // The default corner radius is set to 20px
		if($this->input->get('degrees') && $this->input->get('degrees')!=0)	$degrees = $this->input->get('degrees');
		else $degrees =0;
		if($this->input->get('topleft') and $this->input->get('topleft') == "no") $topleft=false;
		else $topleft=true; // Top-left rounded corner is shown by default
		if($this->input->get('bottomleft') and $this->input->get('bottomleft') == "no")	$bottomleft=false; 
		$bottomleft=true; // Bottom-left rounded corner is shown by default
		if($this->input->get('bottomright') and $this->input->get('bottomright') == "no") $bottomright=false;
		else $bottomright=true; // Bottom-right rounded corner is shown by default
		if($this->input->get('topright') and $this->input->get('topright') == "no") $topright = false;
		else $topright=true; // Top-right rounded corner is shown by default
		if($this->input->get('toNewPic')) {
			$toNewPic = $this->input->get('toNewPic');
		}
		else $toNewPic = "";
		if($this->input->get('beforAddText')) $beforAddText = $this->input->get('beforAddText');

		$images_dir = './pictures/';
		$corner_source = imagecreatefrompng('./images/rounded_corner.png');

		$corner_width = imagesx($corner_source);  
		$corner_height = imagesy($corner_source);  
		$corner_resized = ImageCreateTrueColor($radius, $radius);
		ImageCopyResampled($corner_resized, $corner_source, 0, 0, 0, 0, $radius, $radius, $corner_width, $corner_height);

		$corner_width = imagesx($corner_resized);  
		$corner_height = imagesy($corner_resized);  
		$image = imagecreatetruecolor($corner_width, $corner_height);  
		$image = imagecreatefromjpeg($filename); // replace filename with $this->input->get('filename'] 
		$size = getimagesize($filename); // replace filename with $this->input->get('filename'] 
		$white = ImageColorAllocate($image,255,255,255);
		$black = ImageColorAllocate($image,0,0,0);

		// Top-left corner
		if ($topleft == true) {
			$dest_x = 0;  
			$dest_y = 0;  
			imagecolortransparent($corner_resized, $black); 
			imagecopymerge($image, $corner_resized, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
		} 

		// Bottom-left corner
		if ($bottomleft == true) {
			$dest_x = 0;  
			$dest_y = $size[1] - $corner_height; 
			$rotated = imagerotate($corner_resized, 90, 0);
			imagecolortransparent($rotated, $black); 
			imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
		}

		// Bottom-right corner
		if ($bottomright == true) {
			$dest_x = $size[0] - $corner_width;  
			$dest_y = $size[1] - $corner_height;  
			$rotated = imagerotate($corner_resized, 180, 0);
			imagecolortransparent($rotated, $black); 
			imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
		}

		// Top-right corner
		if ($topright == true) {
			$dest_x = $size[0] - $corner_width;  
			$dest_y = 0;  
			$rotated = imagerotate($corner_resized, 270, 0);
			imagecolortransparent($rotated, $black); 
			imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
		}

		// Rotate image
		$image = imagerotate($image, $degrees, $white);
		header("Content-type: image/jpeg");
		// Output final image
		if(isset($beforAddText)) imagejpeg($image,$beforAddText);
		if($toNewPic!="")  imagejpeg($image,$toNewPic);
		else imagejpeg($image);

		imagedestroy($image);  
		imagedestroy($corner_source);
		if($toNewPic!="") readfile($toNewPic); 
	}
}
