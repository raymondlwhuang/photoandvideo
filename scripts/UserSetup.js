window.onload = initForms;
var thisMsg = true;
function initForms() {
	for (var i=0; i< document.forms.length; i++) {
		document.forms[i].onsubmit = function() {return validForm();}
	}
}
function validForm() {
	MessageTag = document.getElementById("ErrorMessage");
	MessageTag.color = "red";
	var allGood = true;
	var allTags = document.getElementsByTagName("*");

	for (var i=0; i<allTags.length; i++) {
		if (!validTag(allTags[i])) {
			allGood = false;
		}
	}

	return allGood;

	function validTag(thisTag) {
			var outClass = "";
			var allClasses = thisTag.className.split(" ");
			for (var j=0; j<allClasses.length; j++) {
				outClass += validBasedOnClass(allClasses[j]) + " ";
			}

			thisTag.className = outClass;

			if (outClass.indexOf(invalid) > -1) {
				if (colorLabel && thisMsg == true){invalidLabel(thisTag.parentNode.parentNode);}
				thisTag.focus();
				if (thisTag.nodeName == "INPUT") {
					thisTag.select();
				}
				return false;
			}
			thisMsg = true;
			return true;

			function validBasedOnClass(thisClass) {
				var classBack = "";

				switch(thisClass) {
					case "":
					case invalid:
						break;
					case emailfield:
						if (allGood && !validEmail(thisTag.value)) {
							classBack = invalid + " ";
						}
						classBack += thisClass;
						break;
					case passwordConfirm:
						if (allGood && !validPassword(thisTag.value)) {
							classBack = invalid + " ";
						}
						classBack += thisClass;
						break;
					case reqdfield:
						if (allGood && thisTag.value == "") {
							classBack = invalid + " ";
						}
						classBack += thisClass;
						break;
					default:
						classBack += thisClass;
				}
				return classBack;
			}
			function validEmail(email) {
				var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
				if (!re.test(email)){
					MessageTag.innerHTML = "** Invalid e-mail address! **";
				}
				else MessageTag.innerHTML = "";
				thisMsg = false;
				return re.test(email);
			}
			function validPassword(password) {
				if (document.getElementById("password").value.length < 8) {
					MessageTag.innerHTML = "** Password must at least 8 character long! **";
					thisMsg = false;
					return false;
				}
				if (document.getElementById("password").value != password){
				MessageTag.innerHTML = "** Confirm password must match to password entered! **";
				thisMsg = false;
				return false;
				}
				return true;
			}			
			function invalidLabel(parentTag) {
			var cnt=0;
			while(thisTag != parentTag.children.item(cnt).children.item(0)){
			cnt++;
			}
				var ColorLabel = parentTag.children.item(cnt - 1).children.item(0);
				MessageTag.innerHTML = "** " + ColorLabel.innerHTML.substring(0,ColorLabel.innerHTML.length -1) + " must be filled! **";
				ColorLabel.className += " invalid";
			}
		}
}

function CheckPassword(password) {
	MessageTag = document.getElementById("ErrorMessage");
	if (password.length < 8) {
		MessageTag.innerHTML = "** Password must at least 8 character long! **";
		return false;
	}
	var numbericOK = "0123456789";
	var UpperCaseOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	var LowerCaseOK = "abcdefghijklmnopqrstuvwxyz";
	var numberic = false;
	var UpperCase = false;
	var LowerCase = false;

	for (i = 0;  i < password.length;  i++)
	{
		ch = password.charAt(i);
		for (j = 0;  j < numbericOK.length;  j++) {
			if (ch == numbericOK.charAt(j)) {
				numberic = true;
			}
		}
		for (j = 0;  j < UpperCaseOK.length;  j++) {
			if (ch == UpperCaseOK.charAt(j)) {
				UpperCase = true;
			}
		}	
		for (j = 0;  j < LowerCaseOK.length;  j++) {
			if (ch == LowerCaseOK.charAt(j)) {
				LowerCase = true;
			}
		}
	}			
	if (numberic == false || UpperCase == false || LowerCase == false) {
		MessageTag.innerHTML = "** Password must be mix with number, upper and lower case character! **";
		return false;
	}
	return true;
}
