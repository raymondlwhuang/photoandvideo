app.directive('ngIf', function() {
    return {
        link: function(scope, element, attrs) {
            if(scope.$eval(attrs.ngIf)) {
                // remove '<div ng-if...></div>'
                element.replaceWith(element.children())
            } else {
                element.replaceWith(' ')
            }
        }
    }
});
app.filter('nospace', function () {
    return function (value) {
        return (!value) ? '' : value.replace(/ /g, '');
    };
});
app.controller("trackerCtrl", function($scope) 
{
	$scope.newRecord={},
	$scope.repeatRecords=[],
	//$scope.isHide=true,
	$scope.filterPayBy='',
	$scope.filterSpender='',
	$scope.filterPaid='',
	$scope.Spender=[],
	$scope.amountGoingToPaid=0,
	$scope.items=
	[
		{
			name:'Category',
			grounp:2,
			desc:[
			{name:'Income',type:'Income'},
			{name:'Transfer',type:'Transfer'},
			{name:'Home',type:'Expense'},
			{name:'Auto',type:'Expense'},
			{name:'Utilities',type:'Expense'},
			{name:'Personal',type:'Expense'},
			{name:'Activities',type:'Expense'},
			{name:'Food',type:'Expense'},
			{name:'Kids',type:'Expense'}
			]
		},
		{
			name:'Description',
			grounp:3,
			desc:
			[
				{id:1,detail:'Salary',type:'Income'},
				{id:2,detail:'Wage',type:'Income'},
				{id:3,detail:'Bonus',type:'Income'},
				{id:4,detail:'Expense Reimbursemments',type:'Income'},
				{id:5,detail:'Rental',type:'Income'},
				{id:6,detail:'Interest earned',type:'Income'},
				{id:7,detail:'Dividends/Capital Gains',type:'Income'},
				{id:8,detail:'Lottery',type:'Income'},
				{id:9,detail:'Tax return',type:'Income'},
				{id:10,detail:'Refund',type:'Income'},
				{id:11,detail:'Rental',type:'Income'},
				{id:12,detail:'Others',type:'Income'},
				{id:13,detail:'Transfer',type:'Transfer'},
				{id:14,detail:'Rent/Mortgage',type:'Home'},
				{id:15,detail:'Insurance',type:'Home'},
				{id:16,detail:'Maintenance',type:'Home'},
				{id:17,detail:'Furniture',type:'Home'},
				{id:18,detail:'Property taxes',type:'Home'},
				{id:19,detail:'Car Payment',type:'Auto'},
				{id:20,detail:'Fuel',type:'Auto'},
				{id:21,detail:'Insurance',type:'Auto'},
				{id:22,detail:'Maintenance',type:'Auto'},
				{id:23,detail:'Registration',type:'Auto'},
				{id:24,detail:'Parking',type:'Auto'},
				{id:25,detail:'Toll Fee',type:'Auto'},
				{id:26,detail:'Bus/Taxi',type:'Auto'},
				{id:27,detail:'Others',type:'Auto'},
				{id:28,detail:'Gas',type:'Utilities'},
				{id:29,detail:'Phone',type:'Utilities'},
				{id:30,detail:'Internet',type:'Utilities'},
				{id:31,detail:'TV',type:'Utilities'},
				{id:32,detail:'Electric/Water',type:'Utilities'},
				{id:33,detail:'Others',type:'Utilities'},
				{id:34,detail:'Hair care',type:'Personal'},
				{id:35,detail:'Skin care',type:'Personal'},
				{id:36,detail:'Entertainment product',type:'Personal'},
				{id:37,detail:'Clothings',type:'Personal'},
				{id:38,detail:'Others',type:'Personal'},
				{id:39,detail:'Membership',type:'Activities'},
				{id:40,detail:'Vacation',type:'Activities'},
				{id:41,detail:'Charitable',type:'Activities'},
				{id:42,detail:'Entertainment',type:'Activities'},
				{id:43,detail:'Gifts',type:'Activities'},
				{id:44,detail:'Others',type:'Activities'},
				{id:45,detail:'Grocery',type:'Food'},
				{id:46,detail:'Dinning',type:'Food'},
				{id:47,detail:'Others',type:'Food'},
				{id:48,detail:'Tuition',type:'Kids'},
				{id:49,detail:'Books',type:'Kids'},
				{id:50,detail:'Child care',type:'Kids'},
				{id:51,detail:'School Expense',type:'Kids'},
				{id:52,detail:'Others',type:'Kids'},
			]
		},
		{
			name:'Pay By',
			grounp:1,
			desc:['MarsterCard','Cash','VISA','AMX','Cheque','Capital One','Chase','Citi','Discover','First PREMIER','Others']
		},
		{
			name: 'Status',
			grounp: 4,
			desc: 
			[
				{name:'Pay now',val:0},
				{name:'Daily',val:1},
				{name:'Weekly',val:7},
				{name:'Bi-Weekly',val:14},
				{name:'Monthly',val:30},
				{name:'Quartery',val:91},
				{name:'Semi-anually',val:183},
				{name:'Yearly',val:365}
			]
		}
	],
	$scope.title=[],
	$scope.PayBy=[],
	$scope.details=[],
	$scope.summary=[],
	$scope.digits=[0,1,2,3,4,5,6,7,8,9],
	$scope.monthlist = [1,2,3,4,5,6,7,8,9,10,11,12],
	$scope.monthName = ['January','Febulary','March','April','May','June','July','Augest','Setember','October','November','December'],
	$scope.dayRange=[100,10,1],
	$scope.range=[10000,1000,100,10,1],
	$scope.cents=[0.1,0.01],
	$scope.type = 'Activities',
	$scope.amount=0,
	$scope.addItem=function(itemName){
		$scope.newItem='',
		$scope.itemName=itemName,
		$scope.isDialog=true,		
		$scope.isPayStatusSelected = false,
		$scope.isPayStatusSelected=false,
		$scope.isCategorySelected=false,
		$scope.isPayStatusSelected=false;

		if(itemName=='Category')	$scope.isCategorySelected=true;
		if(itemName=='Description')	{
			var e=document.getElementById('Category');
			$scope.expenseName=e.options[e.selectedIndex].text;
		}
		if(itemName=='Status') $scope.isPayStatusSelected=true;
	},
	$scope.saveItem=function(){
		if($scope.newItem=='') {
			alert('Please input an item!');
			return;
		}
		for(x in $scope.items){
			if($scope.items[x].name==$scope.itemName) {
				$scope.isDialog=false;
				switch($scope.itemName) {
					case "Category":
						$scope.items[x].desc.push({name:$scope.newItem,type:$scope.expenseType});
						break;
					case "Description":
						$scope.items[x].desc.push({id:$scope.items[x].desc.length,detail:$scope.newItem,type:$scope.expenseName});
						break;
					case "Status":
						$scope.items[x].desc.push({name:$scope.newItem,val:$scope.days});
						break;
					default:
						$scope.items[x].desc.push($scope.newItem);
				}
			}
		}
		localStorage.setItem("items",angular.toJson($scope.items));
	},
	$scope.removeItem=function(itemName,$event) {
		itemName=itemName.replace(/ /g, '');
		var e=document.getElementById(itemName);
		for(x in $scope.items){
			if($scope.items[x].name.replace(/ /g, '')==itemName) {
				var answer = confirm("Are you sure?");
				if(answer) {
					if('Description'==itemName) {
						var id = parseInt(e.options[e.selectedIndex].id.trim('desc').substring(4));
						$scope.items[x].desc.splice(id, 1);
					}
					else {
						if('Category'==itemName) {
							for(y in $scope.items[x].desc){
								if($scope.items[x].desc[y].name==e.options[e.selectedIndex].value) {
									$scope.items[x].desc.splice(y, 1);
								}
							}
							setTimeout(function() {e.options[1].selected=true;$scope.init();}, 100);
						}
						else
							$scope.items[x].desc.splice(e.selectedIndex, 1);
					}
					localStorage.setItem("items",angular.toJson($scope.items));
				}
			}
		}

	},
	$scope.change=function(){
		var e = document.getElementById("Category");
		$scope.type = e.options[e.selectedIndex].value;
	},
	$scope.byRange = function (fieldName, minValue, maxValue) {
	  if (minValue === undefined) minValue = Number.MIN_VALUE;
	  if (maxValue === undefined) maxValue = Number.MAX_VALUE;
		
	  return function predicateFunc(item) {
		return minValue <= item[fieldName] && item[fieldName] <= maxValue;
	  };
	},	
	$scope.byField = function (fieldName,searchText) {
	  return function predicateFunc(item) {
		if(searchText=='') return true;
		return searchText == item[fieldName];
	  };
	},	
	$scope.greaterThan = function (fieldName, minValue) {
	  if (minValue === undefined) minValue = Number.MIN_VALUE;
		
	  return function predicateFunc(item) {
		return minValue <= item[fieldName];
	  };
	},	
	$scope.getAmount=function(digit) {
		var amount=((document.getElementById('amount').value * 1000 + digit) / 100).toFixed(2);
		document.getElementById('amount').value = amount;
		$scope.amount=amount;
	},
	$scope.back=function() {
		var amtstring = (document.getElementById('amount').value * 100).toString();
		var amount=(amtstring.substr(0,amtstring.length - 1) / 100).toFixed(2);
		document.getElementById('amount').value = amount;
		$scope.amount=amount;
	},
	$scope.swapView=function(){
		var text = jQuery('.swap').text();
		jQuery('.swap').text(text == "View Repeating Records" ? "View Daily Records" : "View Repeating Records");	
		jQuery('.details').fadeToggle();
		jQuery('.repeatRecords').fadeToggle();
	},
	$scope.getDays=function() {
		d0=document.getElementById('days0');
		days = parseInt(d0.options[d0.selectedIndex].value);
		d1=document.getElementById('days1');
		days += parseInt(d1.options[d1.selectedIndex].value);
		d2=document.getElementById('days2');
		days += parseInt(d2.options[d2.selectedIndex].value);
		$scope.days=days;
	},
	$scope.showCol=function() {
		setTimeout(function() {
			for(x in $scope.title) {
				var thisCol='.col'+$scope.title[x].name.replace(/ /g, '');
				if($scope.title[x].hidden)
					jQuery(thisCol).addClass('hidden');
				else
					jQuery(thisCol).removeClass('hidden');
			}
			$scope.$apply(); //this triggers a $digest
		}, 100);	
	},
	$scope.showDetail=function($event) {
		jQuery($event.target).toggleClass('plus minus')
		$scope.showCol();
		//$scope.isHide=!$scope.isHide;
		$('.hide').toggle();
		if($scope.amountGoingToPaid!=0) {
			$scope.msg='Total amount paid:' +$scope.amountGoingToPaid;
			document.getElementById('paid').style.display='inline-block';
		}
		else {
			$scope.msg='';
			document.getElementById('paid').style.display='none';
		}
		
	},
	$scope.hideCol=function(x) {
		var e = '.col'+x.replace(/ /g, '');
		for(y in $scope.title) {
			if($scope.title[y].name==x) {
				$scope.title[y].hidden = !$scope.title[y].hidden;
				localStorage.setItem("title",angular.toJson($scope.title));
				jQuery(e).toggleClass('hidden');
				break;
			}
		}
	},
	$scope.filterChanged=function(dirction){
		d=new Date($scope.filterYear,$scope.filterMonth,$scope.filterDay);
		$scope.filterStartDate=d.getTime();
		ed=new Date($scope.filterEndYear,$scope.filterEndMonth,$scope.filterEndDay);
		$scope.filterEndDate=ed.getTime();
	},
	$scope.newPage=function(dirction,flag){
		if(flag==0) {
			d=new Date($scope.filterStartDate);
			if(dirction==1) {
				d.setMonth(d.getMonth()+1);
				$scope.filterStartDate=d.getTime();
				$scope.filterYear = d.getFullYear(),
				$scope.filterMonth = d.getMonth() + 1,
				$scope.filterDay = d.getDate();
			}
			else {
				d.setMonth(d.getMonth()-1);
				$scope.filterStartDate=d.getTime();
				$scope.filterYear = d.getFullYear(),
				$scope.filterMonth = d.getMonth() + 1,
				$scope.filterDay = d.getDate();
			}
		}
		else {
			d=new Date($scope.filterEndDate);
			if(dirction==1) {
				ed=new Date(d.getFullYear(),d.getMonth(),1);
				ed.setMonth(ed.getMonth()+2);
				ed.setDate(ed.getDate()-1),
				$scope.filterEndDate=ed.getTime();
				$scope.filterEndYear = ed.getFullYear(),
				$scope.filterEndMonth = ed.getMonth() + 1,
				$scope.filterEndDay = ed.getDate();
			}
			else {
				ed=new Date(d.getFullYear(),d.getMonth(),1);
				ed.setDate(ed.getDate()-1),
				$scope.filterEndDate=ed.getTime();
				$scope.filterEndYear = ed.getFullYear(),
				$scope.filterEndMonth = ed.getMonth() + 1,
				$scope.filterEndDay = ed.getDate();
			}
		}

	},
	$scope.addCheck=function($event) {
		if($event.which==13)
			$scope.addDetail();
	};
	$scope.addDetail=function(){
		if(isNaN($scope.amount) || $scope.amount == null || $scope.amount == '' || parseFloat($scope.amount)==0) {
			$scope.amount=0;
			document.getElementById('amount').focus();
			$scope.msg= "Amount is required!";
			return false;
		}
		$scope.msg= "";
		for(x in $scope.title) {
			var thisCol='.col'+$scope.title[x].name.replace(/ /g, '');
			jQuery(thisCol).removeClass('hidden');
		}
		//$scope.isHide=true;
		var recordType=0;
		var d = new Date(), rd = new Date(), year = d.getFullYear(), month=d.getMonth(),	day=d.getDate(), hours=d.getHours(), minutes=d.getMinutes(), seconds=d.getSeconds();
		sd=new Date(year,month,1),
		sd.setMonth(sd.getMonth());
		$scope.filterStartDate=sd.getTime(),
		$scope.filterYear=year,
		$scope.filterMonth=month+1,
		$scope.filterDay=1,
		ed=new Date(year,month,1),
		ed.setMonth(ed.getMonth()+1),
		ed.setDate(ed.getDate()-1);
		$scope.filterEndDate=ed.getTime(),
		$scope.filterEndYear=ed.getFullYear(),
		$scope.filterEndMonth=ed.getMonth()+1,
		$scope.filterEndDay=ed.getDate(),
		amount=parseFloat(document.getElementById("amount").value).toFixed(2);
		d = new Date($scope.year,($scope.month-1),$scope.day);
		rd= new Date($scope.year,($scope.month-1),$scope.day);
		d.setHours(d.getMinutes() + hours);
		d.setMinutes(d.getMinutes() + minutes);
		d.setSeconds(d.getMinutes() + seconds);
		$scope.newRecord={Date:d.getTime()};
		$scope.newRecord['Uploaded']=false;
		e=document.getElementById('Status');
		var daysToRepeat=parseInt(e.options[e.selectedIndex].value);
		if(daysToRepeat>0) {
			rd.setDate(rd.getDate()+daysToRepeat);
			$scope.repeatRecord={Date:rd.getTime()};
			$scope.repeatRecord['Amount']=amount;
			$scope.repeatRecord['daysToRepeat']=daysToRepeat;
			$scope.repeatRecord['Uploaded']=false;
		}
		for(x in $scope.items) {
			item=document.getElementById($scope.items[x].name.replace(/ /g, ''));
			$scope.newRecord[$scope.items[x].name]=item.options[item.selectedIndex].text;
			if(daysToRepeat >0) {
				$scope.repeatRecord[$scope.items[x].name]=item.options[item.selectedIndex].text;
			}
			if($scope.items[x].name=='Pay By') {
				if(item.options[item.selectedIndex].text=='Cash') {
					$scope.newRecord['Paid'] = 1;
					if(daysToRepeat>0)
					{
						$scope.repeatRecord['Paid'] = 1;
					}
				}
				else {
					$scope.newRecord['Paid'] = 0;
					if(daysToRepeat>0) {
						$scope.repeatRecord['Paid'] = 0;
					}
				}
			}
		}
		$scope.newRecord['Amount']=amount;
		$scope.details.push($scope.newRecord);
		if(daysToRepeat>0) {
			$scope.repeatRecords.push($scope.repeatRecord);
		}
		e=document.getElementById("Description");
		recordType=e.options[e.selectedIndex].value;
		$scope.createSummary(amount,recordType,$scope.month);
		
		
		
		d = new Date(), year = d.getFullYear(), month=d.getMonth(),day=d.getDate();
		$scope.year = d.getFullYear(),
		$scope.month=d.getMonth()+1,
		$scope.day=d.getDate(),
		$scope.amount=0;
		localStorage.setItem("newRecord",angular.toJson($scope.newRecord));
		localStorage.setItem("details",angular.toJson($scope.details));
		localStorage.setItem("repeatRecords",angular.toJson($scope.repeatRecords));
		localStorage.setItem("summary",angular.toJson($scope.summary));
		$scope.autoAdd();
		$('#Status').val(0);
		$("select[id^='amt']").val(0);
		$("select[id^='cent']").val(0);
		jQuery('.newRecord').css('height','auto');
	},
	$scope.uploadDetail=function() {
		$.ajax({
			url: uploaddetail,   
			type: 'POST',   
			contentType: 'application/json',
			data: { "summary":angular.toJson($scope.summary), "details":angular.toJson($scope.details) },
			success: function(msg) {
				alert(msg);
			}
		});	
	},
	$scope.createSummary=function(amount,recordType,month) {
		for(x in $scope.summary) {
			if($scope.summary[x].Year==$scope.year) {
				if(recordType=='Income') {
					$scope.summary[x].Income+=parseFloat(amount);
					$scope.summary[x].Month[month-1].Income.total+=parseFloat(amount);
					if($scope.summary[x].Month[month-1].Income[e.options[e.selectedIndex].text])
						$scope.summary[x].Month[month-1].Income[e.options[e.selectedIndex].text]= parseFloat($scope.summary[x].Month[month-1].Income[e.options[e.selectedIndex].text]) + amount;
					else
						$scope.summary[x].Month[month-1].Income[e.options[e.selectedIndex].text]=parseFloat(amount);
				}
				else if(recordType=='Transfer') {
					$scope.summary[x].Transfer+=parseFloat(amount);
					$scope.summary[x].Month[month-1].Transfer.total+=parseFloat(amount);
					if($scope.summary[x].Month[month-1].Transfer[e.options[e.selectedIndex].text])
						$scope.summary[x].Month[month-1].Transfer[e.options[e.selectedIndex].text]=parseFloat($scope.summary[x].Month[month-1].Transfer[e.options[e.selectedIndex].text]) + amount;
					else
						$scope.summary[x].Month[month-1].Transfer[e.options[e.selectedIndex].text]=parseFloat(amount);
					
				}
				else {
					$scope.summary[x].Expense+=parseFloat(amount);
					$scope.summary[x].Month[month-1].Expense.total+=parseFloat(amount);
					if($scope.summary[x].Month[month-1].Expense[e.options[e.selectedIndex].text])
						$scope.summary[x].Month[month-1].Expense[e.options[e.selectedIndex].text] = parseFloat($scope.summary[x].Month[month-1].Expense[e.options[e.selectedIndex].text]) + parseFloat(amount);
					else
						$scope.summary[x].Month[month-1].Expense[e.options[e.selectedIndex].text]=parseFloat(amount);
				}
			
			}
		}
	
	},
	$scope.autoAdd=function() {
		var d=new Date(), newRecord2={};
		for(x in $scope.repeatRecords) {
			if($scope.repeatRecords[x].Date<=d.getTime()) {
				newRecord2=angular.copy($scope.repeatRecords[x]);
				delete newRecord2.daysToRepeat;
				rd = new Date(parseFloat($scope.repeatRecords[x].Date));
				var month = rd.getMonth()+1;
				rd.setDate(rd.getDate()+$scope.repeatRecords[x].daysToRepeat);
				$scope.repeatRecords[x].Date = rd.getTime();
				amount=$scope.repeatRecords[x].Amount;
				recordType=$scope.repeatRecords[x].Category;
				$scope.createSummary(amount,recordType,month);
				$scope.details.push(newRecord2);
			}
		}
		localStorage.setItem("details",angular.toJson($scope.details));
		localStorage.setItem("repeatRecords",angular.toJson($scope.repeatRecords));
		localStorage.setItem("summary",angular.toJson($scope.summary));
		jQuery('.newRecord').css('display','inline-block').find('li').toggleClass('redBorder');
	},
	$scope.paidConfirm=function() {
		$scope.amountGoingToPaid = 0;
		$scope.msg='Total amount paid:' +$scope.amountGoingToPaid;
		localStorage.setItem("amountGoingToPaid",$scope.amountGoingToPaid.toString());
	}
	$scope.paidChange=function(date) {
		for(i in $scope.details) {
			if($scope.details[i].Date==date) {
				if($scope.details[i].Paid==0) {
					$scope.details[i].Paid=1;
					$scope.amountGoingToPaid+=parseFloat($scope.details[i].Amount);
				}
				else {
					$scope.details[i].Paid=0;
					$scope.amountGoingToPaid-=parseFloat($scope.details[i].Amount);
				}
				break;
			}
		}
		if($scope.amountGoingToPaid!=0) {
			$scope.msg='Total amount paid:' +$scope.amountGoingToPaid;
			document.getElementById('paid').style.display='inline-block';
		}
		else {
			$scope.msg= "";
			document.getElementById('paid').style.display='none';
		}
		localStorage.setItem("amountGoingToPaid",$scope.amountGoingToPaid.toString());
		localStorage.setItem("details",angular.toJson($scope.details));
		localStorage.setItem("repeatRecords",angular.toJson($scope.repeatRecords));
	},
	$scope.deleteRepeatRecords=function(date) {
		for(i in $scope.repeatRecords) {
			if($scope.repeatRecords[i].Date==date) {
				$scope.repeatRecords.splice(i, 1);
				localStorage.setItem("repeatRecords",angular.toJson($scope.repeatRecords));
				break;
			}
		}
	},
	$scope.deleteDetail=function(date) {
		for(i in $scope.details) {
			if($scope.details[i].Date==date) {
				d=new Date(date),year = d.getFullYear(), month=d.getMonth(),day=d.getDate();
				for(x in $scope.summary) {
					if($scope.summary[x].Year == year) {
						if($scope.details[i].Category=='Income')
							$scope.summary[x].Income -= $scope.details[i].Amount;
						else if($scope.details[i].Category=='Transfer')
							$scope.summary[x].Transfer -= $scope.details[i].Amount;
						else
							$scope.summary[x].Expense -= $scope.details[i].Amount;
							
						for(y in $scope.summary[x].Month) {
							if(y==month) {
								if($scope.details[i].Category=='Income') {
									for(z in $scope.summary[x].Month[y].Income) {
										if($scope.details[i].Description==z)
											$scope.summary[x].Month[y].Income[z] -= $scope.details[i].Amount;
									}
									$scope.summary[x].Month[y].Income['total'] -= $scope.details[i].Amount;
								}
								else if($scope.details[i].Category=='Transfer') {
									for(z in $scope.summary[x].Month[y].Transfer) {
										if($scope.details[i].Description==z)
											$scope.summary[x].Month[y].Transfer[z] -= $scope.details[i].Amount;
									}
									$scope.summary[x].Month[y].Transfer['total'] -= $scope.details[i].Amount;
								}
								else {
									for(z in $scope.summary[x].Month[y].Expense) {
										if($scope.details[i].Description==z)
											$scope.summary[x].Month[y].Expense[z] -= $scope.details[i].Amount;
									}
									$scope.summary[x].Month[y].Expense['total'] -= $scope.details[i].Amount;
								}
								
							}
						
						}
					}
				}
				$scope.deleteFinished(i);
				break;
			}
		}
	},
	$scope.deleteFinished=function(x) {
		$scope.details.splice(x, 1);
		localStorage.setItem("details",angular.toJson($scope.details));
		localStorage.setItem("summary",angular.toJson($scope.summary));
	},
	$scope.init=function(){
		//localStorage.removeItem("summary");
		//localStorage.removeItem("items");
		//localStorage.removeItem("title");
		//localStorage.removeItem("newRecord");
		//localStorage.removeItem("details");
		//localStorage.removeItem("repeatRecords");
		//localStorage.clear();
		var d = new Date(), year = d.getFullYear(), month=d.getMonth(),day=d.getDate();
		$scope.year = d.getFullYear(),
		$scope.viewMonth=$scope.monthName[d.getMonth()];
		$scope.month=d.getMonth()+1,
		$scope.day=d.getDate(),
		
		$scope.yearRange = [year-5,year-4,year-3,year-2,year-1,year,year+1], 
		$scope.filterYear = year,
		$scope.filterMonth = month + 1,
		$scope.filterDay = 1;
		d=new Date(year,month,1);
//		d.setMonth(d.getMonth()+1),
		$scope.filterStartDate=d.getTime();
		ed=new Date(year,month,1);
		ed.setMonth(ed.getMonth()+1),
		ed.setDate(ed.getDate()-1),
		$scope.filterEndYear = ed.getFullYear(),
		$scope.filterEndMonth = ed.getMonth() +1,
		$scope.filterEndDay = ed.getDate(),
		$scope.filterEndDate=ed.getTime();
		var newTitle={name:'Date',hidden:false};
		$scope.title.push(newTitle);
		newTitle={name:'Uploaded',hidden:true};
		$scope.title.push(newTitle);
		for(x in $scope.items) {
		  if($scope.items[x].name=='Category'||$scope.items[x].name=='Description'||$scope.items[x].name=='Pay By')
			newTitle={name:$scope.items[x].name,hidden:false};
		  else
			newTitle={name:$scope.items[x].name,hidden:true};
		  $scope.title.push(newTitle);
		  if($scope.items[x].name=='Pay By') {
			for(y in $scope.items[x].desc) {
				$scope.PayBy.push($scope.items[x].desc[y]);
			}
		  }
		  else if($scope.items[x].name=='Spender') {
			for(y in $scope.items[x].desc) {
				$scope.Spender.push($scope.items[x].desc[y]);
			}
		  
		  }
		}
		newTitle={name:'Amount',hidden:false};
		$scope.title.push(newTitle);
		newTitle={name:'Paid',hidden:false};
		$scope.title.push(newTitle);
		var newSummary=
		{
			Year:year,
			Expense:0,
			Income:0,
			Transfer:0,
			Month:[
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}},
			{Expense:{total:0}, Income:{total:0}, Transfer:{total:0}}
			]
		};
		if(localStorage.getItem('summary')) $scope.summary=JSON.parse(localStorage.getItem('summary'));
		var found=false;
		for(x in $scope.summary) {
			if($scope.summary[x].Year==year) found=true;
		}
		
		if(!found) {
			$scope.summary.push(newSummary);
			localStorage.setItem("summary",angular.toJson($scope.summary));
		};
		$scope.thisYear=$scope.summary[$scope.summary.length-1];
		$scope.thisMonth=month+1;
		
		if(localStorage.getItem('amountGoingToPaid')) $scope.amountGoingToPaid=parseFloat(localStorage.getItem('amountGoingToPaid'));
		if(localStorage.getItem('title')) $scope.title=JSON.parse(localStorage.getItem('title'));
		if(localStorage.getItem('newRecord')) $scope.newRecord=JSON.parse(localStorage.getItem('newRecord'));
		if(localStorage.getItem('items')) $scope.items=JSON.parse(localStorage.getItem('items'));
		if(localStorage.getItem('details')) $scope.details=JSON.parse(localStorage.getItem('details'));
		if(localStorage.getItem('repeatRecords')) $scope.repeatRecords=JSON.parse(localStorage.getItem('repeatRecords'));
		if($scope.details.length > 0) {
			jQuery('.newRecord').css('height','auto');
		}
		else
			jQuery('.newRecord').css('height',0);
		$scope.isMobile=mobilecheck();
		setTimeout(function() {
			$scope.change();
			$scope.showCol();
			$scope.$apply(); //this triggers a $digest
		}, 100);	
		
	};
	$scope.init();
});