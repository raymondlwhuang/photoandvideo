<div id="slider">
<table>
<tr>
<td align="right">
Contrast: <input type="checkbox" value="3" onchange="flag=1;PictureFilter();Changed('filter10','Contrast',0);" id="filter10"/></td><td>
	<table>
	<tbody>
	<tr>
	<td>
		<!-- Horizontal slider 1 (green) -->
		<div class="horizontal_track" id="horizontal_track_2">
			<div class="horizontal_slit" id="horizontal_slit_2">&nbsp;</div>
			<div class="horizontal_slider" id="horizontal_slider_2"  onmouseover="thisvalue=document.getElementById('Contrast').value;" onmousedown="slide(event, 'horizontal', 100, -255, 255, 0, 0, 'Contrast');" onmouseout="flag=1;DoPictureFilter('filter10','Contrast');">&nbsp;</div>
		</div>
	</td>
	<td>
		<!-- Value display 1 (green) -->
		<div class="display_holder" id="display_holder_2">
		<input type="text" value="0" size="3" class="value_display" id="Contrast" onchange="flag=1;PictureFilter();"  onfocus="blur(this);"/>
		</div>
	</td>
	</tr>
	</tbody>
	</table>
</td></tr><tr><td align="right">
Brightness: <input type="checkbox" value="2" onchange="flag=1;PictureFilter();Changed('filter11','Brightness',0);" id="filter11"/></td><td>
	<table>
	<tbody>
	<tr>
	<td>
		<!-- Horizontal slider 1 (green) -->
		<div class="horizontal_track" id="horizontal_track_3">
			<div class="horizontal_slit" id="horizontal_slit_3">&nbsp;</div>
			<div class="horizontal_slider" id="horizontal_slider_3"  onmouseover="thisvalue=document.getElementById('Brightness').value;" onmousedown="slide(event, 'horizontal', 100, -255, 255, 0, 0, 'Brightness');" onmouseout="flag=1;DoPictureFilter('filter11','Brightness');">&nbsp;</div>
		</div>
	</td>
	<td>
		<!-- Value display 1 (green) -->
		<div class="display_holder" id="display_holder_3">
		<input type="text" value="0" size="3" class="value_display" id="Brightness" onchange="flag=1;PictureFilter();"  onfocus="blur(this);" />
		</div>
	</td>
	</tr>
	</tbody>
	</table>
</td></tr>
</table>
</div>
<div id="negGray">
	<table width="100%">
	<tr>
	<td align="right">
	Negate: <input type="checkbox" value="0" onchange="flag=1;PictureFilter();" id="filter3"/>
	</td>
	<td align="right">
	Grayscale: <input type="checkbox" value="1" onchange="flag=1;PictureFilter();" id="filter5"/></td>
	<td align="right">
	<input type="image" src="/images/edit.png" style="border: 5px grey double;" onclick="window.open('EditPicture?thisPicture=.'+document.getElementById('thisPicture').value,target='_top');" /> </td>
	</tr>
	</table>
</div>
