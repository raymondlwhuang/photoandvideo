<div id="backdrop">
<font class="whitefont">SETTING UP FOR SHARING</font>
<?php
echo "<input type='image' src=\"/images/first2.png\" id='first' onClick=\"SharingList('first');\">";
echo " ";
echo "<input type='image' src=\"/images/previous2.png\" id='previous'  onClick=\"SharingList('previous');\">";
echo "<input type='image' src=\"/images/next1.png\" id='next' onClick=\"SharingList('next');\">";
echo " ";
echo "<input type='image' src=\"/images/last.png\" id='last'  onClick=\"SharingList('last');\">";
?>
<table name="mytable" id='Result'>
<?php
if(isset($optionViewer_id)) {
	$listcount=0;
	foreach ($optionViewer_id as $id => $friendeID) {
	$listcount++;
	if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
		echo "<tr>";
		echo "<td  style='border-style: solid;border-width: 1px;text-align:center;width:50px;'>
			<img src=\"$optionPicture[$id]\" width='50px' /> 
			</td>";
		echo "<td  style='font-size:15px;border-style: solid;border-width: 1px;text-align:center;'>
			  $FirstName[$id]	$LastName[$id]
			  </td>";
		echo "<td  style='border-style: solid;border-width: 1px;text-align:center;width:50px;'>";
		echo "<select onChange='Action(this.value,$friendeID);'>";
		if($optionSel[$id]==0) echo "<option value=\"0\" selected=\"selected\">No Sharing</option>";
		else  echo "<option value=\"0\" >No Sharing</option>";
		if($optionSel[$id]==1) echo "<option value=\"1\" selected=\"selected\">Ask to share</option>";
		else echo "<option value=\"1\">Ask to share</option>";
		if($optionSel[$id]==2) echo "<option value=\"2\" selected=\"selected\">Share allowed</option>";
		else echo "<option value=\"2\">Share allowed</option></select></td></tr>";
		}
	}
}
?>
</table>
</div>
<script type="text/javascript" >
var user_id = "<?php echo $this->session->userdata('id'); ?>";
var admin = <?php echo $this->session->userdata('admin'); ?>;
var pagenum = 1;
var last = <?php echo $last; ?>;
var rows = <?php echo $rows; ?>;
var page_rows = <?php echo $page_rows; ?>;
</script>
<script src="/scripts/setSharing.js"></script>
</body>
</html>
	