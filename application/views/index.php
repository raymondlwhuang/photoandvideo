<div id="backdrop">
<noscript><font class='redtext'>Your browser does not support JavaScript! You also required upgrading your browser to the latest version for the purpose of displaying picture and video.</font><br/></noscript> 
<?php 
	if($this->session->userdata('message')) {
		echo "<div class='redtext'>".$this->session->userdata('message')."</div>";
		$this->session->unset_userdata('message');
	}
?>
	<div id="PictureMain">
		<iframe src="/PictureMain/<?php if(isset($Temporary)) echo "?show_id=$Temporary&FriendID=Temporary"; ?>" width="611" height="950" id="Main2" name="MyBlog" frameborder=0 SCROLLING=no>
		  <p>Your browser does not support iframes.</p>
		</iframe>
	</div>
	<div id="PictureGroup">
		<font class='redtext'>Viewing</font> <font id="my_name"><?php if($this->session->userdata('name')) echo substr($this->session->userdata('name'),0,20)."'s"; elseif(isset($Temporary)) echo "Temporary"; else echo "Public"; ?> photo</font><br/>
		<img src="<?php if($this->session->userdata('profile_picture')) echo $this->session->userdata('profile_picture'); else echo "/images/profile/public.jpg"; ?>" id="ProfilPicture"  width='200' class="pointer" onMouseOver="Action_pic(1);" onMouseOut="Action_pic(0);" onClick="Action_pic(3);"/><br/>
		<div id="popups">Change Picture</div>
		<div>
			<iframe src="<?php if(isset($Temporary)) echo ''; else echo '/PictureGroup'; ?>" width="200" height="950" id="frame1"  name="Group" frameborder=0 SCROLLING=no>
			  <p>Your browser does not support iframes.</p>
			</iframe>
			<div id="maincontent"></div>
		</div>
	</div>
	<div id="Friendlist">
		<input type="hidden" name="FriendID" id="FriendID" value='<?php if($this->session->userdata('id')) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary"; else echo "Public" ?>'>
		<input type="hidden" name="FriendPath" id="FriendPath" value='<?php if($this->session->userdata('owner_path')) echo $this->session->userdata('owner_path'); else echo ""; ?>'>
		<input type="hidden" name="show_id" id="show_id" value="<?php if(isset($show_id)) echo $show_id; else echo''; ?>">
		
		<?php
		if(!isset($Temporary)) {
			echo "<input type='image' src=\"/images/first2.png\" id='first' onClick=\"FriendList('first');\">";
			echo " ";
			echo "<input type='image' src=\"/images/previous2.png\" id='previous'  onClick=\"FriendList('previous');\">";
			echo "<input type='image' src=\"/images/next1.png\" id='next' onClick=\"FriendList('next');\">";
			echo " ";
			echo "<input type='image' src=\"/images/last.png\" id='last'  onClick=\"FriendList('last');\"><br/>";
			$count=0;
		}
		?>
		<div id="Friends">
		<?php
		if(!isset($Temporary)) {
		echo "<b class=\"whitefont\">Public</b><br/>";
		if(isset($profile_picture)){
			foreach ($profile_picture as $key => $value) {
			$count++;
			if(isset($upload_id[$key])) $show_id = $upload_id[$key]; else $show_id = '';
			if($name[$key]=='Public') {
				if($this->session->userdata('id')) $ShowName="<font class='whitefont'>Friends></font>";
				else $ShowName="";
			}
			else $ShowName="<font class='whitefont'>".substr($name[$key],0,25)."</font>";
				if($count > $first_row && $count <= ($first_row+$page_rows)){
					echo "<a href='' onClick=\"refreshiframe('$name[$key]','$FriendID[$key]','$value','$show_id','$key');return false;\"><img src='$value' width='67'/></a><br/><font size='2'>$ShowName</font><br/>";
				}
			}
		}
		echo "</div><div>";
			echo '<font class="redtext" id="showtitil" size="2"><b>People You<br> May Know</b></font><br/>';
			echo "<input type='image' src=\"/images/first2.png\" id='first2' onClick=\"MayBeFriend('first');\">";
			echo " ";
			echo "<input type='image' src=\"/images/previous2.png\" id='previous2'  onClick=\"MayBeFriend('previous');\">";
			echo "<input type='image' src=\"/images/next1.png\" id='next2' onClick=\"MayBeFriend('next');\">";
			echo " ";
			echo "<input type='image' src=\"/images/last.png\" id='last2'  onClick=\"MayBeFriend('last');\"><br/>";
			$count2=0;
			if(isset($profile_picture2)) {
				echo "<div  id=\"MyBeFriends\">";
				foreach ($profile_picture2 as $key2 => $value2) {
				$count2++;
				$ShowName=substr($name[$key2],0,25);
					if($count2 > $first_row2 && $count2 <= ($first_row2+$page_rows2)){
						echo "<a href=\"\" onClick=\"SendRequest ('LastActivity?user_id=".$this->session->userdata('id')."','maincontent');makefriend('$name[$key2]','$FriendID2[$key2]');return false;\"><img src='$value2' width='67'/></a><br/><font size='2'>$ShowName</font><br/>";
					}
				}
				echo "</div>";
			}
			}
			?>		
		</div>
	</div>
	
</div>
<div id='BlankMsg'></div>
<script type="text/javascript" >
var user_id="<?php if($this->session->userdata('id')) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary"; else echo "Public"; ?>";
var OrgFriendID="<?php if($this->session->userdata('id')) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary"; else echo "Public"; ?>";
var pagenum = 1;
var last = <?php echo $last; ?>;
var rows = <?php echo $rows; ?>;
var page_rows = <?php echo $page_rows; ?>;
var admin = <?php if($this->session->userdata('admin')) echo $this->session->userdata('admin'); else echo "0"; ?>;
var pagenum2 = 1;
var last2 = <?php echo $last2; ?>;
var rows2 = <?php echo $rows2; ?>;
var page_rows2 = <?php echo $page_rows2; ?>;
</script>
<script src="/scripts/home.js"></script>
<?php
if($this->session->userdata('id')) {
	$message = "";
	if(!isset($beforeShow2)) {
	echo "
	<script type='text/javascript' >
	function confirmation() {
		var answer = confirm('You have not do the set up yet!\\nWould you like to do the set up?');
		if (answer){
			window.location = 'PickFriend';
		}
	}
	window.onload = confirmation;
	</script>
	";
	}
}
?>
</body>
</html>