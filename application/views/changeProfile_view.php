<center>
<table>
<tr>
	<td colspan="2">
		<img src="<?php echo $OrgPicture; ?>" width="700" id="profile" />
	</td>
</tr>
<tr>
<td colspan="2" align="center">

</td>
</tr>
<tr>
<td colspan="2" id="list_profile">
 <?php
 if (isset($oldprofile)){
	foreach($oldprofile as $key=>$picture) {
	$pos=strrpos($picture,"/");
	$OrgPicture=substr($picture,0,$pos)."/original".substr($picture,$pos);
	$link = $this->function_model->newencode("$key,$picture");
echo "<table style='display:inline-block;'>
<tr>
<td><img src='$picture' height='70' onClick='document.getElementById(\"profile\").src=\"$OrgPicture\";' class='pointer' /></td>
</tr>
<tr>
<td><a href='ChangeProfile.php?link=$link'  onclick='return confirm(\"Are you sure you want to delete?\");'><img src='/images/delete.png' alt='delete' width='25' /></a>&nbsp;<a href='ChangeProfile.php?link2=$link');'><font style='border: 5px grey double;background-color:#E4E4E4;color:black;position:relative;top:-10px;'>Set as Profile</font></a>
</td>
</tr>
</table>";
	}
}
?>
</td>
</tr>
<tr>
	<td>Upload Profile Picture(Max 2MB):</td>
	<td>
	<form name="uploader" id="uploader" action="" method="POST" enctype="multipart/form-data" > 
	<input id="infile" name="infile" type="file" accept="image/*" onChange="document.getElementById('loading').style.display='block';document.getElementById('uploader').submit();" size="30" style="font-size:20px;border-color:#5050FF;border-width: 3px;"/> 
	</form>
	</td>
</tr>
</table>
<?php 
if($this->session->userdata('message')) echo $this->session->userdata('message');
$this->session->unset_userdata('message');
?>
</center>
<div id='BlankMsg' style="display:none;"></div>
<img src="/images/upload.gif" id="loading" />

<script src="/scripts/jquery.js"></script>
<script type="text/javascript" >var user_id = "<?php echo $this->session->userdata('id'); ?>";</script>
<script type="text/javascript">
var admin = <?php echo $this->session->userdata('admin'); ?>;
if(admin==1) {
	document.getElementById('Setup').style.display = "none";
}
document.getElementById('loading').style.display='none';
</script>
</body>
</html>