<div id="backdrop" style="display:block;">
<noscript><font color="red">Your browser does not support JavaScript! You also required upgrading your browser to the latest version for the purpose of displaying picture and video.</font><br/></noscript> 
<?php 
	if($ErrorMessage!="") {
		echo "<div style='color:red'>$ErrorMessage</div>";
	}
//	elseif($greeting!="") {
		//echo "<div style='color:red'>$greeting</div>";
//	}
//	if(!isset( $_COOKIE["greeting"] )) setcookie( "greeting", "Welcome back ", time() + 60 * 60 * 24 * 365, "", "", false, true );
?>
	<div style="display:inline-block;vertical-align:top;">
		<iframe src="pictureMain/<?php if(isset($Temporary)) echo "?show_id=$Temporary&FriendID=Temporary"; ?>" width="611" height="1935" id="Main2" name="MyBlog" frameborder=0 SCROLLING=no>
		  <p>Your browser does not support iframes.</p>
		</iframe>
	</div>
	<div style="display:inline-block;vertical-align:top;">
		<font  color="red">Viewing</font> <font id="my_name"><?php if(isset($this->session->userdata('name'))) echo substr($this->session->userdata('name'),0,20)."'s"; elseif(isset($Temporary)) echo "Temporary"; else echo "Public"; ?></font> photo<br/>
		<img src="<?php if(isset($this->session->userdata('profile_picture'))) echo $this->session->userdata('profile_picture'); else echo "/images/profile/public.jpg"; ?>" id="ProfilPicture"  width='240' class="pointer" onMouseOver="Action_pic(1);" onMouseOut="Action_pic(0);" onClick="Action_pic(3);"/><br/>
		<div id="popups">Change Picture</div>
		<div>
			<iframe src="<?php if(isset($Temporary)) echo ''; else echo 'pictureGroup'; ?>" height="1935" width="240" id="frame1" frameborder=0 SCROLLING=no>
			  <p>Your browser does not support iframes.</p>
			</iframe>
			<div id="maincontent"></div>
		</div>
	</div>
	<div style="display:inline-block;vertical-align:top;">
		<input type="hidden" name="FriendID" id="FriendID" value='<?php if(isset($this->session->userdata('id'))) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary"; else echo "Public" ?>'>
		<input type="hidden" name="FriendPath" id="FriendPath" value='<?php if(isset($this->session->userdata('id'))) echo $this->session->userdata('owner_path'); else echo ""; ?>'>
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
		<div id="Friends" style="height:450px;">
		<?php
		if(!isset($Temporary)) {
		echo "<b>Public</b><br/>";
		foreach ($profile_picture as $key => $value) {
		$count++;
		if(isset($upload_id[$key])) $show_id = $upload_id[$key]; else $show_id = '';
		if($name[$key]=='Public') $ShowName="<font color='red'><b>Friends</b></font>"; else $ShowName=substr($name[$key],0,25);
		$longstring = <<<STRINGBEGIN
		<a href="" onClick="refreshiframe('$name[$key]','$FriendID[$key]','$value','$show_id','$key');return false;"><img src='$value' width='67'/></a><br/><font size='2'>$ShowName</font><br/>
STRINGBEGIN;
			if($count > $first_row && $count <= ($first_row+$page_rows)){
				echo $longstring;
			}
		}
		echo "</div><div>";
			echo '<font color="red" id="showtitil" size="2"><b>People You<br> May Know</b></font><br/>';
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
<div id='BlankMsg' style="display:none;"></div>
<?php if(isset($this->session->userdata('id'))) : ?>
<!--
<iframe src="chat" height="380" width="645" id="ChatFrame" frameborder=1 SCROLLING=no allowTransparency="false" style="position:fixed;bottom:0px;right:0px;z-index:3;background-color:#E6FFE6;display:block;">
  <p>Your browser does not support iframes.</p>
</iframe>
-->
<?php endif; ?>
<script src="/scripts/jquery.js"></script>
<script type="text/javascript" >var user_id="<?php if(isset($this->session->userdata('id'))) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary"; else echo "Public"; ?>";</script>
<?php if(isset($this->session->userdata('id'))) : ?>
<!--
<script src="/scripts/chat.js"></script>
-->
<?php endif; ?>
<script type="text/javascript" >
var OrgFriendID="<?php if(isset($this->session->userdata('id'))) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary"; else echo "Public"; ?>";
var pagenum = 1;
var last = <?php echo $last; ?>;
var rows = <?php echo $rows; ?>;
var page_rows = <?php echo $page_rows; ?>;
var admin = <?php if(isset($this->session->userdata('admin'))) echo $this->session->userdata('admin'); else echo "0"; ?>;
<?php if(isset($this->session->userdata('id'))) : ?>
document.getElementById('Home').style.display = "none";
<?php endif; ?>
if(admin==1) {
	document.getElementById('Setup').style.display = "none";
}
if(rows<=page_rows) {
	if(document.getElementById('first')) document.getElementById('first').style.display = "none";
	if(document.getElementById('previous')) document.getElementById('previous').style.display = "none";
	if(document.getElementById('next')) document.getElementById('next').style.display = "none";
	if(document.getElementById('last')) document.getElementById('last').style.display = "none";
}
var pagenum2 = 1;
var last2 = <?php echo $last2; ?>;
var rows2 = <?php echo $rows2; ?>;
var page_rows2 = <?php echo $page_rows2; ?>;
if(rows2<=page_rows2) {
	if(document.getElementById('first2')) document.getElementById('first2').style.display = "none";
	if(document.getElementById('previous2')) document.getElementById('previous2').style.display = "none";
	if(document.getElementById('next2')) document.getElementById('next2').style.display = "none";
	if(document.getElementById('last2')) document.getElementById('last2').style.display = "none";
}
if(rows2<=0) {if(document.getElementById('showtitil')) document.getElementById('showtitil').style.display = "none";}
else  {if(document.getElementById('showtitil')) document.getElementById('showtitil').style.display = "block";}
function SendRequest(url,ajaxobj)  
{
	$(document).ready(function() {
	   $("#"+ajaxobj).load(url);
	   $.ajaxSetup({ cache: false });
	});
}
function FriendList(require)  
{  
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
	

	var url = 'FriendList?user_id=<?php if(isset($this->session->userdata('id'))) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary"; else echo "Public"; ?>&pagenum='+pagenum;
	$(document).ready(function() {
	   $("#Friends").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}
function MayBeFriend(require)  
{  
	if(require=='first') pagenum2=1;
	else if(require=='previous') {
	  pagenum2 = pagenum2 - 1;
	} 
	else if(require=='next') {
	  pagenum2 = pagenum2 + 1;
	} 
	else if(require=='last') pagenum2=last2;
	 if(pagenum2<=1) { 
		pagenum2 = 1;
		document.getElementById('first2').src = "/images/first2.png";
		document.getElementById('previous2').src = "/images/previous2.png";
		document.getElementById('next2').src = "/images/next1.png";
		document.getElementById('last2').src = "/images/last.png";
	 }
	 else if(pagenum2>=last2) {
		pagenum2 = last2;
		document.getElementById('first2').src = "/images/first.png";
		document.getElementById('previous2').src = "/images/previous.png";
		document.getElementById('next2').src = "/images/next2.png";
		document.getElementById('last2').src = "/images/last2.png";
	 }
	 else {
		document.getElementById('first2').src = "/images/first.png";
		document.getElementById('previous2').src = "/images/previous.png";
		document.getElementById('next2').src = "/images/next1.png";
		document.getElementById('last2').src = "/images/last.png";
	 }
	 if(last2==1) {
		document.getElementById('first2').src = "/images/first2.png";
		document.getElementById('previous2').src = "/images/previous2.png";
		document.getElementById('next2').src = "/images/next2.png";
		document.getElementById('last2').src = "/images/last2.png";
	 }
	var url = 'MayBeFriend?user_id=<?php if(isset($this->session->userdata('id'))) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary"; else echo "Public"; ?>&pagenum='+pagenum2;
	$(document).ready(function() {
	   $("#MyBeFriends").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}
function refreshiframe(name,FriendID,picture,show_id,FriendPath)  
{  
	var ViewerID="<?php if(isset($this->session->userdata('id'))) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary";  else echo "Public"; ?>";
	document.getElementById('my_name').innerHTML = name.substring(0,20);
	document.getElementById('FriendID').value = FriendID;
	document.getElementById('FriendPath').value = FriendPath;
	document.getElementById('ProfilPicture').src = picture;
	if(OrgFriendID!=FriendID){
		var url = 'PictureVideoCheck.php?FriendID='+FriendID+'&ViewerID='+ViewerID;
		$.get(url, function(result) { 
			if(result==1) window.open( "PictureMain?show_id="+show_id+"&FriendID="+FriendID, "MyBlog");
		});	
	}
	window.parent.scroll(0,0);
}
function makefriend(name,FriendID)  
{ 
	var viewer_group = prompt("You want to add "+name+" as your friend?\nIf so please assign a group then click OK", "");
	var user_id="<?php if(isset($this->session->userdata('id'))) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary";  else echo "Public"; ?>";
	if (viewer_group!="" && viewer_group!=null){
		$.ajax({ 
		   type: "POST", 
		   url: "RequiredAsFriend",
		   data: "user_id="+user_id+"&FriendID="+FriendID+"&viewer_group="+viewer_group, 
		   success: function(msg){ 
			 alert( "Your require as a friend to "+name+"has been " + msg ); //Anything you want 
		   }, 
			error:function (xhr, ajaxOptions, thrownError){ 
						alert(xhr.status); 
						alert(thrownError); 
			}     	   
		 }); 	
		window.parent.scroll(0,0);
	}
	else {
		if(viewer_group!=null)	 alert("You must assign a group to your friend!\nPlease try again!"); 
	}

}
function Action_pic(disp)  
{
	var owner = "<?php if(isset($this->session->userdata('id'))) echo $this->session->userdata('id'); elseif(isset($Temporary)) echo "Temporary";  else echo "Public"; ?>";
	var curr = document.getElementById('FriendID').value;
	var FriendPath = document.getElementById('FriendPath').value;
//	alert(owner+'tst'+curr);
	if(disp==1 && curr!="Public" && curr!="Temporary") {
		document.getElementById('popups').style.display = 'block';
		if(owner==curr) document.getElementById('popups').innerHTML="Change Picture";
		else document.getElementById('popups').innerHTML="Profile Pictures";
	}
	else document.getElementById('popups').style.display = 'none';
	if(owner==curr && disp==3 && curr!="Public" && curr!="Temporary") window.open('ChangeProfile',target='_top');
	else if(owner!=curr && disp==3 && curr!='Public' && curr!='Temporary')  window.open('ProfilePicture?FriendPath='+FriendPath,target='_top');
}



function Set_OrgFriendID(CurrID)  
{
  OrgFriendID=CurrID;
}

</script>

<?php
if(isset($this->session->userdata('id'))) {
	$message = "";
	if(!$beforeShow2) {
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