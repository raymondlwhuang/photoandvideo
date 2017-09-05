<?php
$result=mysql_query("SELECT CURRENT_TIMESTAMP");
while($row = mysql_fetch_array($result))
{
 $now = $row[0];
}
