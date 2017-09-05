if(shareallowed=="no") document.getElementById('Result').style.display = "none";
function Action(picture,pv_id,upload_id) {
	document.getElementById("profile").src=picture;
	document.getElementById("pv_id").value=pv_id;
	document.getElementById("thisPicture").value=picture;
	document.getElementById("All").checked=false;
	var url = 'PicCommentList2?pv_id='+pv_id+'&upload_id='+upload_id+'&pagenum=1';
	$(document).ready(function() {
	   $("#ComMain").load(url);
	   $.ajaxSetup({ cache: false });
	});
	Sharepagenum=1;
	var pv_id=document.getElementById('pv_id').value;
	var url = 'ShareList?user_id='+user_id+'&Sharepagenum='+Sharepagenum+'&pv_id='+pv_id;
	$(document).ready(function() {
	   $("#ShareList").load(url);
	   $.ajaxSetup({ cache: false });
	});	
}
function Share(shareto_id,flag) {
	var answer = confirm("Please click \"OK\" to confirm")
	var success = true;
	if (answer){
		var pv_id=document.getElementById("pv_id").value;
		if(document.getElementById(shareto_id).checked)	var share=1;
		else var share=0;
		$.ajax({ 
		   type: "POST", 
		   url: "UpdateShare",
		   data: "pv_id="+pv_id+"&user_id="+user_id+"&shareto_id="+shareto_id+"&flag="+flag+"&share="+share, 
			error:function (xhr, ajaxOptions, thrownError){ 
				alert(xhr.status); 
				alert(thrownError);
				success	=false;
			}     	   
		 });
		if(success){
		alert( "Required have been successfully set" );
		Sharepagenum=1;
		var pv_id=document.getElementById('pv_id').value;
		var url = 'ShareList?user_id='+user_id+'&Sharepagenum='+Sharepagenum+'&pv_id='+pv_id;
		$(document).ready(function() {
		   $("#ShareList").load(url);
		   $.ajaxSetup({ cache: false });
		});	
		}
	}
	else{
		document.getElementById(shareto_id).checked=!document.getElementById(shareto_id).checked;
	}

}

if(Sharerows<=Sharepage_rows) {
	document.getElementById('Sharefirst').style.display = "none";
	document.getElementById('Shareprevious').style.display = "none";
	document.getElementById('Sharenext').style.display = "none";
	document.getElementById('Sharelast').style.display = "none";
}
function SharingList(require)  
{  
	if(require=='first') Sharepagenum=1;
	else if(require=='previous') {
	  Sharepagenum = Sharepagenum - 1;
	} 
	else if(require=='next') {
	  Sharepagenum = Sharepagenum + 1;
	} 
	else if(require=='last') Sharepagenum=Sharelast;
	if(Sharepagenum > 1 && Sharepagenum < Sharelast) {
		document.getElementById('Sharefirst').src = "/images/first.png";
		document.getElementById('Shareprevious').src = "/images/previous.png";
		document.getElementById('Sharenext').src = "/images/next1.png";
		document.getElementById('Sharelast').src = "/images/last.png";
	}
	else if(Sharepagenum<=1) {
		Sharepagenum = 1;
		document.getElementById('Sharefirst').src = "/images/first2.png";
		document.getElementById('Shareprevious').src = "/images/previous2.png";
		document.getElementById('Sharenext').src = "/images/next1.png";
		document.getElementById('Sharelast').src = "/images/last.png";
	}
	 else if(Sharepagenum>=Sharelast) {
		Sharepagenum = Sharelast;
		document.getElementById('Sharefirst').src = "/images/first.png";
		document.getElementById('Shareprevious').src = "/images/previous.png";
		document.getElementById('Sharenext').src = "/images/next2.png";
		document.getElementById('Sharelast').src = "/images/last2.png";
	 }
	var pv_id=document.getElementById('pv_id').value;
	var url = 'ShareList?user_id='+user_id+'&Sharepagenum='+Sharepagenum+'&pv_id='+pv_id;
	$(document).ready(function() {
	   $("#ShareList").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}
if(Comcount<=page_rows) {
	if(document.getElementById('ComNav')) document.getElementById('ComNav').style.display = "none";
	if(document.getElementById('first')) document.getElementById('first').style.display = "none";
	if(document.getElementById('previous')) document.getElementById('previous').style.display = "none";
	if(document.getElementById('next')) document.getElementById('next').style.display = "none";
	if(document.getElementById('last')) document.getElementById('last').style.display = "none";
}

function CommentList(require,upload_id)  
{  
	var pv_id = document.getElementById('pv_id').value;
	if(require=='first') pagenum=1;
	else if(require=='previous') {
	  pagenum = pagenum - 1;
	} 
	else if(require=='next') {
	  pagenum = pagenum + 1;
	} 
	else if(require=='last') pagenum=last;

	if(pagenum > 1 && pagenum < last) {
		document.getElementById('first').src = "/images/first_up.png";
		document.getElementById('previous').src = "/images/previous_up.png";
		document.getElementById('next').src = "/images/next_up.png";
		document.getElementById('last').src = "/images/last_up.png";
	}
	else if(pagenum<=1) {
		pagenum = 1;
		document.getElementById('first').src = "/images/first_up2.png";
		document.getElementById('previous').src = "/images/previous_up2.png";
		document.getElementById('next').src = "/images/next_up.png";
		document.getElementById('last').src = "/images/last_up.png";
	}
	 else if(pagenum>=last) {
		pagenum = last;
		document.getElementById('first').src = "/images/first_up.png";
		document.getElementById('previous').src = "/images/previous_up.png";
		document.getElementById('next').src = "/images/next_up2.png";
		document.getElementById('last').src = "/images/last_up2.png";
	 }
	var url = 'PicCommentList?pv_id='+pv_id+'&upload_id='+upload_id+'&pagenum='+pagenum;
	$(document).ready(function() {
	   $("#comments").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}