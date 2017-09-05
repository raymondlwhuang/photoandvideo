<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
<script language="JavaScript">
function checkPin( )	  
{
	var answer = confirm("Are you sure?");
	if (document.ValidPin.pin.value != document.ValidPin.confirm.value || answer==false) 
	{  
	   if(answer) document.getElementById("ErrorMessage").innerHTML = "Pin is not match!";
	   return false;
	}
}
</script>

<title>User Login</title>

<style type="text/css">
 #mydiv {
	position:fixed;
	top:10px;
	width:300px;
	height:450px;
	text-align: left;
	left: 50%;
	margin-left: -140px;	
	border-style:solid outset;
	border-width:7px;
	font-size:25px;
}
</style>

  </head>
<body>
<div id="mydiv">
	<input type="image" name="close" src="/images/close_icon.png" width="39" style="float:right;" onclick="window.open('HERecorder',target='_top');"></td>
	<center><b><font color="red" id="ErrorMessage" size="4">You must enter old pin correctly. <br/>It could lead to unexpected result</font></b>	</center>
	<form name="ValidPin" method="Post">
	<table border="0" style="font-size:25px;">
	<tbody>
	<tr>
	  <td align="right">
		<font face="GILLS SANS MT" color="141654">Old PID:</font>
		<input type="text" name="Oldpin" id="Oldpin" size="6" maxlength="6" value="" style="font-size:25px;"/><br/>
	  </td>
	</tr>
	<tr>
	  <td align="right">
		<font face="GILLS SANS MT" color="141654">New PID:</font>
		<input type="password" name="pin" id="pin" size="6" maxlength="6" value="" style="font-size:25px;"/><br/>
	  </td>
	</tr>
	<tr>
	  <td align="right">
		<font face="GILLS SANS MT" color="141654">Confirm PID:</font>
		<input type="password" name="confirm" id="confirm" size="6" maxlength="6" value="" style="font-size:25px;"/><br/>
	  </td>
	</tr>
	<tr>
	  <td align="center">
			<input type="checkbox" name="RememberPin" value="1" style="font-size:25px;">Remember My Pin<br/>
	  </td>
	</tr>
	<tr>
	  <td align="center">
			<input type="image" src="/images/done.png" name="submit" alt="Done" value="submit" width="80" onClick="return checkPin()">
	  </td>
	</tr>
	</tbody>
	</table>
	</form>
	<font color="red">you need to remeber PID in your mind. <br/>It is unrecoverable if you forget it!!</font>
</div>
</body>
</html>

