<div style="display:inline-block;vertical-align:top;">
<table border="0" width="400" id="mytable">
	<tr>
		<td style="border: 1px solid #38c;font-size:20px;" colspan="2">
		<input type="checkbox" name="All" id="All" value="All" onChange="Select_all();">Select All
		</td>
		<td align="center" style="border: 1px solid #38c;font-size:20px;">Pick a friend
		</td>
	</tr>
	<tr>
		<th width="40" style="border: 1px solid #38c;">
		</th>
		<th align='left' width="50" style="border: 1px solid #38c;">
		
		</th>	
		<th align='left' style="border: 1px solid #38c;">
		</th>	
	</tr>	
<?php	
foreach($ViewerList as $nt){
	$get=$this->user->Get($nt->viewer_email);
	if($get) {
		$profile_picture=$get->profile_picture;
		$user_id1=$get->id;
	}
echo "
<tr>
    <td align='center' style=\"border: 1px solid #38c;\">
	<input type=\"image\" src=\"/images/click-here.gif\" width='50px' alt=\"Add to group\" name=\"$user_id1\" value=\"$nt->id\" id='$user_id1' onClick=\"Action(this.value);\">
	<font size='2' color='DarkBlue'><b>To Add</b></font></td>
    <td align='left' style=\"border: 1px solid #38c;\">
	<img src=\"$profile_picture\" width='50px' /> 
	</td>
    <td align='left' style=\"border: 1px solid #38c;\">
	$nt->first_name	$nt->last_name
	</td></tr>";
}
?>
</table>
</div>
<div  style="display:inline-block;">
<form name="FriendDel" method="Post">
<input type="hidden" name="owner_email"  value="<?php if ($this->session->userdata('email_address')){ echo $this->session->userdata('email_address'); } else ''; ?>">
<input type="hidden" name="delete_id" id="delete_id" value="">
<table border="0" width="450">
	<tr>
		<th colspan="4" align="center" style="border: 1px solid #38c;">Group List
		</th>
	</tr>
	<tr>
		<th style="border: 1px solid #38c;text-align:right;color:green;" colspan="3">Asign to group
		</th>	
		<th style="border: 1px solid #38c;text-align:center;">Name
		</th>	
	</tr>	
<?php
if(!$ViewerList2) {
	echo "
	<tr>
		<td style='font-size:25px;border-style: solid;border-width: 1px;text-align:center;' colspan='4'>
		Currently your friend list in the left only allow to view pictures/videos that you upload to the All Group area. <br/>Please create your viewer group now.
		</td>
	</tr>";
}
else {
	$viewer_group='';
	$color="brown";
	foreach($ViewerList2 as $nt2){
		if($viewer_group!=$nt2->viewer_group) {
			$viewer_group=$nt2->viewer_group;
			if($color=="green") $color="brown";
			else  $color="green";
		}
		$get=$this->user->get($nt2->viewer_email);
		if($get) {
			$profile_picture=$get->profile_picture;
		}
	echo "
	<tr>
		<td width=\"40\" style=\"border: 1px solid #38c;\">
		<input type='image' src='/images/delete.png' name='Delete' value='$nt2->id' onClick=\"document.getElementById('delete_id').value=this.value\">
		</td>
		<td width=\"50\" style=\"border: 1px solid #38c;\">
		<img src=\"$profile_picture\" width='50px' /> 
		</td>
		<td align='left' style=\"border: 1px solid #38c;color:$color;\">
		$nt2->viewer_group 
		</td>
		<td align='left' style=\"border: 1px solid #38c;\">
		$nt2->first_name	$nt2->last_name
		</td></tr>";
	}
}
?>
</table>
</form>
</div>
</div>	