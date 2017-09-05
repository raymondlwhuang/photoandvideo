<?php
$result = mysql_query($GetDisplay) or die(mysql_error());

while($row = mysql_fetch_array($result))
{
 $searchID=$row['id'];
 $owner_email=$row['owner_email'];
 $owner_path = $row['owner_path'];
 $viewer_group =  $row['viewer_group'];
 $is_active =  $row['is_active'];
 $viewer_email =  $row['viewer_email'];
} 
?>
