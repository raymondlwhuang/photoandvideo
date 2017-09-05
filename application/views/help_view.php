<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>		
<meta charset="utf-8">
<title>Raymond Huang | Website Developer</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="author" content="Raymond Huang">
<?php echo link_tag('css/style.css'); ?>
</head>

<body>	
<div id="top-bar">		</div>
<div id="bg">
	<div id="wrap">
		<div id="frontpage-content"> 
			<div id="headline">
<?php
			if(!isset( $_COOKIE["greeting"] ))
			{
				echo '<h1><font color="red">Please enable Cookie and then refresh the page.</font></h1>';
			}
?>
<div id="menu">
<button class="tab" onClick="window.open('/Introduction',target='_top');">Introduction</button>
<button class="tab" onClick="window.open('/EditPicture',target='_top');">Image Editor</button>
<button class="tab" onClick="window.open('/',target='_top');">Picturs and Videos</button>
<button class="tab" onClick="window.open('/HERecorder',target='_top');">House Expense Recorder</button>
<button class="tab" onClick="window.open('/Resume',target='_top');">Resume</button>
<button class="tab" onClick="window.open('/Help',target='_top');">Help</button>
</div>			
				<noscript><h2><font color="red">Please enable JavaScript and then refresh the page.</font></h2></noscript>
				<h2>Will available soon. Please come check next time
				</h2>
			</div><!--end headline-->

	</div> <!--end wrap-->
</div>
</div>
</body>	
</html>