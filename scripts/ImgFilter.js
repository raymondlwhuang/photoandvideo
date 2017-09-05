var to=null, to2=null,to3=null, to4=null,to5=null,thisvalue=0,flag=0,t=1500;
function PictureFilter() {
	var imgEffect="";
	for(i=1;i<=12;i++){
		filter="filter"+i;
		if(document.getElementById(filter) && document.getElementById(filter).checked) {
			imgEffect=imgEffect+","+document.getElementById(filter).value;
		}
	}
	filename=document.getElementById("thisPicture").value;
	Contrast=document.getElementById("Contrast").value;
	Brightness=document.getElementById("Brightness").value;
	imgEffect=imgEffect+",";
	$('#loadimg').html('<img src="ImgFilter?time_x='+new Date().getTime()+'&filename=.'+filename + '&imgEffect='+imgEffect + '&Contrast='+Contrast + '&Brightness='+Brightness + '" width="640"'+'id="profile" />');
}
function Changed(elem1,elem2,setValue) {
	if(document.getElementById(elem1).checked==false) document.getElementById(elem2).value=setValue;
}
function DoPictureFilter(elem,elem2) {
	if(thisvalue!=document.getElementById(elem2).value && document.getElementById(elem).checked) PictureFilter();
}
