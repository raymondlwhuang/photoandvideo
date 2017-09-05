function alertsize(pixels,flag){
    pixels+=132;
    if(flag==1) document.getElementById('Main2').style.height=pixels+"px";
    else if(flag==2) document.getElementById('frame1').style.height=pixels+"px";
}
if(admin==1) {
	document.getElementById('Setup').style.display = "none";
}
if(rows<=page_rows) {
	if(document.getElementById('first')) document.getElementById('first').style.display = "none";
	if(document.getElementById('previous')) document.getElementById('previous').style.display = "none";
	if(document.getElementById('next')) document.getElementById('next').style.display = "none";
	if(document.getElementById('last')) document.getElementById('last').style.display = "none";
}
if(rows2<=page_rows2) {
	if(document.getElementById('first2')) document.getElementById('first2').style.display = "none";
	if(document.getElementById('previous2')) document.getElementById('previous2').style.display = "none";
	if(document.getElementById('next2')) document.getElementById('next2').style.display = "none";
	if(document.getElementById('last2')) document.getElementById('last2').style.display = "none";
}
if(rows2<=0) {if(document.getElementById('showtitil')) document.getElementById('showtitil').style.display = "none";}
else  {if(document.getElementById('showtitil')) document.getElementById('showtitil').style.display = "block";}
function SendRequest(url,ajaxobj)  
{
	$(document).ready(function() {
	   $("#"+ajaxobj).load(url);
	   $.ajaxSetup({ cache: false });
	});
}
function FriendList(require)  
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
	

	var url = 'FriendList?user_id='+user_id+'&pagenum='+pagenum;
	$(document).ready(function() {
	   $("#Friends").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}
function MayBeFriend(require)  
{  
	if(require=='first') pagenum2=1;
	else if(require=='previous') {
	  pagenum2 = pagenum2 - 1;
	} 
	else if(require=='next') {
	  pagenum2 = pagenum2 + 1;
	} 
	else if(require=='last') pagenum2=last2;
	 if(pagenum2<=1) { 
		pagenum2 = 1;
		document.getElementById('first2').src = "/images/first2.png";
		document.getElementById('previous2').src = "/images/previous2.png";
		document.getElementById('next2').src = "/images/next1.png";
		document.getElementById('last2').src = "/images/last.png";
	 }
	 else if(pagenum2>=last2) {
		pagenum2 = last2;
		document.getElementById('first2').src = "/images/first.png";
		document.getElementById('previous2').src = "/images/previous.png";
		document.getElementById('next2').src = "/images/next2.png";
		document.getElementById('last2').src = "/images/last2.png";
	 }
	 else {
		document.getElementById('first2').src = "/images/first.png";
		document.getElementById('previous2').src = "/images/previous.png";
		document.getElementById('next2').src = "/images/next1.png";
		document.getElementById('last2').src = "/images/last.png";
	 }
	 if(last2==1) {
		document.getElementById('first2').src = "/images/first2.png";
		document.getElementById('previous2').src = "/images/previous2.png";
		document.getElementById('next2').src = "/images/next2.png";
		document.getElementById('last2').src = "/images/last2.png";
	 }
	var url = 'MayBeFriend?user_id='+user_id+'&pagenum='+pagenum2;
	$(document).ready(function() {
	   $("#MyBeFriends").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}
function refreshiframe(name,FriendID,picture,show_id,FriendPath)  
{  
	document.getElementById('my_name').innerHTML = name.substring(0,20);
	document.getElementById('FriendID').value = FriendID;
	document.getElementById('FriendPath').value = FriendPath;
	document.getElementById('ProfilPicture').src = picture;
//	document.getElementById('frame1').src="/PictureGroup?name="+name+"&FriendID="+FriendID;
	if(OrgFriendID!=FriendID){
		var url = 'PictureVideoCheck?FriendID='+FriendID+'&ViewerID='+user_id;
		$.get(url, function(result) {
			if(result==1){
				window.open("PictureGroup?name="+name+"&FriendID="+FriendID, "Group");
				window.open("PictureMain?show_id="+show_id+"&FriendID="+FriendID, "MyBlog");
			}
		});	
	}
	window.parent.scroll(0,0);
}
function makefriend(name,FriendID)  
{ 
	var viewer_group = prompt("You want to add "+name+" as your friend?\nIf so please assign a group then click OK", "");
	if (viewer_group!="" && viewer_group!=null){
		$.ajax({ 
		   type: "POST", 
		   url: "RequiredAsFriend",
		   data: "user_id="+user_id+"&FriendID="+FriendID+"&viewer_group="+viewer_group, 
		   success: function(msg){ 
			 alert( "Your require as a friend to "+name+"has been " + msg ); //Anything you want 
		   }, 
			error:function (xhr, ajaxOptions, thrownError){ 
						alert(xhr.status); 
						alert(thrownError); 
			}     	   
		 }); 	
		window.parent.scroll(0,0);
	}
	else {
		if(viewer_group!=null)	 alert("You must assign a group to your friend!\nPlease try again!"); 
	}

}
function Action_pic(disp)  
{
	var curr = document.getElementById('FriendID').value;
	var FriendPath = document.getElementById('FriendPath').value;
	if(disp==1 && curr!="Public" && curr!="Temporary") {
		document.getElementById('popups').style.display = 'block';
		if(user_id==curr) document.getElementById('popups').innerHTML="Change Picture";
		else document.getElementById('popups').innerHTML="Profile Pictures";
	}
	else document.getElementById('popups').style.display = 'none';
	if(user_id==curr && disp==3 && curr!="Public" && curr!="Temporary") window.open('ChangeProfile',target='_top');
	else if(user_id!=curr && disp==3 && curr!='Public' && curr!='Temporary')  window.open('ProfilePicture?FriendPath='+FriendPath,target='_top');
}



function Set_OrgFriendID(CurrID)  
{
  OrgFriendID=CurrID;
}