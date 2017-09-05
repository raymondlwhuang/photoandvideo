window.onload = initForm;
window.onunload = function() {};

function initForm() {
	document.getElementById("newLocation").selectedIndex = 0;
	document.getElementById("newLocation").onchange = jumpPage;
}

function jumpPage() {
	
	var newLoc = document.getElementById("newLocation");
	var newPage = newLoc.options[newLoc.selectedIndex].value;

	if (newPage != "") {
		window.open( "VideoShow2.php?SetShow2=" + newPage, "VideoMain");
	}	

}