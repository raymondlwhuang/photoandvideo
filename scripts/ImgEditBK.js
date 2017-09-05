var to=null, to2=null,to3=null, to4=null,to5=null,thisvalue=0,flag=0,t=1500,cur_page=1;
function PictureFilter() {
	document.getElementById("loading").style.display="inline-block";
	if(document.getElementById("cropbtn").value=="Crop") {
		document.getElementById("cropbtn").value="Reset"
		 var elem = document.getElementsByClassName('field');
		 for (var i = 0; i < elem.length; i++) {
		 elem[i].style.display="none";
		 }
		 document.getElementById("crophelp").style.display="none";
	}
	t=1500;
	var imgEffect="";
	for(i=1;i<=12;i++){
		filter="filter"+i;
		if(document.getElementById(filter).checked) {
			imgEffect=imgEffect+","+document.getElementById(filter).value;
		}
	}
	filename=CropPic;
	Smoothvalue=document.getElementById("Smoothvalue").value;
	red=document.getElementById("red").value;
	green=document.getElementById("green").value;
	blue=document.getElementById("blue").value;
	Contrast=document.getElementById("Contrast").value;
	Brightness=document.getElementById("Brightness").value;
	Pixelate=document.getElementById("Pixelate").value;
	imgEffect=imgEffect+",";

	if(document.getElementById("doResize").checked==false && document.getElementById("doRotation").checked ==false && document.getElementById("doRoundCorner").checked==false) flag=1;
	if(flag==1) {
		$('#editimage').html('<img src="ImgFilter.php?time_x='+new Date().getTime()+'&filename='+filename + '&toNewPic='+toNewPic + '&beforAddText='+beforAddText + '&imgEffect='+imgEffect + '&Smoothvalue='+Smoothvalue + '&red='+red + '&green='+green + '&blue='+blue + '&Contrast='+Contrast + '&Brightness='+Brightness + '&Pixelate='+Pixelate + '"'+'id="image" />');
		document.getElementById("cur_picture").value=toNewPic;
		flag=2;
	}
	if(flag==2) {
		if(document.getElementById("doResize").checked) {
			window.clearTimeout(to);
			to=window.setTimeout ( function() { PictureResize(); },   t  ); 
		};
		if(document.getElementById("doRotation").checked || document.getElementById("doRoundCorner").checked) {
			window.clearTimeout(to2);
			t=t+1500;
			to2=window.setTimeout ( function() { PictureRotation(); },  t  ); 
		};
		flag=3;
	}
	if(flag==3) {
//		if(document.getElementById("text_to_display").value!="") {
			window.clearTimeout(to3);
			t=t+1500;
			to3=window.setTimeout ( function() { AddText(); },  t  ); 
//		}
	}
	if(document.getElementById("loading").style.display=="inline-block"){
	    window.clearTimeout(to4);
		to4=window.setTimeout ( function() { document.getElementById("loading").style.display="none"; },  t  ); 
	}
	
}

function PictureResize() {
	Percent=document.getElementById("resize").value;
	cur_img="ImgResizing.php?time_x="+new Date().getTime()+"&filename="+toNewPic + "&Percent="+Percent + "&toNewPic="+toNewPic2 + '&beforAddText='+beforAddText;
//	alert('tst');
	$('#editimage').html('<img src="'+cur_img + '"'+'id="image" />');
	document.getElementById("cur_picture").value=toNewPic2;
}
function PictureRotation() {
	degrees=document.getElementById("degrees").value;
	radius=document.getElementById("radius").value;
	if(document.getElementById("topleft").checked)	topleft="yes"
	else topleft="no";
	if(document.getElementById("bottomleft").checked)	bottomleft="yes"
	else bottomleft="no";
	if(document.getElementById("bottomright").checked)	bottomright="yes"
	else bottomright="no";
	if(document.getElementById("topright").checked)	topright="yes"
	else topright="no";

	if(radius==0) {
		document.getElementById("corner").style.display="none";
		if(document.getElementById("doResize").checked) {
			cur_img="ImgAngleControl.php?time_x="+new Date().getTime()+"&filename="+toNewPic2 + "&degrees="+degrees + "&toNewPic="+toNewPic3 + '&beforAddText='+beforAddText;
		}
		else {
			cur_img="ImgAngleControl.php?time_x="+new Date().getTime()+"&filename="+toNewPic + "&degrees="+degrees + "&toNewPic="+toNewPic3 + '&beforAddText='+beforAddText;
		}
	}
	else {
		document.getElementById("corner").style.display="block";
		if(document.getElementById("doResize").checked) {
			cur_img="ImgRoundCorner.php?time_x="+new Date().getTime()+"&filename="+toNewPic2 + "&degrees="+degrees + "&toNewPic="+toNewPic3 + '&beforAddText='+beforAddText + "&radius="+radius + "&topleft="+topleft + "&bottomleft="+bottomleft + "&bottomright="+bottomright + "&topright="+topright;
		}
		else {
			cur_img="ImgRoundCorner.php?time_x="+new Date().getTime()+"&filename="+toNewPic + "&degrees="+degrees + "&toNewPic="+toNewPic3 + '&beforAddText='+beforAddText + "&radius="+radius + "&topleft="+topleft + "&bottomleft="+bottomleft + "&bottomright="+bottomright + "&topright="+topright;
		}
	}
	document.getElementById("cur_picture").value=toNewPic3;
	$('#editimage').html('<img src="'+cur_img + '"'+'id="image" />');
}
function AddText() {
	text_to_display=document.getElementById("text_to_display").value;
	font=document.getElementById("font").value;
	textrotate=document.getElementById("textrotate").value;
	if(document.getElementById("dofontsize").checked) fontsize=document.getElementById("fontsize").value;
	else fontsize=24;
	if(document.getElementById("dofontcolor").checked) {
		FontR=document.getElementById("FontR").value;
		FontG=document.getElementById("FontG").value;
		FontB=document.getElementById("FontB").value;
	}
	else {
		FontR=0;
		FontG=0;
		FontB=0;
	}
//	if(!(FontR==255 && FontG==255 && FontB==255)) document.getElementById('text_to_display').style.color = "rgb("+FontR+","+FontG+","+FontB+")";
//	else  document.getElementById('text_to_display').style.color = "rgb(0,0,0)";
//	bg_red=document.getElementById("bg_red").value;
//	bg_green=document.getElementById("bg_green").value;
//	bg_blue=document.getElementById("bg_blue").value;
	positionx=document.getElementById("positionx").value;
	positiony=document.getElementById("positiony").value;
	if(text_to_display!="" || cur_page==3) {
		if(document.getElementById("dofontsize").checked || document.getElementById("dofontcolor").checked || document.getElementById("dotextrotate").checked || document.getElementById("dofont").checked || text_to_display!="") 
		{
			cur_img="AddTextToImg.php?time_x="+new Date().getTime()+"&filename="+beforAddText+"&toNewPic="+toNewPic4 + '&beforAddText='+beforAddText+"&text_to_display="+text_to_display+"&font="+font+"&fontsize="+fontsize+"&textrotate="+textrotate+"&FontR="+FontR+"&FontG="+FontG+"&FontB="+FontB+"&positionx="+positionx+"&positiony="+positiony;
			$('#editimage').html('<img src="'+cur_img + '"'+'id="image" />');
			if(text_to_display!="") document.getElementById("cur_picture").value=beforAddText;
			if(text_to_display=="" && cur_page!=3) $('#editimage').html('<img src="'+beforAddText + '?time_x='+new Date().getTime()+'"'+'id="image" />');
		}
		else if(text_to_display=="" && cur_page==3) {
		$('#editimage').html('<img src="'+beforAddText + '?time_x='+new Date().getTime()+'"'+'id="image" />');
		}
		
	}
}
function Changed(elem1,elem2,setValue) {
	if(document.getElementById(elem1).checked==false) document.getElementById(elem2).value=setValue;
}
function DoPictureFilter(elem,elem2) {
	if(thisvalue!=document.getElementById(elem2).value && document.getElementById(elem).checked) PictureFilter();
}
function ShowOpt(opt) {
	if(opt==1) {
		$('#image').imgAreaSelect({ disable: true, hide: true });
		document.getElementById("cropopt").style.display="none";
		document.getElementById("effect").style.display="inline-block";
		document.getElementById("addtext").style.display="none";
		document.getElementById("croptab").style.backgroundImage="url('../images/tab_not_selected.png')";
		document.getElementById("texttab").style.backgroundImage="url('../images/tab_not_selected.png')";
		document.getElementById("effecttab").style.backgroundImage="url('../images/tab_selected.png')";
		document.getElementById("croptab").style.color='white';
		document.getElementById("texttab").style.color='white';
		document.getElementById("effecttab").style.color='black';
		document.getElementById("image").src=document.getElementById("cur_picture").value;
		cur_page=2;
		PictureFilter();
	}
	else if(opt==2) {
		$('#image').imgAreaSelect({ disable: true, hide: true });
		document.getElementById("cropopt").style.display="none";
		document.getElementById("effect").style.display="none";
		document.getElementById("addtext").style.display="inline-block";
		document.getElementById("croptab").style.backgroundImage="url('../images/tab_not_selected.png')";
		document.getElementById("effecttab").style.backgroundImage="url('../images/tab_not_selected.png')";
		document.getElementById("texttab").style.backgroundImage="url('../images/tab_selected.png')";
		document.getElementById("croptab").style.color='white';
		document.getElementById("texttab").style.color='black';
		document.getElementById("effecttab").style.color='white';
		document.getElementById("image").src=document.getElementById("cur_picture").value;
		cur_page=3;
		AddText();
	}
	else {
		window.open('EditPicture.php?thisPicture='+document.getElementById("cur_picture").value+'&org_picture='+document.getElementById("org_picture").value + '&beforAddText='+beforAddText,target='_top');
	}
}
function cropImage() {
	if(document.getElementById("cropbtn").value=="Crop") {
		x=document.getElementById("cropx").value;
		y=document.getElementById("cropy").value;
		cropWidth=document.getElementById("cropwidth").value;
		cropHeight=document.getElementById("cropheight").value;
		if(!(x==0&&y==0&&cropWidth==0&&cropHeight==0)) {
			document.getElementById("loading").style.display="inline-block";
			document.getElementById("cropbtn").value="Reset"
			cur_img="cropImage.php?time_x="+new Date().getTime()+"&filename="+document.getElementById("cur_picture").value+"&x="+x+"&y="+y+"&cropWidth="+cropWidth+"&cropHeight="+cropHeight+"&CropPic="+CropPic + '&beforAddText='+beforAddText;
			$('#editimage').html('<img src="'+cur_img + '"'+'id="image" />');
			
			window.clearTimeout(to5);
			to5 = window.setTimeout ( function() { window.open('EditPicture.php?thisPicture='+CropPic+'&org_picture='+document.getElementById("org_picture").value + '&beforAddText='+beforAddText,target='_top'); },   1500  );
		}
	}
	else {
		window.open('EditPicture.php?thisPicture='+document.getElementById("cur_picture").value+'&org_picture='+document.getElementById("org_picture").value + '&beforAddText='+beforAddText,target='_top');
	}
}
function point_it(event){
	pos_x = event.offsetX?(event.offsetX):event.pageX-document.getElementById("editimage").offsetLeft;
	pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("editimage").offsetTop;
	document.getElementById("positionx").value = pos_x;
	document.getElementById("positiony").value = pos_y;
	document.getElementById('cursorX').innerHTML = "";
	document.getElementById('cursorY').innerHTML = "";
	
	AddText();
}
function init() {
	if (window.Event) {
	document.captureEvents(Event.MOUSEMOVE);
	}
	document.onmousemove = getCursorXY;
}

function getCursorXY(e) {
	document.getElementById('cursorX').innerHTML = (window.Event) ? e.pageX-document.getElementById("editimage").offsetLeft : event.clientX-document.getElementById("editimage").offsetLeft+(document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
	document.getElementById('cursorY').innerHTML = (window.Event) ? e.pageY-document.getElementById("editimage").offsetTop : event.clientY-document.getElementById("editimage").offsetTop +(document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
}
window.onload = init();
