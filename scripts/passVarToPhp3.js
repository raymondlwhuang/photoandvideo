var xhr = false;
var statesArray = new Array();
function SendRequest (url,SearchGroup,owner_path) {
	var str = document.getElementById("searchField").value;
	document.getElementById("searchField").className = "";
	if (str != "") {
		if(document.getElementById("popups")) document.getElementById("popups").innerHTML = "";
		document.getElementById("popups").style.display = 'block';
		for (var i=0; i<statesArray.length; i++) {
			var thisState = statesArray[i];

			if (thisState.toLowerCase().indexOf(str.toLowerCase()) >= 0) {
				var tempDiv = document.createElement("div");
				tempDiv.innerHTML = thisState;
				tempDiv.onclick = makeChoice;
				tempDiv.className += " suggestions";
				document.getElementById("popups").appendChild(tempDiv);
			}
		}
		var foundCt = document.getElementById("popups").childNodes.length;
		if (foundCt == 0) {
			document.getElementById("searchField").className += " error";
		}
	}
	var forceActiveX = (window.ActiveXObject && location.protocol === "file:");
    if (window.XMLHttpRequest && !forceActiveX) {
        xhr = new XMLHttpRequest();
    }
    else {
        try {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        } catch(e) {}
    }	
	if (xhr) {
		xhr.onreadystatechange = setStatesArray;
		var queryString = url + "?SearchGroup=" + SearchGroup;
		xhr.open("GET", queryString, true);
		xhr.send(null);
	}
	else {
		alert("Sorry, but I couldn't create an XMLHttpRequest");
	}
}
function setStatesArray() {
	if (xhr.readyState == 4) {
       if (xhr.status == 0 || (xhr.status >= 200 && xhr.status < 300) 
        || xhr.status == 304 
        || xhr.status == 1223) {    // defined in ajax.js
       var allStates=xhr.responseText;
       statesArray = allStates.split(":::");
		}
		else {
			alert("There was a problem with the request " + xhr.status);
		}
	}
}
function makeChoice(evt) {
	if (evt) {	
		var thisDiv = evt.target;
	}
	else {
		var thisDiv = window.event.srcElement;
	}
//	if(thisDiv.innerHTML.substring(0,1) == 1) document.getElementById("allow_viewer_upload").checked = true;
//	else document.getElementById("allow_viewer_upload").checked = false;
	document.getElementById("searchField").value = thisDiv.innerHTML;
	document.getElementById("popups").innerHTML = "";
	document.getElementById("popups").style.display = 'none';
//	document.DescSearch.submit();
}
function clearChoice() {
	document.getElementById("searchField").value = "";
	document.getElementById("popups").innerHTML = "";
}
