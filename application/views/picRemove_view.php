<center>
<font style="font-size:18px;color:red;">PICTURE MAINTENANCE</font><br/>
<?php
$encode=$this->function_model->newencode($upload_id);
echo "<a href='PRemove'><img src='ImgOnImgWithBorder?second_img=$picture' alt='Go Back'/></a>";
echo "<a href='PicRemove?folder=$encode'  onclick=\"return confirm('Are you sure you want to delete this folder?');\"><img src='/images/delete.png' alt='delete' width='25' /></a>";
echo "<br/>$pic_date<br/>";
echo "$pic_desc<br/><br/>";
	
foreach($result as $nt){
	$link = $this->function_model->newencode("$nt->id,$nt->name,$upload_id,$pic_date,$pic_desc,$picture");
	echo "<div style='display:inline-block;'><img src='$nt->name' width='134' />";
	echo "<a href='PicRemove?link=$link'  onclick='return confirm(\"Are you sure you want to delete?\");'><img src='/images/delete.png' alt='delete' width='25' /></a>&nbsp;</div>";
}

?>
</center>
<div id='BlankMsg' style="display:none;"></div>
<!--
<iframe src="chat.php" height="380" width="645" id="ChatFrame" frameborder=0 SCROLLING=no allowTransparency="false" style="position:fixed;bottom:0px;right:0px;z-index:3;background-color:#FFFFFF;display:block;">
  <p>Your browser does not support iframes.</p>
</iframe>
-->
<!--
<script src="/scripts/jquery.js"></script>
<script type="text/javascript" >var user_id = "<?php echo $GV_id; ?>";</script>
<script src="/scripts/chat.js"></script>
-->
</body>
</html>
	