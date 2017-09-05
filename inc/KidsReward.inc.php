<?php
$result = mysql_query($GetDisplay) or die(mysql_error());
while($row = mysql_fetch_array($result)){ 
$searchID=$row['id'];
$signature=$row['signature'];
$rewardid = $row['rewardid']; 
$amount =  $row['amount']; 
$description =  $row['description']; 
$date =  $row['date']; 
}
?>
