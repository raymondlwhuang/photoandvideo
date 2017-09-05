<center>
<font style="font-size:18px;color:red;"><?php echo strtoupper(substr($picture_video,0,-1)) ?> UPLOADER</font><br/>
<div style="border:solid;color:green;width:900px;">
<font color='red' SIZE='12'><b>HINTS:</b></font>(to make your video supported <font color='red'><b>by most browser</b></font>)<br>
Upload 3 files for each video clip. One in <font color='red'><b>MP4</b></font> format, one in <font color='red'><b>WebM(vp8)</b></font> format and one in <font color='red'><b>Theora</b></font> format <br>
<a href=\"http://www.mirovideoconverter.com/download_win.html\" target=\"_blank\">Click here</a> to Download the converter if needed<br/><font color='red'><b>(to make sure your mp4 file works you must convert your mp4 video with this converter)</b></font><br/>
</div>

<form name="uploader" id="uploader" action="" method="POST" enctype="multipart/form-data" > 
<table>
	<tr>
		<td>Description:
		</td>
		<td align="left">
		<input type="text" name="description" id="description" placeholder="Enter description here before select files" value="<?php if($this->session->userdata('description')) echo $this->session->userdata('description'); echo ""; ?>" size="40" style="font-size:20px;border-color:#5050FF;border-width: 3px;">
		</td>
	</tr>
	<tr>
		<td>Share Video With:
		</td>
		<td align="left">
				<select name="viewer_group" id="viewer_group" style="font-size:20px;width:385px;border-color:#5050FF;border-width: 3px;">
				<option value='' <?php if($this->session->userdata('viewer_group') && $this->session->userdata('viewer_group') == '') echo "selected='selected'"; ?>>All Group</option>
				<?php
					foreach($optionGroup as $id=>$viewer_group2) {
						if($this->session->userdata('viewer_group') && $viewer_group2 == $this->session->userdata('viewer_group')) echo "<option value='$viewer_group2' selected='selected'>$viewer_group2</option>";
						else echo "<option value='$viewer_group2'>$viewer_group2</option>";
					}
				?>
				<option value='Public' <?php if($this->session->userdata('viewer_group') && $this->session->userdata('viewer_group') == 'Public') echo "selected='selected'"; ?>>Public</option>
				<option value='Temporary' <?php if(isset($viewer_group) && $viewer_group == 'Temporary') echo "selected='selected'"; ?>>Temporary</option>
				</select>
		</td>
	</tr>
	<tr>
		<td>Video Upload(Max <?php echo $MaxSize."MB"; ?>):</td>
		<td align="left">
		<input id="infile" name="infile" type="file" accept="video/*" size="40" onChange="send_mail();Action();" style="font-size:20px;border-color:#5050FF;border-width: 3px;"/> 
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
var admin = <?php echo $this->session->userdata('admin'); ?>;
if(admin==1) {
	document.getElementById('Setup').style.display = "none";
}
//document.getElementById('VUpload').style.display = "none";
var user_id=<?php echo $this->session->userdata('id'); ?>;
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
			error:function (xhr, ajaxOptions, thrownError){ 
						alert(xhr.status); 
						alert(thrownError); 
			}     	   
		 }); 	
	}

}
</script>
</body> 
</html>