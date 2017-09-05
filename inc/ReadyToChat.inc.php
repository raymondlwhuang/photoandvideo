<?php
include("../config.php");
include("../inc/GlobalVar.inc.php");
$ReadyToChat = 0;
$query="SELECT * FROM view_permission where owner_email = '$GV_email_address' group by viewer_email";  // query string stored in a variable
$result=mysql_query($query);          // query executed 
echo mysql_error();              // if any error is there that will be printed to the screen 
while($row2 = mysql_fetch_array($result))
{
	$queryFriends="SELECT * FROM user where  email_address = '$row2[viewer_email]' and is_active = 3 LIMIT 1";
	$friend=mysql_query($queryFriends);          // query executed 
	echo mysql_error();
	while($row3 = mysql_fetch_array($friend))
	{
  	  $ReadyToChat++;
	} 
}
setcookie( "ReadyToChat", $ReadyToChat, time() + 10, "", "", false, true );
