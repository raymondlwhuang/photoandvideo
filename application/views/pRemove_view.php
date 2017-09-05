<h1>
<?php
if ($this->session->userdata('admin')==1 && $this->session->userdata('FriendID') && $this->session->userdata('FriendID')!=$this->session->userdata('id') && $this->session->userdata('FriendID')!="Public") {
	if($notmypic)
		echo "$FriendName's picture";
	else
		echo "$FriendName's picture<input type=\"checkbox\" value=\"\" id=\"other_pic\" onchange=\"other_pic();\">";
}
echo "</h1><div><img src=\"/images/first2.png\" id='first' onClick=\"PRemoveList('first');\">";
echo " ";
echo "<img src=\"/images/previous2.png\" id='previous'  onClick=\"PRemoveList('previous');\">";
echo "<img src=\"/images/next1.png\" id='next' onClick=\"PRemoveList('next');\">";
echo " ";
echo "<img src=\"/images/last.png\" id='last'  onClick=\"PRemoveList('last');\"><br/>";
?>
</div>
<div id="Private">
	<div class="menu1">
		<div id="Pictures">
		<?php
			if(isset($picture_group)) {
				$listcount=0;
				foreach ($picture_group as $key3 => $value3) {
					echo "<div class='folder'>";
					$count=0;
					$listcount++;
					if($listcount > $first_row && $listcount <= ($first_row+$page_rows)){
						foreach ($value3 as $key4 => $value4) {
							$infor=$this->function_model->newencode("$key3,$value4,$picture_UploadDate[$key3],$picture_description[$key3],$FriendID");
							if($key4==0) echo "<input type=\"image\" width='100' src='$value4' alt='' onClick=\"Action('LastActivity?user_id=".$this->session->userdata('id')."','BlankMsg');refreshiframe('$infor');\"/>";
						}
						$encode=$this->function_model->newencode($key3);
						echo "<a href='PRemove?link=$encode'  onclick=\"return confirm('Are you sure you want to delete this folder?');\"><img src='/images/delete.png' alt='Delete' title='Delete' /></a>";
						echo "<br/>$picture_UploadDate[$key3]<br/>";
						echo "$picture_description[$key3]<br/>";
					}
					echo "</div>";
				}
			}
		?>
	</div>
</div>
<div id='BlankMsg'></div>
<script src="/scripts/jquery.js"></script>
<script type="text/javascript" >var user_id = "<?php echo $this->session->userdata('id'); ?>";</script>
<script type="text/javascript">
var admin = <?php echo $this->session->userdata('admin'); ?>;
if(admin==1) {
	document.getElementById('Setup').style.display = "none";
}
var pagenum = 1;
var last = <?php echo $last; ?>;
var rows = <?php echo $rows; ?>;
var page_rows = <?php echo $page_rows; ?>;
if(rows<=page_rows) {
	document.getElementById('first').style.display = "none";
	document.getElementById('previous').style.display = "none";
	document.getElementById('next').style.display = "none";
	document.getElementById('last').style.display = "none";
}
function PRemoveList(require)  
{  
	var owner_path =  "<?php echo $this->session->userdata('owner_path') ?>";

	if(require=='first') pagenum=1;
	else if(require=='previous') {
	  pagenum = pagenum - 1;
	} 
	else if(require=='next') {
	  pagenum = pagenum + 1;
	} 
	else if(require=='last') pagenum=last;
	if(pagenum > 1 && pagenum < last) {
		document.getElementById('first').src = "/images/first.png";
		document.getElementById('previous').src = "/images/previous.png";
		document.getElementById('next').src = "/images/next1.png";
		document.getElementById('last').src = "/images/last.png";
	}
	else if(pagenum<=1) {
		pagenum = 1;
		document.getElementById('first').src = "/images/first2.png";
		document.getElementById('previous').src = "/images/previous2.png";
		document.getElementById('next').src = "/images/next1.png";
		document.getElementById('last').src = "/images/last.png";
	}
	 else if(pagenum>=last) {
		pagenum = last;
		document.getElementById('first').src = "/images/first.png";
		document.getElementById('previous').src = "/images/previous.png";
		document.getElementById('next').src = "/images/next2.png";
		document.getElementById('last').src = "/images/last2.png";
	 }

	var url = 'PRemoveList?user_id='+user_id+'&owner_path='+owner_path+'&pagenum='+pagenum;;
	$(document).ready(function() {
	   $("#Pictures").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}

function refreshiframe(infor)  
{
	window.open( "PicRemove?infor=" + infor, "_top");
}
function Action(url,affect_id) {
	var id = "#"+affect_id;
	$(document).ready(function() {
	   $(id).load(url);
	   $.ajaxSetup({ cache: false });
	});
	window.parent.scroll(0,0);
}
function other_pic() {
	if(document.getElementById('other_pic')) {
		if(document.getElementById('other_pic').checked) var FriendID= <?php echo $FriendID; ?>;
		else  var FriendID= <?php echo $this->session->userdata('id'); ?>;
	}
	else  var FriendID= <?php echo $this->session->userdata('id'); ?>;
	var id = "#Pictures";
	url="PRemove2?FriendID="+FriendID;
	$(document).ready(function() {
	   $(id).load(url);
	   $.ajaxSetup({ cache: false });
	});
}
</script>

</body>
</html>
	