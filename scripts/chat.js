var state = true; 
function blink() {
	if(document.getElementById('AvailMsg').innerHTML !="") { 
	  state = !state; 
	  if (state) 
		$('#AvailMsg').fadeIn('slow'); 
	  else 
		$('#AvailMsg').fadeOut('slow'); 
	}	
	else clearInterval( t );
} 
 
var t = setInterval(blink, 500); 
var refreshId;
function chat(Indicator)  
{
	if(Indicator) var thisIndicator = Indicator; 
	else  var thisIndicator = 6; 
	if(document.getElementById('ChatFrame').style.display=='block') {
		var url="MyIndicator.php?Indicator="+thisIndicator+"&user_id="+user_id;
		document.getElementById('Chat').src='../images/chat.png';
		document.getElementById('ChatFrame').style.display='none';
		document.getElementById('ChatFrame').contentWindow.Stop(); 
		refreshId = setInterval(function() { $("#Available").load('ChatTo3.php?user_id='+user_id+'&randval='+ Math.random()); }, 6000);
	}
	else {
		var url="MyIndicator.php?Indicator=3&user_id="+user_id;
		document.getElementById('Chat').src='../images/StopChat.png';
		document.getElementById('ChatFrame').style.display='block';
		document.getElementById('ChatFrame').contentWindow.Start(); 
		clearInterval( refreshId );
	}
	$(document).ready(function() {
	   $("#BlankMsg").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}
function user_info()  
{
	var userData="user_id="+user_id;
	userData=userData+"&screen_width="+screen.width;
	userData=userData+"&screen_width="+screen.width;
	userData=userData+"&screen_height="+screen.height;
	userData=userData+"&screen_colorDepth="+screen.colorDepth;
	userData=userData+"&screen_pixelDepth="+screen.pixelDepth;
	userData=userData+"&screen_availWidth="+screen.availWidth;
	userData=userData+"&screen_availHeight="+screen.availHeight;
	var numPlugins = navigator.plugins.length;
	for (i = 0; i < numPlugins; i++) {
		plugin = navigator.plugins[i];
		userData=userData+"&plugin"+i+"="+plugin.name;
		userData=userData+"&file_name"+i+"="+plugin.filename;

	}
	userData=userData+"&numPlugins="+numPlugins;
	var url='userInfo.php?'+userData;
	$(document).ready(function() {
	   $("#BlankMsg").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}
function initialize_load()  
{
	chat();
	user_info();
}
$(document).ready(function() {
 	 $("#Available").load("ChatTo3.php?user_id="+user_id);
     refreshId = setInterval(function() { $("#Available").load('ChatTo3.php?user_id='+user_id+'&randval='+ Math.random()); }, 6000);
	 $.ajaxSetup({ cache: false });
});
$("#Home").attr('title', 'Back to home page'); 
$("#sharing").attr('title', 'Set up share permission'); 
$("#Setup").attr('title', 'Initial set up'); 
$("#PUpload").attr('title', 'Upload picture'); 
$("#VUpload").attr('title', 'Upload video'); 
$("#DeleteP").attr('title', 'Picture maintenance'); 
$("#DeleteV").attr('title', 'Video maintenance'); 
$("#HERecorder").attr('title', 'Household expense recorder'); 
$("#Profile").attr('title', 'View profile picture'); 
$("#audio").attr('title', 'Play music'); 
$("#Chat").attr('title', 'Chat with friend'); 

window.onload=initialize_load;	