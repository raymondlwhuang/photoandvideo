<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Make your Selection</title>
<?php echo link_tag('css/picturegroup.css'); ?>
</head>
<body class="darkblue" onload="parent.alertsize($(document).height(),2);">
<center>
<font id="SharedPicture"></font><br/>
<?php
if($this->session->userdata('id') && $FriendID==$this->session->userdata('id')) {
	$count=0;
	$showed=false;
	if($queryShare){
		foreach($queryShare as $row5)
		{
			$count++;
			if($count>5) break;
			if($row5->is_video==0) {
				if($showed==false) {
					echo "<input type=\"image\"  class=\"shakeimage\" onMouseover=\"init(this);rattleimage()\"  onMouseout=\"stoprattle(this);top.focus()\" src='ImgOnImgWithBorder?second_img=.$row5->pv_name' alt='' onClick=\"stoprattle(this);top.focus();document.getElementById('SharedPicture').innerHTML='Viewing shared photos';t = setInterval(blink, 500);Action('LastActivity?user_id=".$this->session->userdata('id')."','maincontent');document.getElementById('FriendID').value='SharedPicture';refreshiframe('SharedPicture');\"/><br/>";
					$showed=true;
				}	
				else echo "<img src='$row5->pv_name' height='30'/>";
			}
		}
	}
	if($showed==false && $shardCount!=0) {
		echo "<input type=\"image\"  class=\"shakeimage\" onMouseover=\"init(this);rattleimage()\"  onMouseout=\"stoprattle(this);top.focus()\" src='/images/Folder.png' alt='' onClick=\"stoprattle(this);top.focus();document.getElementById('SharedPicture').innerHTML='Viewing shared photos';t = setInterval(blink, 500);Action('LastActivity?user_id=".$this->session->userdata('id')."','maincontent');document.getElementById('FriendID').value='SharedPicture';refreshiframe('SharedPicture');\"/><br/>";
	}	
	if($shardCount!=0) echo "<br/>Photos shared to you from friends<br/>";
}
echo "<input type='image' src=\"/images/first2.png\" id='first' onClick=\"PictureList('first',$FriendID);\">";
echo " ";
echo "<input type='image' src=\"/images/previous2.png\" id='previous'  onClick=\"PictureList('previous',$FriendID);\">";
echo "<input type='image' src=\"/images/next1.png\" id='next' onClick=\"PictureList('next',$FriendID);\">";
echo " ";
echo "<input type='image' src=\"/images/last.png\" id='last'  onClick=\"PictureList('last',$FriendID);\"><br/>";
?>
</center>
<div id="maincontent"></div>
<div id="Private">
	<table width="100%">
		<tbody>
		<tr>
		<td colspan="2">
			<div class="menu1"><span id="picked_owner">
				<?php 
				if($show_id ==0) echo "<font class=\"redfont\">No picture and video available!</font><br/>"; 
				elseif($pic_avail==0) echo "<font class=\"redfont\">No picture available!</font><br/>"; 
				?>
				</span>
				<?php
					if($show_id!=0) {
						echo '<div id="Pictures">';
						if(isset($picture_group)) {
							$listcount=0;
							foreach ($picture_group as $key3 => $value3) {
									$count=0;
									$listcount++;
									if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
										foreach ($value3 as $key4 => $value4) {
											$count++;
											if($count>5) break;
											if($key4==0) echo "<input type=\"image\"  class=\"shakeimage\" onMouseover=\"init(this);rattleimage()\"  onMouseout=\"stoprattle(this);top.focus()\" src='ImgOnImgWithBorder?second_img=.$value4' alt='' onClick=\"stoprattle(this);top.focus();document.getElementById('SharedPicture').innerHTML='';document.getElementById('FriendID').value='$FriendID';refreshiframe('$key3');\"/><br/>";
											else echo "<img src='$value4' height='30'/>";
										}
										echo "<br/>$picture_UploadDate[$key3]<br/>";
										echo "$picture_description[$key3]<br/>";
									}
							}
						}		
						echo '</div>';
					}
				?>
			</div>
		</td>
		</tr>
		</tbody>
	</table>
</div>
<input type="hidden" name="FriendID" id="FriendID" value="<?php if(isset($FriendID)) echo $FriendID; else echo $this->session->userdata('id'); ?>">
<script type="text/javascript" >
var pagenum = 1;
var last = <?php echo $last; ?>;
var rows = <?php echo $rows; ?>;
var page_rows = <?php echo $page_rows; ?>;
var viewer_id =  "<?php if($this->session->userdata('id')) echo $this->session->userdata('id'); else echo "Public"; ?>";
var base_url="<?php echo base_url();?>";
</script>
<script src="<?php echo base_url();?>/scripts/jquery.js"></script>
<script src="<?php echo base_url();?>/scripts/picturegroup.js"></script>
</body>
</html>
	