var httpRequest = null;
var affectobj = null;
function CreateHTTPRequestObject () {
    var forceActiveX = (window.ActiveXObject && location.protocol === "file:");
    if (window.XMLHttpRequest && !forceActiveX) {
        return new XMLHttpRequest();
    }
    else {
        try {
            return new ActiveXObject("Microsoft.XMLHTTP");
        } catch(e) {}
    }
    alert ("Your browser doesn't support XML handling!");
    return null;
}

function SendRequest (url,ajaxobj) {
	affectobj = ajaxobj;
    if (!httpRequest) {
        httpRequest = CreateHTTPRequestObject ();
    }
    if (httpRequest) {
            // The requested file must be in the same domain that the page is served from.
        httpRequest.open ("GET", url, true);    // async
        httpRequest.onreadystatechange = OnStateChange;
        httpRequest.send (null);
    }
}

function OnStateChange () {
    if (httpRequest.readyState==4) {
        if (IsRequestSuccessful (httpRequest)) {
        	var newbody = document.getElementById(affectobj);
        	var newtext = httpRequest.responseText.replace(/\\\\n/mg,"\\n");
        	newbody.innerHTML = newtext;
       }
        else {
            alert ("Operation failed.");
        }
    }
}
function IsRequestSuccessful (httpRequest) {
    var success = (httpRequest.status == 0 ||
        (httpRequest.status >= 200 && httpRequest.status < 300) ||
        httpRequest.status == 304 || httpRequest.status == 1223);

    return success;
}
