var xhr = false;
var MyMsg = '';
function SendRequest (url,DispTarget,searchField) {
	if(document.getElementById(searchField)) document.getElementById(searchField).className = "";
	if(document.getElementById(DispTarget).childNodes.length != 16) {
		document.getElementById(DispTarget).innerHTML = "";
	}
				var tempDiv = document.createElement("div");
				tempDiv.innerHTML = MyMsg;
				tempDiv.className += " suggestions";
				document.getElementById(DispTarget).appendChild(tempDiv);
		var foundCt = document.getElementById(DispTarget).childNodes.length;
		if (foundCt == 0 && document.getElementById(searchField) ) {
			document.getElementById(searchField).className += " error";
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
		xhr.onreadystatechange = setMyMsg;
		var queryString = url;
		xhr.open("GET", queryString, true);
		xhr.send(null);
	}
	else {
		alert("Sorry, but I couldn't create an XMLHttpRequest");
	}
}
function setMyMsg() {
	if (xhr.readyState == 4) {
       if (xhr.status == 0 || (xhr.status >= 200 && xhr.status < 300) 
        || xhr.status == 304 
        || xhr.status == 1223) {    // defined in ajax.js
			MyMsg=xhr.responseText;
		}
		else {
			alert("There was a problem with the request " + xhr.status);
		}
	}
}
