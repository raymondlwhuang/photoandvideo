//configure shake degree (where larger # equals greater shake)
var rector=3
var state = true; 
///////DONE EDITTING///////////
var stopit=0 
var a=1

function init(which){
stopit=0
shake=which
shake.style.left=0
shake.style.top=0
}
function rattleimage(){
if ((!document.all&&!document.getElementById)||stopit==1)
return
if (a==1){
shake.style.top=parseInt(shake.style.top)+rector+"px"
}
else if (a==2){
shake.style.left=parseInt(shake.style.left)+rector+"px"
}
else if (a==3){
shake.style.top=parseInt(shake.style.top)-rector+"px"
}
else{
shake.style.left=parseInt(shake.style.left)-rector+"px"
}
if (a<4)
a++
else
a=1
setTimeout("rattleimage()",50)
}
function stoprattle(which){
stopit=1
which.style.left=0
which.style.top=0
}

if(rows<=page_rows) {
	document.getElementById('first').style.display = "none";
	document.getElementById('previous').style.display = "none";
	document.getElementById('next').style.display = "none";
	document.getElementById('last').style.display = "none";
}
function PictureList(require,FriendID)  
{  
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

	var url = base_url+'PictureList?FriendID='+FriendID+'&viewer_id='+viewer_id+'&pagenum='+pagenum;
	$.ajax({
	  url: url,
	  success: function(data) {
		$("#Pictures").html(data);
	  }
	});
}

function refreshiframe(upload_id)  
{
	window.open( "PictureMain?FriendID="+document.getElementById("FriendID").value + "&show_id=" + upload_id, "MyBlog");
}
function Action(url,affect_id) {
	var id = "#"+affect_id;
	$(document).ready(function() {
	   $(id).load(url);
	   $.ajaxSetup({ cache: false });
	});
	window.parent.scroll(0,0);
}
function blink() {
	if(document.getElementById('SharedPicture').innerHTML !="") { 
	  state = !state; 
	  if (state) 
		$('#SharedPicture').fadeIn('slow'); 
	  else 
		$('#SharedPicture').fadeOut('slow'); 
	}	
	else clearInterval( t );
} 
 
var t = setInterval(blink, 500); 