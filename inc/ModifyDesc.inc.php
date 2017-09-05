<?php
$result = mysql_query($GetDisplay) or die(mysql_error());
while($row = mysql_fetch_array($result)){ 
$searchID=$row['id'];
$description=$row['description'];
} 
?>
