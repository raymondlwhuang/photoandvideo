var infor_id= {
"AddBank": { "id":7 , "text":"Account Name Or Create" },
"AddCategory": { "id":2 , "text":"New Category" },
"AddType": { "id":5 , "text":"New Payment Type" },
"AddItem": { "id":3 , "text":"New Item Description" },
"AddSpender": { "id":1 , "text":"New Spender Name" },
"AddAutoRec": { "id":8 , "text":"Set New amount" }
},CallID=0,text="";
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
$(document).ready(function() {
	$("#balance").text(parseFloat(optionBalance[$("#bank_id").val()]).toFixed(2));
	$("#frequency_id").change(function(){DispDate();});
	$("#reminder").change(function(){SetRminder();});
	$("#Yearly" ).change(function(){Action('yearly');});
	$("#spender_id").change(function(){
		$("#category_id").trigger("change");
	});
	$("#type_id").change(function(){
		if($("#type_id").val()==1) {
			if($("#spender_id").val()==1) $("#bank_id").val('1');
			else if($("#spender_id").val()==2) $("#bank_id").val('2');
			else  $("#bank_id").val('3');
		}
		else $("#bank_id").val('3');
		$("#balance").text(parseFloat(optionBalance[$("#bank_id").val()]).toFixed(2));
	});
	$("#bank_id").change(function(){$("#balance").text(parseFloat(optionBalance[$(this).val()]).toFixed(2));});
	$("#MonthlyExpenese" ).change(function(){$('#MonthlyIncome').val($("#MonthlyExpenese" ).val());Action('Disp');});
	$("#MonthlyIncome" ).change(function(){$('#MonthlyExpenese').val($("#MonthlyIncome" ).val());Action('Disp');});
	$('#category_id').change(function() {
		var category_id = $("#category_id").val();
		var detail="",total=0;
		$('#item_id').html('');
		for (var i = 0; i < ItemResult.length; i++) { 
			if(category_id==ItemResult[i].category_id) {
				if(ItemResult[i].default_value==1) {
					$('#item_id').append($('<option>'+ItemResult[i].category_item+'</option>').attr({value: ItemResult[i].id, selected: "selected"}));
				}
				else {
					$("#item_id").append($('<option>'+ItemResult[i].category_item+'</option>').attr("value", ItemResult[i].id));
					}
			}
		}
	    if(category_id==1) $("#type_id").val(1);
		$("#type_id").val(defaultPayType[$(this).val()]);
		if(category_id==1) $("#bank_id").val("3");
		else {
			if($("#type_id").val()!=1) $("#bank_id").val("3");
			else $("#bank_id").val($("#spender_id").val());
		}
		$("#balance").text(parseFloat(optionBalance[$("#bank_id").val()]).toFixed(2));
		$("#CategoryShow").html(optionCategory[category_id]);
		$.each(itemTotal, function(key, value) {
			if(category_id==key) {
			detail="<table>";
			total=0;
			$.each(value, function(key1, value1) {
				if(key1!=77) {
					detail+="<tr><td>"+optionItem[key1]+"</td><td>"+ value1+"</td></tr>";
					total+=value1;
				}
			});
			detail+="</table>";
			}
		});
		$("#Detail").html(detail);
		$("#total").html(parseFloat(total).toFixed(2));
	});
	$("#item_id").change(function(){
		if($(this).val()==77) {
			$('#float_right').css('visibility','visible');
			$('#bank_desc').text('From');
		}
		else {
			$('#float_right').css('visibility','hidden');
			$('#bank_desc').text('To');
		}
		$("#type_id").val(itemPayType[$(this).val()]);
		$("#bank_id").val(itemBank[$(this).val()]);
		$("#balance").text(parseFloat(optionBalance[$("#bank_id").val()]).toFixed(2));
	});
	$("#amount,select").click(function(){$('#keyboard').css('display','inline');});
	$("#calender" ).click(function(){$( "#start_date" ).datepicker();});
	$("#start_date" ).datepicker();
	$(".delete_reminder").click(function(){
		$("#reminder_id").val($(this).attr("value"));
		document.ReminderDel.submit();
	});
	$(".paynow").click(function(){
		var reminder_id=$(this).attr("id");
		var amount=$(this).attr("value");
		var url = "MakePayment?reminder_id="+reminder_id+"&amount="+amount;
		var answer = confirm("$"+amount+" will inserted\nAre you sure the amount is correct?")
		if (answer){
			ajax_load("#reminder_list",url);
			Action('Disp');
		}
	});
	$("#Home").click(function(){window.open('/',target='_top');});
	$("#account").click(function(){window.open('CreditPayOff',target='_top');});
	$("#pid").click(function(){window.open('getPin',target='_top');});
	$("#Save").click(function(){answer=confirm('Are you sure you want to save?');if(answer) Action('Save');$('#keyboard').css('display','none');});
	$("#Logout").click(function(){window.open('Signout',target='_top');});
    setTimeout(function() {$("#category_id").trigger('change');},10);
	$("#0,#1,#2,#3,#4,#5,#6,#7,#8,#9").click(function(){$("#amount,.paynow,.reminder_amount").val(parseFloat(parseInt(($("#amount").val() * 1000) + parseInt($(this).attr("id")))/100).toFixed(2));	});
	$("#subtract").click(function(){$("#amount").val($("#amount").val()*-1);});
	$("#backward").click(function(){$("#amount").val($("#amount").val().substr(0,$("#amount").val().length-1));$("#amount").val(parseFloat($("#amount").val()/10).toFixed(2));});
	$("#hide").click(function(){$('#keyboard').css('display','none');});
	$(".close").click(function(){$('#AddDialog').toggle();$('#main').toggle();});
	$("#insert,#AddBank,#AddCategory,#AddType,#AddItem,#AddSpender,#AddAutoRec").click(function(){
		$('#AddDialog').toggle();
		$('#main').toggle();
		if($(this).attr("id")=="insert") {
			if(CallID!=8 && $('#input_text').val()=="") {}
			else
				AddInfor();
		}
	});
	$("#AddBank,#AddCategory,#AddType,#AddItem,#AddSpender,#AddAutoRec").click(function(){
		CallID=infor_id[$(this).attr("id")].id;
		if(CallID==8)
			text=$("#item_frequency_id option:selected").text();
		else
			text=infor_id[$(this).attr("id")].text;
		SetDialog();
	});
	$("#DeleteSpender").click(function(){
		var text = $("#spender_id  option:selected").text();
		var value = $("#spender_id  option:selected").val();
		if(text!="") {
			var ans=confirm('You will lose information for "' + text + '" and might lead to incorect data!\nAre you sure you want to remove "' + text + '" from the spender list?');
			var url = 'RemoveSpender?spender_id='+value;
			if(ans) {
				ajax_load("#spender_id",url);
			}
		}
	});
	$("#DeleteAutoRec").click(function(){
		var infor = $('#item_frequency_id option:selected').text();
		var item_frequency_id = $('#item_frequency_id option:selected').val();
		if(infor!="") {
			var ans=confirm( 'You are going to remove this record:'+infor+'\nFrom the system. Are you sure want to do this?');
			url = "ModifyFrequency?item_frequency_id="+item_frequency_id+"&delete=1";
			if(ans) {
				ajax_load("#item_frequency_id",url);
			}		
		}
	});
});
function assign_value(v) {
	$('#reminder_id').val(v);
	document.ReminderDel.submit();
}
function SetVisibleDiv(disp) {
	$('#main').css('display',disp);
}
function SetDialog() {
	$('#dialog_text').text(text);
	if(CallID == 7) {
			$('#input_text').css("display","block").val($("#bank_id option:selected").text());
			$('#adj_title').text('Ajusting Amount');
			var myString = parseFloat(optionBalance[$("#bank_id option:selected").val()]).toFixed(2);
			$("#input_amount").val(myString.substr(myString.indexOf("$") + 1));
			$("#adjustment").css("display","block");
	}
	else if(CallID == 8) {
		if($("#item_frequency_id").val()) {
			$("#input_text").css("display","none");
			$('#adj_title').text('***Reset Amount to***');
			$("#input_amount").val(0);
			$("#adjustment").css("display","block");
		}
		else $('#main').toggle();
	}
	else  {
		$('#input_text').val("").css("display","block");
		$("#adjustment").css("display","none");
	}
}

function AddInfor() {
	var category_id = $('#category_id').val();
	var item_id = $('#item_id').val();
	var spender_id = $('#spender_id').val();
	var adjustment = $('#input_amount').val();
	var item_frequency_id = $('#item_frequency_id').val();
	var id = '#spender_id';
	text = encodeURIComponent($('#input_text').val());
		if(CallID == 1) {
		id = '#spender_id';
		url = 'AddSpender?name='+text;
		}
		else if(CallID == 2) {
		id = '#category_id';
		url = 'AddCategory?category='+text;
		}
		else if(CallID == 3) {
			id = '#item_id';
			url = 'AddItem?category_id='+category_id+'&category_item='+text;
		}
		else if(CallID == 4) {
			id = '#comment_id';
			url = 'AddComment?comment='+text+'&category_id='+category_id+'&item_id='+item_id;
		}
		else if(CallID == 5) {
			id = '#type_id';
			url = 'AddType?Type='+text;
		}
		else if(CallID == 6) {
			id = '#frequency_id';
			url = 'AddFrequency?frequency='+text;
		}
		else if(CallID == 7) {
			id = '#bank_id';
			url = "AddBank?spender_id="+spender_id+"&bank="+text+"&adjustment="+adjustment;
		}
		else if(CallID == 8) {
			id = '#item_frequency_id';
			url = "ModifyFrequency?item_frequency_id="+item_frequency_id+"&amount="+adjustment+"&delete=0";
		}
		ajax_load(id,url);
}
function ForceNumericInput(field,DotIncl) {
	if (DotIncl == true) {var regExpr = /^[0-9]*([\.]?)[0-9]*$/;} else var regExpr = /^[0-9]*$/;
	if (!regExpr.test(field.value)) {field.value = field.value.substr(0,field.value.length-1);}
}
var CallID = 0;
function setamount(number) {
	$('#amount').val($('#amount').val() + '' + number);
	var allTags = document.getElementsByClassName("reminder_amount");

	for (var i=0; i<allTags.length; i++) {
		document.getElementsByClassName("reminder_amount")[i].value = $('#amount').val(); 
	}	
}

function RemoveSpender() {
	var text = $('#spender_id').text();
	var value = $('#spender_id').val();
	var ans=confirm('You will lose information for ' + text.toUpperCase() + ' and might lead to incorect data!\nAre you sure you want to remove ' + text.toUpperCase() + ' from the spender list?');
	var url = 'RemoveSpender?spender_id='+value;
	if(ans) {
		$(document).ready(function() {
		   $("#spender_id").load(url);
		   $.ajaxSetup({ cache: false });
		});		
	} 
}
function RemovePayFrq() {
	var infor = $('#item_frequency_id').text();
	var item_frequency_id = $('#item_frequency_id').val();
	var ans=confirm( 'You are going to remove this record:'+infor+'\nFrom the system. Are you sure want to do this?');
	url = "ModifyFrequency?item_frequency_id="+item_frequency_id+"&delete=1";
	if(ans) {	
		$(document).ready(function() {
		   $('#item_frequency_id').load(url);
		   $.ajaxSetup({ cache: false });
		});
	}	
}
function SetDisp(AffectID) {
	var category_id = $('#category_id').val();
	var item_id = $('#item_id').val();
	var spender_id = $('#spender_id').val();
	var bank_id = $('#bank_id').val();
	var type_id = $('#type_id').val();
	if(AffectID == 3) {
		id = '#item_comment';
		url = 'ItemComment?category_id='+category_id+'&item_id='+item_id;
	}
	else if(AffectID == 4) {
		id = '#comment_id';
		url = 'AddComment?category_id='+category_id+'&item_id='+item_id;
	}
	else if(AffectID == 5) {
		id = '#balance';
		url = 'GetBalance?bank_id='+bank_id;
	}
	else if(AffectID == 6) {
		id = '#bank_balance';
		url = 'GetBank?category_id='+category_id+'&item_id='+item_id+'&bank_id='+bank_id+'&spender_id='+spender_id;
	}
	else if(AffectID == 7) {
		id = '#bank_balance';
		url = 'GetBank?category_id='+category_id+'&item_id='+item_id+'&type_id='+type_id+'&bank_id='+bank_id+'&spender_id='+spender_id;
	}
	$(document).ready(function() {
	   $(id).load(url);
	   $.ajaxSetup({ cache: false });
	});	
	if(category_id == 1) {
		if(item_id == 77){
			$('#float_right').css('visibility','visible');
			$('#bank_desc').html('From');
		}
		else {
			$('#float_right').css('visibility','hidden');
			$('#bank_desc').html('To');
		}	
	}
	else {
		$('#bank_desc').html('Will Pay From');
		$('#float_right').css('visibility','hidden');
	}	
}
function SetRminder() {
	var reminder = $("#reminder").val();
	var spender_id = $("#spender_id").val();
	var category_id = $("#category_id").val();
	var item_id = $("#item_id").val();
	var frequency_id = $("#frequency_id").val();
	var amount = $("#amount").val();
	var type_id = $("#type_id").val();
	var bank_id = $("#bank_id").val();
	if($('#to_bank').css('display')=='none') var to_bank = 0;
	else var to_bank = $("#to_bank").val();

	if(reminder!=1) {
		var answer = confirm("Are you sure?")
		if(answer) {
			$.blockUI({ message: '<h1>Setting reminder.<br/> please wait...</h1>' });
			$.ajax({ 
			   type: "POST", 
			   url: "SetReminder",
			   data: "spender_id="+spender_id+"&category_id="+category_id+"&item_id="+item_id+"&frequency_id="+frequency_id+"&amount="+amount+"&type_id="+type_id+"&bank_id="+bank_id+"&to_bank="+to_bank+"&reminder="+reminder, 
			   success: function(msg){ 
				  //Anything you want 
			   }, 
				error:function (xhr, ajaxOptions, thrownError){ 
							alert(xhr.status + " " + thrownError); 
				}     	   
			 }); 
			$(document).ajaxStop($.unblockUI);	
		 }
	 }
}

function EnDisableDiv() {
 toggleDisabled(document.getElementById("main"));
}
function toggleDisabled(el) {
	 try {
	 el.disabled = !el.disabled;
	 }
	 catch(E){
	 }
	 if (el.childNodes && el.childNodes.length > 0) {
		 for (var x = 0; x < el.childNodes.length; x++) {
		 toggleDisabled(el.childNodes[x]);
		 }
	 }
 }
function DispDate() {
	var id = $('#frequency_id').val();
	var mydate= new Date();
	if($('#frequency_id').val() == 1) {
		$('#Date').css('visibility','hidden');
		$('#start_date').val('');
	}
	else {
		$('#Date').css('visibility','visible');
		$('#start_date').val(("0" + (mydate.getMonth() + 1)).slice(-2)+"/"+("0" + mydate.getDate()).slice(-2)+"/"+mydate.getFullYear());
	}
} 


function BankingDetail() {
	var bank_id = $('#bank_id').val();
	window.open('BankingDetail?bank_id='+bank_id,target='_top');	
}




function Action(action) {
	if($('#start_date').css('display')=='block') {
		var date = Date.parse($('#start_date').val());
		var theDate = new Date(date); 
		var today = new Date();
		var checkdate1 = (today.getMonth()+1)+"/"+today.getDate() +"/"+today.getFullYear();
		var checkdate2 = Date.parse(checkdate1);
		var checkdate = new Date(checkdate2);
		var difference = theDate - checkdate;
		var days = Math.round(difference/(1000*60*60*24));	
		if(days < 0) {
			alert("Starting date must not in the pass");
			return false;
		}
	}
	var amount = $('#amount').val();
	if(amount != 0 || action != 'Save') {
		 var category_id = $('#category_id').val();
		 var item_id = $('#item_id').val();
		 var comment_id = $('#comment_id').val();
		 var spender_id = $('#spender_id').val();
		 var type_id = $('#type_id').val();
		 var bank_id = $('#bank_id').val();
		 var to_bank = $('#to_bank').val();
		 var frequency_id = $('#frequency_id').val();
		 var start_date = encodeURIComponent($('#start_date').val());
		 var year_month = $('#MonthlyExpenese').val();
		 var yearly = $('#Yearly').val();
		 if(action == 'Save' && bank_id == 0) $('#ErrorMessage').innerHTML = 'Please choice the Pay From option';
		 else {
			 $('#ErrorMessage').innerHTML = '';
			 if(action=='Account') var url ="CreditPayOff?category_id="+category_id+"&item_id="+item_id+"&comment_id="+comment_id+"&amount="+amount+"&spender_id="+spender_id+"&type_id="+type_id+"&frequency_id="+frequency_id+"&start_date="+start_date+"&bank_id="+bank_id+"&to_bank="+to_bank+"&year_month="+year_month+"&action="+action+"&yearly="+yearly;
			 else { 
				var url ="HERDisp?category_id="+category_id+"&item_id="+item_id+"&comment_id="+comment_id+"&amount="+amount+"&spender_id="+spender_id+"&type_id="+type_id+"&frequency_id="+frequency_id+"&start_date="+start_date+"&bank_id="+bank_id+"&to_bank="+to_bank+"&year_month="+year_month+"&action="+action+"&yearly="+yearly;
				ajax_load("#Result",url);
				$('#amount').val('');
				if(type_id==1) {
					if(category_id==1) {
						if(item_id==77) {
							optionBalance[to_bank]=parseFloat(optionBalance[to_bank])+parseFloat(amount);
							optionBalance[bank_id]=parseFloat(optionBalance[bank_id])-parseFloat(amount);
						}
						else optionBalance[bank_id]=parseFloat(optionBalance[bank_id])+parseFloat(amount);
					}
					else optionBalance[bank_id]=parseFloat(optionBalance[bank_id])-parseFloat(amount);
					$("#balance").text(parseFloat(optionBalance[bank_id]).toFixed(2));
				}
			}
		}
	}
	else $('#ErrorMessage').innerHTML = 'Please enter the amount!';
}
$(function(){ 
  $("#mytable").tablesorter(); 
});