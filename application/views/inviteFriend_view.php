<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>VIEWER GROUP SET UP</title>
<script type="text/javascript">
function Select_all(is_checked) {
	var allTags = document.getElementsByTagName("input");
	for (var i=0; i<allTags.length; i++) {
		if(allTags[i].type=="checkbox") allTags[i].checked=is_checked;
	}
}
</script>
</head>
<body style="font-size:30px;">
<center>
<table width="600" style="border-style: solid;border-color:#0000ff;border-width: 3px;">
	<tr>
    <td align="center" colspan="2" style="font-size:18px;font-weight:bold;border-style: solid;border-width: 1px;text-align:center;">INVITE FRIENDS</td>
	</tr>
	<tr>
	<td style='text-align:right;'>
	<input type="image" src="/images/home.png" name="Home" value="Home" height="60px" onClick="window.open('/',target='_top');">
	</td>
	<td style='text-align:right;' width="150px">
	<input type="image" src="/images/nextStep.png" height="60px" onClick="alert('Congratulation you have successfully finish your set up!\nClick OK to start your session');window.open('index.php',target='_top');">
	</td>
	</tr>
</table>
<form name="InviteFriends" method="Post">
<table border="0" width="600" id="mytable">
	<tr>
		<td style="border: 1px solid #38c;font-size:18px;" colspan="2">
		<input type="checkbox" name="All" id="All" value="All" onChange="Select_all(this.checked);">Select All
		</td>
		<td align="center" style="border: 1px solid #38c;font-size:20px;">Pick a friend to invite from below
		</td>
	</tr>
	<tr>
		<th width="40" style="border: 1px solid #38c;">
		</th>
		<th align='left' width="50" style="border: 1px solid #38c;">
		
		</th>	
		<th align='left' style="border: 1px solid #38c;">
		</th>	
	</tr>	
<?php	

foreach($ViewerList as $nt){
	$viewer_email1=newencode($nt->viewer_email);
	$get=$this->user->Get($nt->viewer_email);
	if($get) {
		$profile_picture=$get->profile_picture;
		$user_id1=$get->id;
	}
echo "
<tr>
    <td align='center' style=\"border: 1px solid #38c;\">
	<input type=\"checkbox\" name=\"$viewer_email1\" value=\"invited\" id='$user_id1'>
	</td>
    <td valign='left' style=\"border: 1px solid #38c;\">
	<img src=\"$profile_picture\" width='50px' /> 
	</td>
    <td align='left' style=\"border: 1px solid #38c;font-size:30px;\">
	$nt->first_name	$nt->last_name
	</td></tr>";
}
?>
<tr>
<td colspan="3" align="center" style="border: 1px solid #38c;">
<input type='image' src='/images/InviteFriends.jpg' name='Invite' width="120">
</td>
</tr>
</table>
</form>
</center>

</body>
</html>
	