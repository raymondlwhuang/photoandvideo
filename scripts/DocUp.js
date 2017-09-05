window.onload = scrollup;
document.onmousedown=scrollup;
var WindowPostion=0, ToggleVariable=1, ScrollPosition1=0, ScrollPosition2=-1,upid="";

function ScrollWindowContents(){
	if (navigator.appName == "Microsoft Internet Explorer")
		WindowTopPosition=document.body.scrollTop;
	else
		WindowTopPosition=window.pageYOffset;

	//Keep ToggleVariable switching between 1 and 0
	if (ToggleVariable==0) ToggleVariable=1; else ToggleVariable=0;
	if (ToggleVariable==0) ScrollPosition1=WindowTopPosition; else ScrollPosition2=WindowTopPosition;

	// ScrollPostion1 will be equal to ScrollPosition2 when end of the page has been reached
	if (ScrollPosition1!=ScrollPosition2){ // ScrollPostion1 NOT equal to ScrollPosition2, means more to scroll !
		if (navigator.appName == "Microsoft Internet Explorer")
			WindowPostion=document.body.scrollTop+1;
		else WindowPostion=window.pageYOffset+1;
		window.scroll(0,WindowPostion);
	}
	else{
	// ScrollPostion1 will be equal to ScrollPosition2 when end of the page has been reached
	WindowPostion=0
	window.scroll(0,WindowPostion)
	}
}

function scrollup() {
	if(upid=="") upid=setInterval('ScrollWindowContents()',60);
	else {clearInterval(upid); upid="";}
}
