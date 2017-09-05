<div id="effect">
	<div class="filter">
	<table>
	<tr>
	<td align="right">
	Smooth: <input type="checkbox" value="10" onchange="flag=1;PictureFilter();Changed('filter8','Smoothvalue',10);" id="filter8"></td><td>
		<table>
		<tbody>
		<tr>
		<td>
			<!-- Horizontal slider 1 (green) -->
			<div class="horizontal_track" id="horizontal_track_1">
				<div class="horizontal_slit" id="horizontal_slit_1">&nbsp;</div>
				<div class="horizontal_slider" id="horizontal_slider_1"  onmouseover="thisvalue=document.getElementById('Smoothvalue').value;" onmousedown="slide(event, 'horizontal', 100, -20, 20, 0, 0, 'Smoothvalue');" onmouseout="flag=1;DoPictureFilter('filter8','Smoothvalue');">&nbsp;</div>
			</div>
		</td>
		<td>
			<!-- Value display 1 (green) -->
			<div class="display_holder" id="display_holder_1">
			<input type="text" value="10" size="3" class="value_display" id="Smoothvalue" onchange="flag=1;PictureFilter();"  onfocus="blur(this);" />
			</div>
		</td>
		</tr>
		</tbody>
		</table>
	</td></tr><tr><td align="right">
	Contrast: <input type="checkbox" value="3" onchange="flag=1;PictureFilter();Changed('filter10','Contrast',0);" id="filter10"></td><td>
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
			<input type="text" value="0" size="3" class="value_display" id="Contrast" onchange="flag=1;PictureFilter();"  onfocus="blur(this);" />
			</div>
		</td>
		</tr>
		</tbody>
		</table>
	</td></tr><tr><td align="right">
	Brightness: <input type="checkbox" value="2" onchange="flag=1;PictureFilter();Changed('filter11','Brightness',0);" id="filter11"></td><td>
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
	</td></tr><tr><td align="right">
	Pixelate: <input type="checkbox" value="11" onchange="flag=1;PictureFilter();Changed('filter12','Pixelate',0);" id="filter12"></td><td>
		<table>
		<tbody>
		<tr>
		<td>
			<!-- Horizontal slider 1 (green) -->
			<div class="horizontal_track" id="horizontal_track_4">
				<div class="horizontal_slit" id="horizontal_slit_4">&nbsp;</div>
				<div class="horizontal_slider" id="horizontal_slider_4"  onmouseover="thisvalue=document.getElementById('Pixelate').value;" onmousedown="slide(event, 'horizontal', 100, 0, 20, 0, 0, 'Pixelate');" onmouseout="flag=1;DoPictureFilter('filter12','Pixelate');">&nbsp;</div>
			</div>
		</td>
		<td>
			<!-- Value display 1 (green) -->
			<div class="display_holder" id="display_holder_4">
			<input type="text" value="0" size="3" class="value_display" id="Pixelate" onchange="flag=1;PictureFilter();"  onfocus="blur(this);"/>
			</div>
		</td>
		</tr>
		</tbody>
		</table>
	</td></tr><tr><td align="right">
	Colorize: <input type="checkbox" value="4" onchange="flag=1;PictureFilter();Changed('filter9','red',0);" id="filter9">R:</td><td>
		<table>
		<tbody>
		<tr>
		<td>
			<!-- Horizontal slider 1 (green) -->
			<div class="horizontal_track" id="horizontal_track_5">
				<div class="horizontal_slit" id="horizontal_slit_5">&nbsp;</div>
				<div class="horizontal_slider" id="horizontal_slider_5"  onmouseover="thisvalue=document.getElementById('red').value;" onmousedown="slide(event, 'horizontal', 100, 0, 255, 0, 0, 'red');" onmouseout="flag=1;DoPictureFilter('filter9','red');">&nbsp;</div>
			</div>
		</td>
		<td>
			<!-- Value display 1 (green) -->
			<div class="display_holder" id="display_holder_5">
			<input type="text" value="0" size="1" class="value_display" id="red" onchange="flag=1;PictureFilter();" />
			</div>
		</td>
		</tr>
		</tbody>
		</table>
	</td></tr><tr><td align="right">
	G:</td><td>
		<table>
		<tbody>
		<tr>
		<td>
			<!-- Horizontal slider 1 (green) -->
			<div class="horizontal_track" id="horizontal_track_6">
				<div class="horizontal_slit" id="horizontal_slit_6">&nbsp;</div>
				<div class="horizontal_slider" id="horizontal_slider_6"  onmouseover="thisvalue=document.getElementById('green').value;" onmousedown="slide(event, 'horizontal', 100, 0, 255, 0, 0, 'green');" onmouseout="flag=1;DoPictureFilter('filter9','green');">&nbsp;</div>
			</div>
		</td>
		<td>
			<!-- Value display 1 (green) -->
			<div class="display_holder" id="display_holder_6">
			<input type="text" value="0" size="1" class="value_display" id="green" onchange="flag=1;PictureFilter();" />
			</div>
		</td>
		</tr>
		</tbody>
		</table>
	</td></tr><tr><td align="right">
	B:</td><td>
		<table>
		<tbody>
		<tr>
		<td>
			<!-- Horizontal slider 1 (green) -->
			<div class="horizontal_track" id="horizontal_track_7">
				<div class="horizontal_slit" id="horizontal_slit_7">&nbsp;</div>
				<div class="horizontal_slider" id="horizontal_slider_7"  onmouseover="thisvalue=document.getElementById('blue').value;" onmousedown="slide(event, 'horizontal', 100, 0, 255, 0, 0, 'blue');" onmouseout="flag=1;DoPictureFilter('filter9','blue');">&nbsp;</div>
			</div>
		</td>
		<td>
			<!-- Value display 1 (green) -->
			<div class="display_holder" id="display_holder_7">
			<input type="text" value="0" size="1" class="value_display" id="blue" onchange="flag=1;PictureFilter();"/>
			</div>
		</td>
		</tr>
		</tbody>
		</table>
	</td></tr>
	</table>
	</div>
	<div class="filter">
		<table width="100%">
		<tr>
		<td align="right">
		Emboss: <input type="checkbox" value="6" onchange="flag=1;PictureFilter();" id="filter1"></td><td align="right">
		Selective Blur: <input type="checkbox" value="8" onchange="flag=1;PictureFilter();" id="filter2"></td></tr><tr><td align="right">
		Negate: <input type="checkbox" value="0" onchange="flag=1;PictureFilter();" id="filter3"></td><td align="right">
		Gaussian Blur: <input type="checkbox" value="7" onchange="flag=1;PictureFilter();" id="filter4"></td></tr><tr><td align="right">
		Grayscale: <input type="checkbox" value="1" onchange="flag=1;PictureFilter();" id="filter5"></td><td align="right">
		Mean Removal: <input type="checkbox" value="9" onchange="flag=1;PictureFilter();" id="filter6"></td></tr><tr><td align="right">
		Edgedetect: <input type="checkbox" value="5" onchange="flag=1;PictureFilter();" id="filter7"></td><td align="right">
		</td></tr>
		</table>
		</div>
		<div class="selDiv">
			Resize: <input type="checkbox" value="10" onchange="flag=2;PictureFilter();Changed('doResize','resize',100);" id="doResize">
			<div class="selDiv">
				<table>
				<tbody>
				<tr>
				<td>
					<!-- Horizontal slider 1 (green) -->
					<div class="horizontal_track" id="horizontal_track_8">
						<div class="horizontal_slit" id="horizontal_slit_8">&nbsp;</div>
						<div class="horizontal_slider" id="horizontal_slider_8" onmouseover="thisvalue=document.getElementById('resize').value;" onmousedown="slide(event, 'horizontal', 100, 10, 150, 0, 0, 'resize');" onmouseout="flag=2;DoPictureFilter('doResize','resize');">&nbsp;</div>
					</div>
				</td>
				<td>
					<!-- Value display 1 (green) -->
					<div class="display_holder" id="display_holder_8">
					<input type="text" value="100" size="3" class="value_display" id="resize" onchange="flag=2;PictureFilter();"  onfocus="blur(this);" />
					</div>
				</td>
				</tr>
				</tbody>
				</table>
			</div><br/>						
			<div class="selDiv">
			Rotation Angle: <input type="checkbox" value="10" onchange="flag=2;PictureFilter();Changed('doRotation','degrees',0);" id="doRotation"></div>
			<div class="selDiv">
				<table>
				<tbody>
				<tr>
				<td>
					<!-- Horizontal slider 1 (green) -->
					<div class="horizontal_track" id="horizontal_track_9">
						<div class="horizontal_slit" id="horizontal_slit_9">&nbsp;</div>
						<div class="horizontal_slider" id="horizontal_slider_9" onmouseover="thisvalue=document.getElementById('degrees').value;" onmousedown="slide(event, 'horizontal', 100, 0, 360, 0, 0, 'degrees');" onmouseout="flag=2;DoPictureFilter('doRotation','degrees');">&nbsp;</div>
					</div>
				</td>
				<td>
					<!-- Value display 1 (green) -->
					<div class="display_holder" id="display_holder_9">
					<input type="text" value="0" size="3" class="value_display" id="degrees" onchange="flag=2;PictureFilter();"  onfocus="blur(this);"/>
					</div>
				</td>
				</tr>
				</tbody>
				</table>
			</div><br/>
			<div class="selDiv">
			Corner Radius: <input type="checkbox" value="10" onchange="if(this.checked==false) document.getElementById('corner').style.display='none'; flag=2;PictureFilter();Changed('doRoundCorner','radius',0);" id="doRoundCorner"></div>
			<div class="selDiv">
				<table>
				<tbody>
				<tr>
				<td>
					<!-- Horizontal slider 1 (green) -->
					<div class="horizontal_track" id="horizontal_track_10">
						<div class="horizontal_slit" id="horizontal_slit_10">&nbsp;</div>
						<div class="horizontal_slider" id="horizontal_slider_10" onmouseover="thisvalue=document.getElementById('radius').value;" onmousedown="slide(event, 'horizontal', 100, 0, 360, 0, 0, 'radius');" onmouseout="flag=2;DoPictureFilter('doRoundCorner','radius');">&nbsp;</div>
					</div>
				</td>
				<td>
					<!-- Value display 1 (green) -->
					<div class="display_holder" id="display_holder_10">
					<input type="text" value="0" size="3" class="value_display" id="radius" onchange="flag=2;PictureFilter();"  onfocus="blur(this);" />
					</div>
				</td>
				</tr>
				</tbody>
				</table>
			</div><br/>
			<div id="corner" class="selDiv">
					top left?:	<input type="checkbox" value="yes" id="topleft" onchange="flag=2;PictureFilter();" checked="checked" >
					top right:	<input type="checkbox" value="yes" id="topright" onchange="flag=2;PictureFilter();" checked="checked"  >
					bottom left?:	<input type="checkbox" value="yes" id="bottomleft" onchange="flag=2;PictureFilter();" checked="checked"  >
					bottom right?:<input type="checkbox" value="yes" id="bottomright" onchange="flag=2;PictureFilter();" checked="checked"  >
			</div>
		</div>
	</div>
	<div class="selDivhide" id="addtext">
	  <div class="selDiv">
		<div>
			<div class="selDiv">
				<div class="selDiv">
				Startposition x <p id="cursorX"></p></br>
				<input type="text" name="positionx" id="positionx" value="0" size="15" onchange="AddText();" />
				</div>
				<div class="selDiv">
				Startposition y <p id="cursorY"></p></br>
				<input type="text" name="positiony"  id="positiony" value="0" size="15" onchange="AddText();" />
				</div>
			</div>
		</div>
		<div>
			<div>
				<div class="selDiv">
					Font <input type="checkbox" value="10" onchange="Changed('dofont','font',24);AddText();" id="dofont">&nbsp;
				</div>
				<div class="selDiv">
					<select id='font' onchange='AddText();'>
						<?php
						$files = $this->function_model->getFiles("./fonts/");
						foreach ($files as $file) {
							if($file['name']=="timesbi.ttf")  echo "<option value='/fonts/$file[name]' selected='selected' >$file[name]</option>";
							else echo "<option value='/fonts/$file[name]'>$file[name]</option>";
						 }
						?>	
					</select>
				</div>
			</div>
			<div>
				<div  class="selDiv">
				Font Size <input type="checkbox" value="10" onchange="Changed('dofontsize','fontsize',24);AddText();" id="dofontsize">&nbsp;
				</div>
				<div  class="selDiv">
						<div  class="selDiv">
							<!-- Horizontal slider 1 (green) -->
							<div class="horizontal_track" id="horizontal_track_11">
								<div class="horizontal_slit" id="horizontal_slit_11">&nbsp;</div>
								<div class="horizontal_slider" id="horizontal_slider_11" onmouseover="thisvalue=document.getElementById('fontsize').value;" onmousedown="slide(event, 'horizontal', 100, 4, 120, 0, 0, 'fontsize');" onmouseout="flag=3;DoPictureFilter('dofontsize','fontsize');">&nbsp;</div>
							</div>
						</div>
						<div  class="selDiv">
							<!-- Value display 1 (green) -->
							<div class="display_holder" id="display_holder_11">
							<input type="text" value="24" size="3" class="value_display" id="fontsize" onfocus="blur(this);" >
							</div>
						</div>
				</div>
			</div>
		</div>
		<div>
			<div  class="selDiv">
				Font Color <input type="checkbox" value="1" onchange="Changed('dofontcolor','FontR',0);Changed('dofontcolor','FontG',0);Changed('dofontcolor','FontB',0);AddText();" id="dofontcolor">R
			</div>
			<div  class="selDiv">
				<div  class="selDiv">
					<!-- Horizontal slider 1 (green) -->
					<div class="horizontal_track" id="horizontal_track_13">
						<div class="horizontal_slit" id="horizontal_slit_13">&nbsp;</div>
						<div class="horizontal_slider" id="horizontal_slider_13" onmouseover="thisvalue=document.getElementById('FontR').value;" onmousedown="slide(event, 'horizontal', 100, 0, 255, 0, 0, 'FontR');" onmouseout="flag=3;DoPictureFilter('dofontcolor','FontR');">&nbsp;</div>
					</div>
				</div>
				<div  class="selDiv">
					<!-- Value display 1 (green) -->
					<div class="display_holder" id="display_holder_13">
					<input type="text" value="0" size="3" class="value_display" id="FontR" onchange="AddText();"/>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div  class="selDiv">G</div>
			<div  class="selDiv">
				<!-- Horizontal slider 1 (green) -->
				<div class="horizontal_track" id="horizontal_track_14">
					<div class="horizontal_slit" id="horizontal_slit_14">&nbsp;</div>
					<div class="horizontal_slider" id="horizontal_slider_14" onmouseover="thisvalue=document.getElementById('FontG').value;" onmousedown="slide(event, 'horizontal', 100, 0, 255, 0, 0, 'FontG');" onmouseout="flag=3;DoPictureFilter('dofontcolor','FontG');">&nbsp;</div>
				</div>
			</div>
			<div  class="selDiv">
				<!-- Value display 1 (green) -->
				<div class="display_holder" id="display_holder_14">
				<input type="text" value="0" size="3" class="value_display" id="FontG"  onchange="AddText();"/>
				</div>
			</div>
		</div>
		<div>
			<div class="selDiv">B</div>
			<div  class="selDiv">
				<!-- Horizontal slider 1 (green) -->
				<div class="horizontal_track" id="horizontal_track_15">
					<div class="horizontal_slit" id="horizontal_slit_15">&nbsp;</div>
					<div class="horizontal_slider" id="horizontal_slider_15" onmouseover="thisvalue=document.getElementById('FontB').value;" onmousedown="slide(event, 'horizontal', 100, 0, 255, 0, 0, 'FontB');" onmouseout="flag=3;DoPictureFilter('dofontcolor','FontB');">&nbsp;</div>
				</div>
			</div>
			<div  class="selDiv">
				<!-- Value display 1 (green) -->
				<div class="display_holder" id="display_holder_15">
				<input type="text" value="0" size="3" class="value_display" id="FontB" onchange="AddText();"/>
				</div>
			</div>
		</div>		
		<div>
			<div  class="selDiv">
			Text Rotation <input type="checkbox" value="10" onchange="Changed('dotextrotate','textrotate',0);AddText();" id="dotextrotate">
			</div>
			<div  class="selDiv">
					<div  class="selDiv">
						<!-- Horizontal slider 1 (green) -->
						<div class="horizontal_track" id="horizontal_track_12">
							<div class="horizontal_slit" id="horizontal_slit_12">&nbsp;</div>
							<div class="horizontal_slider" id="horizontal_slider_12" onmouseover="thisvalue=document.getElementById('textrotate').value;" onmousedown="slide(event, 'horizontal', 100, 0, 360, 0, 0, 'textrotate');" onmouseout="flag=3;DoPictureFilter('dotextrotate','textrotate');">&nbsp;</div>
						</div>
					</div>
					<div  class="selDiv">
						<!-- Value display 1 (green) -->
						<div class="display_holder" id="display_holder_12">
						<input type="text" value="0" size="3" class="value_display" id="textrotate" onfocus="blur(this);"/>
						</div>
					</div>
			</div>
		</div>
	  </div>
	  <div  class="selDiv">
		<div>
		Enter text for the picture if needed
		</div>
		<div>
		<textarea cols="30" rows="6" id="text_to_display" onfocus="AddText();" onkeyup="AddText();"></textarea>
		</div>
		<div class="help">
			<div class="help-header" >Instructions</div>
			<div class="help-content" >Enter a text.<br/>Startposition x and y starts from the top left image corner.<br/>You can click on the image to select a startposition</div>
		</div>
	  </div>
	</div>  
	
</div>
<div class="loading">
	<div id="loading"></div>
</div>