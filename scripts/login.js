function formCheck( )	  
{
	if(document.getElementById("login").value=="Cancle") {
		document.getElementById("login").value="Log In";
		document.getElementById("ErrorMessage").innerHTML = "";
		document.getElementById("autologin").style.display="inline-block";
		document.getElementById("UserSetup").innerHTML="New User<br />Reset/Forget password";
		return false;
	}
	if (document.ValidateUser.username.value == "") 
	{  
	   document.getElementById("ErrorMessage").innerHTML = "Please provide your valid email address!";
	   return false;
	}
	var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	if (!re.test(document.getElementById("username").value)){
		document.getElementById("ErrorMessage").innerHTML = "Please provide your valid email address!";
		return false;
	}
	if (document.ValidateUser.password.value == "") 
	{  
	   document.getElementById("ErrorMessage").innerHTML = "Password required!!";
	   document.getElementById("autologin").style.display="inline-block";
	   document.getElementById("password").focus();
	   return false;
	}
}

function validEMail(email) {
	document.getElementById("login").value="Cancle";
	document.getElementById("UserSetup").innerHTML="Send<br /> Require";
	if(email=="") {
		document.getElementById("ErrorMessage").innerHTML = "Please provide your valid email address!";
		document.getElementById("autologin").style.display="none";
		return false;
	}
	var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	if (!re.test(email)){
		document.getElementById("ErrorMessage").innerHTML = "Please provide your valid email address!";
	}
	alert("Your require had been sent.\nPlease check you e-mail and follow the instruction.");
	return re.test(email);			
}	
