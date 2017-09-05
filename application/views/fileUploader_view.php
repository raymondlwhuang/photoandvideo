<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html> 
<head> 
	<title>PICTURE UPLOADER</title> 
</head> 
<body> 
<center>
<font style="font-size:18px;color:red;">PICTURE UPLOADER</font><br/>
<?php
if($bname =="Internet Explorer" || $bname =="Apple Safari") {
echo <<<_END
<div style="border:solid;color:green;width:900px;">
<font color='red' SIZE='12'><b>HINTS:</b></font>(Your browser don't support multiple Picture Upload!<br/>Please consider using 'Google Chrome,FireFox,Aurora,Opera,Avant instead!)<br>
Picture will put as the same group base on 'Date->Group->Description' <br/>
Recommend maximum <font color='red'><b>10 pictures</b></font>/day/group. <br/>
Picture will automatically resize to less then <font color='red'><b>2MB</b></font> after upload.<br/> 
To increase your upload speed. Please reduce your picture size previous to upload! </div>
_END;
}
else {
echo <<<_END
<div style="border:solid;color:green;width:900px;">
<font color='red' SIZE='12'><b>HINTS:</b></font><br>
Picture will put as the same group base on 'Date->Group->Description' <br/>
Recommend maximum <font color='red'><b>10 pictures</b></font>/day/group.<br/>
Picture will automatically resize to less then <font color='red'><b>2MB</b></font> after upload.<br/> 
To increase your upload speed. Please reduce your picture size previous to upload!</div>
_END;
}
?>
<br/>
<form name="uploader" id="uploader" action="" method="POST" enctype="multipart/form-data" > 
<table>
	<tr>
		<td>Description:
		</td>
		<td align="left">
		<input type="text" name="description" id="description" placeholder="Enter description here before select files" value="<?php if(isset($description)) echo "$description"; echo ""; ?>" size="40" style="font-size:20px;border-color:#5050FF;border-width: 3px;">
		</td>
	</tr>
	<tr>
		<td>Share Picture With:
		</td>
		<td align="left">
				<select name="viewer_group" id="viewer_group" style="font-size:20px;width:385px;border-color:#5050FF;border-width: 3px;">
				<option value='' <?php if(isset($viewer_group) && $viewer_group == '') echo "selected='selected'"; ?>>All Group</option>
				<?php
					foreach($optionGroup as $id=>$viewer_group2) {
						if(isset($viewer_group) && $viewer_group2 == $viewer_group) echo "<option value='$viewer_group2' selected='selected'>$viewer_group2</option>";
						else echo "<option value='$viewer_group2'>$viewer_group2</option>";
					}
				?>
				<option value='Public' <?php if(isset($viewer_group) && $viewer_group == 'Public') echo "selected='selected'"; ?>>Public</option>
				<option value='Temporary' <?php if(isset($viewer_group) && $viewer_group == 'Temporary') echo "selected='selected'"; ?>>Temporary</option>
				</select>
		</td>
	</tr>
	<tr>
		<td><?php if($bname =="Internet Explorer" || $bname =="Apple Safari") echo "Picture Upload(Max ".$MaxSize."MB):"; else echo "Mulitiple pictures Upload(Max ".$MaxSize."MB each):"?></td>
		<td align="left">
		<input id="infile" name="infile[]" type="file" accept="image/*" onChange="send_mail();Action();" <?php if($bname =="Internet Explorer" || $bname =="Apple Safari") echo ""; else echo 'multiple="true"' ?> size="40" style="font-size:20px;border-color:#5050FF;border-width: 3px;"/> 
		</td>
	</tr>
</table>
</form>
<div id='BlankMsg' style="display:none;"></div>
<img src="/images/upload.gif" id="loading" />
<!--
<iframe src="chat.php" height="380" width="645" id="ChatFrame" frameborder=0 SCROLLING=no allowTransparency="false" style="position:fixed;bottom:0px;right:0px;z-index:3;background-color:#FFFFFF;display:block;">
  <p>Your browser does not support iframes.</p>
</iframe>
-->
<?php 
if($this->session->userdata('message')) echo $this->session->userdata('message');
$this->session->unset_userdata('message');
?>
</center>	
<script src="/scripts/jquery.js"></script>
<script type="text/javascript" >var user_id = "<?php echo $this->session->userdata('id'); ?>";</script>
<!--
<script src="/scripts/chat.js"></script>	
-->
<script type="text/javascript">
//document.getElementById('PUpload').style.display = "none";
var user_id=<?php echo $this->session->userdata('id'); ?>;
var admin = <?php echo $this->session->userdata('admin'); ?>;
if(admin==1) {
	document.getElementById('Setup').style.display = "none";
}
function Action() {
	document.getElementById('loading').style.display='block';
	document.getElementById('uploader').submit();
}
document.getElementById('loading').style.display='none';
function send_mail()  
{ 
	var viewer_group=document.getElementById('viewer_group').value;
	if (viewer_group!="Public" && viewer_group!="Temporary"){
		$.ajax({ 
		   type: "POST", 
		   url: "SendEMail.php",
		   data: "user_id="+user_id+"&viewer_group="+viewer_group, 
		 }); 	
	}

}
</script>
</body> 
</html>