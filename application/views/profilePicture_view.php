<center>
<?php if(isset($name)) echo strtoupper($name); ?>
<table>
<tr>
<td>
<img src="<?php echo $OrgPicture[0] ?>" width="700" id="profile">
</td>
</tr>
<tr>
<td id="list_profile" align="center">
 <?php
 if (isset($oldprofile)){
	foreach($oldprofile as $key=>$picture) {
	   echo "<img src='$picture' height='100' onClick='document.getElementById(\"profile\").src=\"$OrgPicture[$key]\";' class='pointer'/>";
	   if(isset($pp_id)){
		   $link = newencode("$pp_id[$key],$picture,$FriendPath");
		   if($this->session->userdata('admin')==1) echo "<a href='ProfilePicture.php?link=$link'  onclick='return confirm(\"Are you sure you want to delete?\");'><img src='/images/delete.png' alt='delete' width='25' /></a>";
	   }
	}
}
?>
</td>
</tr>
</table>
</center>
<div id='BlankMsg' style="display:none;"></div>
<!--
<iframe src="chat.php" height="380" width="645" id="ChatFrame" frameborder=0 SCROLLING=no allowTransparency="false" style="position:fixed;bottom:0px;right:0px;z-index:3;background-color:#FFFFFF;display:block;">
  <p>Your browser does not support iframes.</p>
</iframe>
-->
<script src="/scripts/jquery.js"></script>
<script type="text/javascript" >var user_id = "<?php echo $this->session->userdata('id'); ?>";</script>
<!--
<script src="/scripts/chat.js"></script>
-->
</body>
</html>