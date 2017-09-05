var total=0;
$(document).ready(function() {
	$("#Result").tablesorter(); 
	$("#calender").click(function(){$("#paid_date").datepicker('show');});
	$("#paid_date").datepicker();
});
function ajax_load(id,url) {
//	$.blockUI({ message: '<h1>Talking to Server.<br/> please wait...</h1>' });		
	$.ajax({
		url: url,
		async: false,
		timeout: 20000,
		success: function(data) {
			$(id).html(data);
		},
		error: function(xhr, textStatus, errorThrown){
		   alert('request failed! Please try again.');
		}		  
	});		
//	$(document).ajaxStop($.unblockUI);	
}
function Action(this_id,amount,add) {
	var paid_status = $('input:radio[name=paid_status]:checked').val();
	var paid = 0;
	if(amount !=0) {
		if(add) {
		  total = total + parseFloat(amount);
		  paid = 3;
		}
		else {
		  total = total - parseFloat(amount);
		}
	}
	$("#total").text("$"+total.toFixed(2));
	if(this_id==0) action = "display"; else  action = "update";
	var bank_id = document.getElementById('bank_id').value;
	var url ="ModifyPaidStatus?id="+this_id+"&amount="+amount+"&paid="+paid+"&bank_id="+bank_id+"&action="+action+"&paid_status="+paid_status;
	ajax_load("#Result",url);
}
function Action_Final() {
	var paid_status = $('input:radio[name=paid_status]:checked').val();
	var paid_date = encodeURIComponent(document.getElementById('paid_date').value);
	var infor = document.getElementById('paid_date').value;
	var bank_id = document.getElementById('bank_id').value;
	var url ="ModifyPaidStatus?paid=1&paid_date="+paid_date+"&bank_id="+bank_id+"&paid_status="+paid_status;
	var ans=confirm( 'Are you sure want to paid at '+infor+'?');
	if(ans) {
		ajax_load("#Result",url);
	}	
}
function DispDate(id) {
	var mydate= new Date();
	document.getElementById('paid_date').value = ("0" + (mydate.getMonth() + 1)).slice(-2)+"/"+("0" + mydate.getDate()).slice(-2)+"/"+mydate.getFullYear();
}

