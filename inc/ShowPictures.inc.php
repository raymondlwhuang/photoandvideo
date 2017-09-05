<?php
		  $countimg++;
		  $content ='<div style="display: none; opacity: 1;height: 530px;" id="banner'."$countimg".'">';
		  $content = "$content"."<img src='$row3[name]' height='60%' usemap='#mail-info' border='0'><br />
		  Your Comments: <input type='text' name='comment' size='48' value =''><br />
		  <textarea rows='8' cols='50' readonly='readonly'>
 At W3Schools you will find all the Web-building tutorials you need, from basic HTML to advanced XML, SQL, ASP, and PHP. 
</textarea>";
		  $content = "$content".'</div>';
		  echo "$content";
?>