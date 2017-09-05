<div id="share" >
	<div id="Result">
		<div id="navigator">
			<input type='image' src="/images/first2.png" id='Sharefirst' onClick="SharingList('first');">
			<input type='image' src="/images/previous2.png" id='Shareprevious' onClick="SharingList('previous');">
			<input type='image' src="/images/next1.png" id='Sharenext' onClick="SharingList('next');">
			<input type='image' src="/images/last.png" id='Sharelast'  onClick="SharingList('last');">
		</div>
		<div id="sel">
			<input type="checkbox" name="All" id="All" value="All"  onChange="Share(this.id,1);">Share to All
		</div>
		<div>
			<table id="ShareList">
			<?php
			if(isset($optionViewer_id)) {
				$listcount=0;
				foreach ($optionViewer_id as $id => $friendeID) {
				$listcount++;
				if($listcount > $Sharefirst_row && $listcount <= ($Sharefirst_row+$Sharepage_rows)){
					$name=substr($FirstName[$id]." ".$LastName[$id],0,15);
					echo "<tr>
						<td width='15'>";
					echo "<input type='checkbox' value='$id' id='$friendeID' $shareFlag[$id] onChange='Share(this.id,0);' />";
					echo "
						</td>
						<td  width='50'>
						<img src=\"$optionPicture[$id]\" width='40px' /> 
						</td>";
					echo "	
						<td>
						$name
						</td></tr>";
					}
				}
			}
			?>
			</table>
		</div>
	</div>
</div>
<div id="picture">
	<div id="loadimg">
	<img src="<?php echo $this->session->userdata('thisPicture'); ?>" width="640" id="profile">
	</div>
	<form name='MyForm' action="" enctype='application/x-www-form-urlencoded' method='post' onsubmit="if(document.getElementById('comment').value=='') return false;">
	<?php
			foreach($_GET as $name => $value) {
				if($name!="pv_id" && $name!="comment" && $name!="thisPicture")  echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>";
			}
	?>
	<input type="hidden" name="thisPicture" id="thisPicture" value="<?php echo $this->session->userdata('thisPicture'); ?>"/>
	<input type="hidden" name="pv_id" id="pv_id" value="<?php echo $this->session->userdata('this_id'); ?>"/>
	Comment: <input type='text' name='comment'  id='comment' value=''/>
	</form>
	<div id="ComMain">
		<div id="ComNav">
		<img src="/images/first_up2.png" id='first' onClick="CommentList('first',<?php echo $upload_id;?>);"/>
		<img src="/images/previous_up2.png" id='previous'  onClick="CommentList('previous',<?php echo $upload_id;?>);"/>
		<img src="/images/next_up.png" id='next' onClick="CommentList('next',$upload_id);"/>
		<img src="/images/last_up.png" id='last'  onClick="CommentList('last',$upload_id);"/>
		</div>
		<div id="comments">
		<?php
			if(isset($comments)) {
				$listcount=0;
				foreach ($comments as $key => $comment) {
					$listcount++;
					if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
						echo "<div><div id='result_profile'><img src=\"$result_profile_picture[$key]\"  class=\"noborder\" height=\"38\"/></div>$comment</div>";
					}
				}
			}
		?>	
		</div>
	</div>
</div>
<div id="filterSel">
<?php $this->load->view("FilterSel"); ?>
<?php
 if (isset($pictures)){
	foreach($pictures as $key=>$picture) {
	   echo "<img src='$picture' width='80' onClick='Action(\"$OrgPicture[$key]\",$pv_id[$key],$upload_id);PictureFilter();' class='pointer'/>";
	}
}
$this->session->unset_userdata('this_id');
$this->session->unset_userdata('thisPicture');
?>
</div>
<div id='BlankMsg'></div>
<script type="text/javascript" src="/scripts/jquery.js"></script>
<script type="text/javascript" src="/scripts/ImgFilter.js"></script>
<script type="text/javascript">
var user_id="<?php if($this->session->userdata('id')) echo $this->session->userdata('id'); else echo "Public"; ?>";
var shareallowed="<?php echo $shareallowed; ?>";
var Sharepagenum = 1;
var Sharelast = <?php echo $Sharelast; ?>;
var Sharerows = <?php echo $Sharerows; ?>;
var Sharepage_rows = <?php echo $Sharepage_rows; ?>;
var pagenum = 1;
var last = <?php echo $last; ?>;
var Comcount = <?php echo $Comcount; ?>;
var page_rows = <?php echo $page_rows; ?>;
var viewer_id = "<?php if($this->session->userdata('id')) echo $this->session->userdata('id'); else echo "Public"; ?>";
</script>
<script type="text/javascript" src="/scripts/commentPicture.js"></script>
</body>
</html>