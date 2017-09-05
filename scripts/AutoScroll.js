window.onload = scrollup;
document.onmousedown=scrollup;

function ScrollWindowContents(){
	if (navigator.appName == "Microsoft Internet Explorer")
		WindowTopPosition=document.body.scrollTop;
	else
		WindowTopPosition=window.pageYOffset;

	//Keep ToggleVariable switching between 1 and 0
	if (ToggleVariable==0) ToggleVariable=1; else ToggleVariable=0;
	if (ToggleVariable==0) ScrollPosition1=WindowTopPosition; else ScrollPosition2=WindowTopPosition;

		if (navigator.appName == "Microsoft Internet Explorer") {
			WindowPostion=WindowPostion+ScrollStep+MiniAdjust;
			if (totalHeight + Ajustment <= WindowPostion){
				WindowPostion = WindowTopPosition + 1;
			}
			window.scroll(0,WindowPostion);	
		}
		else {
			if (ScrollPosition1!=ScrollPosition2){ // ScrollPostion1 NOT equal to ScrollPosition2, means more to scroll !
				WindowPostion=window.pageYOffset+ScrollStep;
				window.scroll(0,WindowPostion);
			}
			else{
			// ScrollPostion1 will be equal to ScrollPosition2 when end of the page has been reached
			WindowPostion=0
			window.scroll(0,WindowPostion)
			}		
		}
}

function scrollup() {
	if(upid=="") upid=setInterval('ScrollWindowContents()',ScrollInterval);
	else {clearInterval(upid); upid="";}
}
