<div id="phpImageEditor">
	<div class="tabs">
	<div class="tab" id="croptab" onclick="ShowOpt(0);" ><p>Crop Image</p></div>
	<div class="tab" id="effecttab" onclick="ShowOpt(1);" ><p>Effect</p></div>
	<div class="tab" id="texttab" onclick="ShowOpt(2);" ><p>Text</p></div>
	<div class="block">Upload File</div>
	<div class="upload">
		<form name="uploader" id="uploader" action="" method="POST" enctype="multipart/form-data" > 
			<input id="infile" name="infile" type="file" accept="image/*" onChange="document.getElementById('loading').style.display='block';document.getElementById('uploader').submit();" size="30"/>
		</form>
	</div>
	<input type="hidden" id="cur_picture" value="<?php echo $thisPicture; ?>" size="200">
	<input type="hidden" id="org_picture" value="<?php echo $org_picture; ?>" size="200">

	<div id="actionContainer">
		<input type="button" value="Reset" id="Resetbtn" onclick="window.open('EditPicture?thisPicture='+document.getElementById('org_picture').value,target='_top');" >
		<div id="cropopt">
			<table>
				<tbody>
				<tr>
					<td>
						<table>
							<tbody><tr>
								<td>
									<div class="field">
										<label for="cropwidth">Crop width</label>
										<input class="input-number" name="cropwidth" id="cropwidth" value="0" type="text">
									</div>
								</td>
								<td>
									<div class="field">
										<label for="cropheight">Crop height</label>
										<input class="input-number" name="cropheight" id="cropheight" value="0" type="text">
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="field">
										<label for="cropx">Startposition x</label>
										<input class="input-number" name="cropx" id="cropx" value="0" type="text">
									</div>
								</td>
								<td>
									<div class="field">
										<label for="cropy">Startposition y</label>
										<input class="input-number" name="cropy" id="cropy" value="0" type="text">
									</div>
								</td>
							</tr>
						</tbody>
						</table>
						
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
						<div class="help" id="crophelp">
						<div class="help-header" id="crophelpheader">Instructions</div>
						<div class="help-content" id="crophelpcontent">Drag and drop to create a crop area on the<br> image.Or use the fields to do the cropping.</div>
						</div>
						<input type="button" value="Crop" id="cropbtn" onclick="cropImage();">
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php $this->load->view("FilterSel2"); ?>
	</div>
	<input name="panel" id="panel" value="2" type="hidden">
	</div>
</div>

<div id="editimage" onclick="point_it(event)">
<img id="image" alt="" src="<?php echo $thisPicture."?time_x=".time(); ?>" />
</div>
<div id='BlankMsg'></div>
<script type="text/javascript">
var user_id = "<?php echo $tempname; ?>";
var CropPic="<?php echo $CropPic; ?>";
var firstPic="<?php echo $firstPic; ?>";
var toNewPic="<?php echo $toNewPic; ?>";
var toNewPic2="<?php echo $toNewPic2; ?>";
var toNewPic3="<?php echo $toNewPic3; ?>";
var toNewPic4="<?php echo $toNewPic4; ?>";
var beforAddText="<?php echo $beforAddText; ?>";
var cur_picture=document.getElementById('cur_picture').value;
var org_picture=document.getElementById('org_picture').value;
if(cur_picture !=org_picture) document.getElementById('crophelpcontent').innerHTML = "Drag and drop to create a crop area on the<br> image.Or use the fields to do the cropping.<br/>TEXT had been removed for croping purpose";
</script>
<script type="text/javascript" src="/scripts/ImgEdit.js"></script>
</body>
</html>