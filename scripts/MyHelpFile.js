window.onload = initAll;

function initAll() {
	var allLinks = document.getElementsByTagName("div");
	
	for (var i=0; i<allLinks.length; i++) {
		allLinks[i].onmouseover = function (){if(this.className) document.getElementById(this.className).style.display = "block";}
		allLinks[i].onmouseout =  function (){if(this.className) document.getElementById(this.className).style.display = "none";}
	}
}

