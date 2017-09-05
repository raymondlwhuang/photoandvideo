<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>User Setup</title>
<style type="text/css">
html, body {
   height: 100%;
   margin: 0;
   padding: 0;
 }

img#bg {
   position:fixed;
   top:0;
   left:0;
   width:100%;
   height:100%;
 } 
body {
background-image:url('/images/Desert.jpg');
}
input.invalid {
	background-color: #FF9;
	border: 2px red inset;
}

label.invalid {
	color: red;
	font-weight: bold;
}
p {
	color:#0000A0;
	text-align:right;
	font-family:"Monotype Corsiva";
	margin:0;
}
p.invalid {
	color: red;
}
</style>

<style type="text/css">
html, body {
   background-image:url('/images/background.png');
 }
 #mydiv {
	position:fixed;
	top:150px;
	width:380px;
	height:220px;
	background-color: #FFFFFF;
	text-align: left;
	left: 50%;
	margin-left: -190px;	
	border-style:solid outset;
	border-width:7px;
	background-color:#8FC4FF;
}
</style>
<script language="javascript">var emailfield = "email", reqdfield = "reqd", passwordConfirm = "passwordConfirm",invalid = "invalid",MessageTag = "",colorLabel=true;</script>
<script type="text/javascript" src="/scripts/UserSetup.js"></script>
</head>
<body>
<div id="mydiv">
<form name="ValidateUser" action="" method="Post">
<center>
<font color="red" id="ErrorMessage"><?php if (isset($ErrorMessage)){ echo '**'.htmlspecialchars($ErrorMessage).'**   '; } else ''; ?></font>
<br>
<br>
  <table border="0">
    <tr>
      <td><label for="first_name">First Name:</label></td>
      <td><input type="text"  name="first_name" size="30"  maxlength="50" id="first_name" class="invalid reqd" value="<?php if (isset($first_name)){ echo $first_name; } else ''; ?>" /></td>
    </tr>
    <tr>
      <td><label for="last_name">Last Name:</label></td>
      <td><input type="text"  name="last_name" size="30"  maxlength="50" id="last_name"  class="reqd" value="<?php if (isset($last_name)){ echo $last_name; } else ''; ?>" /></td>
    </tr>
    <tr>
      <td><label for="email_address">Your Email:</label></td>
      <td><input type="text"  name="email_address" size="30"  maxlength="50" id="email_address" class="email" value="<?php if (isset($email_address)){ echo $email_address; } else ''; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td><label for="password">Password:</label></td>
      <td><input type="password"  name="password" size="30"  maxlength="15" id="password" class="reqd" onBlur="return CheckPassword(this.value);" value="" /></td>
    </tr>
    <tr>
      <td><label for="passwordConfirm">Confirm Your Password:</label></td>
      <td><input type="password"  name="passwordConfirm" size="30"  maxlength="15" id="passwordConfirm" class="passwordConfirm" value="" /></td>
    </tr>
    <tr>
      <td></td>
      <td>
	  <input type="Submit" name="Submit" value="Submit" >
<!--	  <input type="button" name="Cancel" value="Back"   onclick="window.open( '/', '_top');return false;"> -->
	  </td>
    </tr>
  </table>
  </form>
</center>
</div>
</body>
</html>