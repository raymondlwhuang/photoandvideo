function ajax_call(phone,phone2,phone3) 
{ 
	var t=new Date().getTime();
    $.ajax({ 
       type: "POST", 
       url: "http://" + document.domain + "/api_subc_lp.php",
       data: "phone="+phone+"&phone2="+phone2+"&phone3="+phone3+"&t="+t, 
       success: function(msg){ 
         alert( "Sing up successfully: " + msg ); //Anything you want 
       }, 
		error:function (xhr, ajaxOptions, thrownError){ 
                    alert(xhr.status); 
                    alert(thrownError); 
        }     	   
     }); 
}