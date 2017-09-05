<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link type="text/css" rel="stylesheet" href="/css/MyResource.css" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Pick Friends</title>
<script src="/scripts/jquery.js"></script>
<script type="text/javascript">
function Action(name,viewer_id,action) {
	var user_id = "<?php echo $this->session->userdata('id'); ?>";
	var postname=encodeURIComponent(name);
	if(action==1){
		var friendnow = document.getElementById("All").checked?-1:-2;
	}
	else if(action==0 && document.getElementById(viewer_id)) friendnow = document.getElementById(viewer_id).checked?-1:-2;
	$.ajax({
		type: "GET", 
		url: "PickFriend2",
		data: { user_id: user_id, viewer_id: text,name:postname,friendnow:friendnow,action:action },
		dataType: "html",
		success: function(response) {document.getElementById("Result").innerHTML=response; },
		error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
	});	
	
/*	
	$(document).ready(function() {
	   $("#Result").load({url:url,data: "user_id="+user_id+"&viewer_id="+viewer_id+"&name="+postname+"&friendnow="+friendnow+"&action="+action});
	   $.ajaxSetup({ cache: false });
	});
*/	
}
</script>
</head>
<body style="font-size:60px;">
<center>
<table width="600" style="border-style: solid;border-color:#0000ff;border-width: 3px;">
	<tr>
    <td align="center" colspan="3" style="font-size:18px;font-weight:bold;border-style: solid;border-width: 1px;text-align:center;">PICK YOUR FRIENDS OR ADD FRIEND IN NEXT STEP</td>
	</tr>
	<tr>
	<td style='font-size:15px;width:100px;text-align:left;'>
	<input type="checkbox" name="All" id="All" value="All"  onChange="Action('1','1',1);">Select All
	</td>
	<td style='text-align:right;'>
	<input type="image" src="/images/home.png" name="Home" value="Home" height="60px" onClick="window.open('/',target='_top');">
	</td>
	<td style='text-align:right;' width="150px">
	<input type="image" src="/images/nextStep.png" height="60px" onClick="window.open('AddFriend',target='_top');">
	</td>
	</tr>
</table>
<table name="mytable" width="600" style="border-style: solid;border-color:#0000ff;border-width: 3px;" id="Result">
<?php
if(isset($optionViewer_id)) {
	foreach ($optionViewer_id as $id => $friendeID) {
	echo "<tr>
		<td  style='width:15px;'>";
		if($optionSel[$id]==-1)
			echo "<input type='checkbox' value='$id' id='$friendeID' checked='checked' onChange='Action(this.value,this.id,0);' />";
		else
			echo "<input type='checkbox' value='$id' id='$friendeID' onChange='Action(this.value,this.id,0);' />";
	echo "
		</td>
		<td  style='border-style: solid;border-width: 1px;text-align:center;width:50px;'>
		<img src=\"$optionPicture[$id]\" width='50px' /> 
		</td>";
	echo "	
		<td  style='font-size:15px;border-style: solid;border-width: 1px;text-align:center;'>
		$FirstName[$id]	$LastName[$id]
		</td></tr>";
	}
}
?>
</table>
</center>
</body>
</html>
	