<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Add Friend</title>
</head>
<body style="color:darkblue;">
<center>
<input type="image" src="/images/home.png" name="Home" value="Home" width="60" onClick="window.open('/',target='_top');">
<?php
if(isset($ViewerList) && count($ViewerList) != 0) {
	echo "<input type=\"image\" src=\"/images/nextStep.png\" height=\"60px\" onClick=\"window.open('SetGroup',target='_top');\">";
}
?>
<br/>

<font color=red id="ErrorMessage"><b></b></font>
<form name="FriendDel" method="Post">
<input type="hidden" name="delete_viewer_id" id="delete_viewer_id" value="">

<table>
	<tr>
    <td align="center" colspan="2" style="font-size:18px;font-weight:bold;">FRIEND(S) SET UP<br/><font color="red">(Please add friend and submit from below)</font></td>
	</tr>
	<tr>
    <td align="center" colspan="2" style="font-size:8px;">&nbsp;</td>
	</tr>
	<tr>
    <td align="right">
	First Name:
	</td>
    <td align="right">
	<input type="text" name="first_name" id="first_name" size="40"  value="">
	</td>
	</tr>
	<tr>
    <td align="right">
	Last Name:
	</td>
    <td align="right">
	<input type="text" name="last_name" id="last_name" size="40"  value="">
	</td>
	</tr>
	<tr>
    <td align="right">
	Friend's Email:
	</td>
    <td align="right" id="addemail">
	<input type="text" name="viewer_email" id="viewer_email" size="40"  value="">
	</td>
	<tr>
    <td align="right">
	</td>
    <td align="center">
	<input type='image' src='/images/submit.jpg' name="Save" value="Submit" width="100"  onClick="return formCheck(document.getElementById('viewer_email').value);">
	</td>
	</tr>
</table>    
<!--<input type ="button" name="Upload" value="Upload Photos/Videos"  disabled="disabled" > -->
<table border="1" width="370">
	<tr>
		<th colspan="3" align="center">Friend List(<?php if(isset($ViewerList)) echo count($ViewerList); else echo 0; ?>)
		</th>
	</tr>
	<tr>
		<th width="40">
		</th>
		<th align='left' width="50">
		
		</th>	
		<th align='left'>
		</th>	
	</tr>	
<?php
if(isset($ViewerList)){	
	foreach($ViewerList as $nt){
		$get=$this->user->Get($nt->viewer_email);
		if($get) {
			$profile_picture=$get->profile_picture;
		}
	echo "
	<tr>
		<td align='left'>
		<input type='image' src='/images/delete.png' name='Delete' value='$nt->viewer_id' onClick=\"document.getElementById('delete_viewer_id').value=this.value\">
		</td>
		<td align='left'>
		<img src=\"$profile_picture\" width='50px' /> 
		</td>
		<td align='left'>
		$nt->first_name	$nt->last_name
		</td></tr>";
	}
}
?>
</table>
</form>
</center>
</body>
</html>
<script language="JavaScript">
function formCheck(email)	  
{
	if (document.FriendDel.first_name.value == "") 
	{  
	   document.getElementById("ErrorMessage").innerHTML = "Please enter your friend's first name";
	   document.FriendDel.first_name.focus();
	   return false;
	}
	else if (document.FriendDel.viewer_email.value == "") 
	{  
	   document.getElementById("ErrorMessage").innerHTML = "Invalid e-mail address!Please try again.";
	   document.FriendDel.viewer_email.focus();
	   return false;
	}
	else {
		var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		if (!re.test(email)){
			document.getElementById("ErrorMessage").innerHTML = "Invalid e-mail address!Please try again.";
			document.FriendDel.viewer_email.focus();
			return re.test(email);			
		}
	}
	return true;
}
</script>	

	