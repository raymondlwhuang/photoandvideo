<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Make your Selection</title>
<style type="text/css" media="screen">
a {
text-decoration: none;
}
.shakeimage{
position:relative
}
</style>	

</head>
<body style="color:darkblue;">
<center>
<font style="font-size:18px;" id="SharedPicture"><?php// if(isset($_SESSION["show_id"]) && $_SESSION["show_id"]="SharedPicture" && $FriendID!="Public") echo "Viewing shared photos"; ?></font><br/>
<?php
if(isset($GV_id) && $FriendID==$GV_id) {
	$count=0;
	$showed=false;
	while($row5 = mysql_fetch_array($queryShare))
	{
		$count++;
		if($count>5) break;
		if($row5['is_video']==0) {
			if($showed==false) {
				echo "<input type=\"image\"  class=\"shakeimage\" onMouseover=\"init(this);rattleimage()\"  onMouseout=\"stoprattle(this);top.focus()\" src='ImgOnImgWithBorder.php?second_img=$row5[pv_name]' alt='' onClick=\"stoprattle(this);top.focus();document.getElementById('SharedPicture').innerHTML='Viewing shared photos';t = setInterval(blink, 500);Action('/PHP/LastActivity.php?user_id=$GV_id','maincontent');document.getElementById('FriendID').value='SharedPicture';refreshiframe('SharedPicture');\"/><br/>";
				$showed=true;
			}	
			else echo "<img src='$row5[pv_name]' height='30'/>";
		}
//		echo "$row5[pv_name]";
	}
	if($showed==false && $shardCount!=0) {
				echo "<input type=\"image\"  class=\"shakeimage\" onMouseover=\"init(this);rattleimage()\"  onMouseout=\"stoprattle(this);top.focus()\" src='/images/Folder.png' alt='' onClick=\"stoprattle(this);top.focus();document.getElementById('SharedPicture').innerHTML='Viewing shared photos';t = setInterval(blink, 500);Action('/PHP/LastActivity.php?user_id=$GV_id','maincontent');document.getElementById('FriendID').value='SharedPicture';refreshiframe('SharedPicture');\"/><br/>";
	}	
	if(mysql_num_rows($queryShare)!=0) echo "<br/>Photos shared to you from friends<br/>";
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
<div style="display:block;" id="Private">
	<table width="100%">
		<tbody>
		<tr>
		<td colspan="2">
			<div class="menu1" style="text-align:center;"><span id="picked_owner">
				<?php 
				if($show_id ==0) echo "<font color='red'>No picture and video available!</font><br/>"; 
				elseif($pic_avail==0) echo "<font color='red'>No picture available!</font><br/>"; 
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
											if($key4==0) echo "<input type=\"image\"  class=\"shakeimage\" onMouseover=\"init(this);rattleimage()\"  onMouseout=\"stoprattle(this);top.focus()\" src='ImgOnImgWithBorder.php?second_img=$value4' alt='' onClick=\"stoprattle(this);top.focus();document.getElementById('SharedPicture').innerHTML='';document.getElementById('FriendID').value='$FriendID';refreshiframe('$key3');\"/><br/>";
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
<input type="hidden" name="FriendID" id="FriendID" value="<?php if(isset($FriendID)) echo $FriendID; else echo $GV_id; ?>">
<script src="/scripts/jquery.js"></script>
<script type="text/javascript" >
//configure shake degree (where larger # equals greater shake)
var rector=3
var state = true; 
///////DONE EDITTING///////////
var stopit=0 
var a=1

function init(which){
stopit=0
shake=which
shake.style.left=0
shake.style.top=0
}
function rattleimage(){
if ((!document.all&&!document.getElementById)||stopit==1)
return
if (a==1){
shake.style.top=parseInt(shake.style.top)+rector+"px"
}
else if (a==2){
shake.style.left=parseInt(shake.style.left)+rector+"px"
}
else if (a==3){
shake.style.top=parseInt(shake.style.top)-rector+"px"
}
else{
shake.style.left=parseInt(shake.style.left)-rector+"px"
}
if (a<4)
a++
else
a=1
setTimeout("rattleimage()",50)
}
function stoprattle(which){
stopit=1
which.style.left=0
which.style.top=0
}

var pagenum = 1;
var last = <?php echo $last; ?>;
var rows = <?php echo $rows; ?>;
var page_rows = <?php echo $page_rows; ?>;
if(rows<=page_rows) {
	document.getElementById('first').style.display = "none";
	document.getElementById('previous').style.display = "none";
	document.getElementById('next').style.display = "none";
	document.getElementById('last').style.display = "none";
}
function PictureList(require,FriendID)  
{  
	var viewer_id =  "<?php if(isset($GV_id)) echo $GV_id; else echo "Public"; ?>";

	if(require=='first') pagenum=1;
	else if(require=='previous') {
	  pagenum = pagenum - 1;
	} 
	else if(require=='next') {
	  pagenum = pagenum + 1;
	} 
	else if(require=='last') pagenum=last;
	if(pagenum > 1 && pagenum < last) {
		document.getElementById('first').src = "/images/first.png";
		document.getElementById('previous').src = "/images/previous.png";
		document.getElementById('next').src = "/images/next1.png";
		document.getElementById('last').src = "/images/last.png";
	}
	else if(pagenum<=1) {
		pagenum = 1;
		document.getElementById('first').src = "/images/first2.png";
		document.getElementById('previous').src = "/images/previous2.png";
		document.getElementById('next').src = "/images/next1.png";
		document.getElementById('last').src = "/images/last.png";
	}
	 else if(pagenum>=last) {
		pagenum = last;
		document.getElementById('first').src = "/images/first.png";
		document.getElementById('previous').src = "/images/previous.png";
		document.getElementById('next').src = "/images/next2.png";
		document.getElementById('last').src = "/images/last2.png";
	 }

	var url = 'PictureList.php?FriendID='+FriendID+'&viewer_id='+viewer_id+'&pagenum='+pagenum;;
	$(document).ready(function() {
	   $("#Pictures").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}

function refreshiframe(upload_id)  
{ 
	window.open( "PictureMain.php?FriendID="+document.getElementById("FriendID").value + "&show_id=" + upload_id, "MyBlog");
}
function Action(url,affect_id) {
	var id = "#"+affect_id;
	$(document).ready(function() {
	   $(id).load(url);
	   $.ajaxSetup({ cache: false });
	});
	window.parent.scroll(0,0);
}
function blink() {
	if(document.getElementById('SharedPicture').innerHTML !="") { 
	  state = !state; 
	  if (state) 
		$('#SharedPicture').fadeIn('slow'); 
	  else 
		$('#SharedPicture').fadeOut('slow'); 
	}	
	else clearInterval( t );
} 
 
var t = setInterval(blink, 500); 
</script>

</body>
</html>
	