if(admin==1) {
	document.getElementById('Setup').style.display = "none";
}
if(rows<=page_rows) {
	document.getElementById('first').style.display = "none";
	document.getElementById('previous').style.display = "none";
	document.getElementById('next').style.display = "none";
	document.getElementById('last').style.display = "none";
}
function SharingList(require)  
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
	
	var url = 'SetSharing3';
	$(document).ready(function() {
	   $("#Result").load({url:url,data: "user_id="+user_id+"&pagenum="+pagenum});
	   $.ajaxSetup({ cache: false });
	});		
}

function Action(share_flag,viewer_id) {
    $.ajax({ 
       type: "POST", 
       url: "SetSharing2",
       data: "share_flag="+share_flag+"&user_id="+user_id+"&viewer_id="+viewer_id, 
       success: function(msg){
		 if(msg!="")  alert( msg ); //Anything you want 
       }, 
		error:function (xhr, ajaxOptions, thrownError){ 
                    alert(xhr.status); 
                    alert(thrownError); 
        }     	   
     }); 
}
document.getElementById('sharing').style.display = "none";