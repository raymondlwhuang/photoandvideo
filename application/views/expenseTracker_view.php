<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Simple Expense Tracker</title>
<link rel="stylesheet" href="css/tracker.css">
<script src="js/utility.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular.min.js"></script>
<script src="js/trackerApp.js"></script>
<script src="js/trackerCtrl.js"></script>
</head>
<body>
<div class="main" ng-app="trackerApp" ng-controller="trackerCtrl">
	<div class="dialog" ng-show="isDialog">
		<h4>Add new item ({{itemName}})</h4>
		<button ng-click="saveItem()" class="save"></button>
		<button ng-click="isDialog=false" class="remove"></button>
		<input type="text" ng-model="newItem" />
		<div ng-show="isCategorySelected" class="addDesc">
			<label>Type: </label>
			<select  ng-init="expenseType='Expense'" ng-model="expenseType">
				<option value="Income">Income</option>
				<option value="Transfer">Transfer</option>
				<option value="Expense">Expense</option>
			</select>
		</div>
		<div ng-show="isPayStatusSelected" class="addPayStatus">
			<label>Repeat in</label>
			<select id="days{{$index}}" ng-repeat="x in dayRange" ng-change="getDays()" ng-init="c=digits[0]" ng-model="c">
				<option ng-repeat="digit in digits" value="{{digit * x}}">{{digit}}</option>
			</select>

			<label>Days</label>
			
		</div>
	</div>
	<div  ng-hide="isDialog">
		<div class="navigator" ng-class="{'active':show === true}" >
			<div class="plus" ng-click="show = true"></div>
			<div class="minus" ng-click="show = false"></div>
			<ul>
			<li class="yearSummary">
				<div>
					Year:<select ng-model="thisYear" ng-options="x.Year for x in summary"></select>
					<ul>
					<li>Income: <b>{{thisYear.Income}}</b></li>
					<li>Expense: <b>{{thisYear.Expense}}</b></li>
					<li>Transfer: <b>{{thisYear.Transfer}}</b></li>
					</ul>
				</div>
			</li>
			<li>
				<div class="month">
					<div class="monthSelect"><p>{{monthName[thisMonth-1]}}</p>
						<select ng-model="thisMonth" ng-options="x for x in monthlist"></select>
					</div>
				</div>
				<div class="month">
					<div>
					Transfer:
					<select>
					<option ng-repeat="(key, value) in thisYear.Month[thisMonth-1].Transfer"> {{key}}:{{value}}</option>
					</select>
					</div>
					<div>
					Income:	<select>
							<option ng-repeat="(key, value) in thisYear.Month[thisMonth-1].Income"> {{key}}:{{value}}</option>
							</select>
					</div>
					<div>
					Expense:
							<select>
							<option ng-repeat="(key, value) in thisYear.Month[thisMonth-1].Expense"> {{key}}:{{value}}</option>
							</select>
					</div>
					
				</div>
			
			</li>
			</ul>
		</div>
		<div class="date">
			
			<select ng-model="year" ng-options="x for x in yearRange" ng-change="dateChanged()"></select>
			<select ng-model="month" ng-options="x for x in monthlist" ng-change="dateChanged()"></select>
			<select ng-model="day" ng-change="dateChanged()">
			<option value=1>01</option>
			<option value=2>02</option>
			<option value=3>03</option>
			<option value=4>04</option>
			<option value=5>05</option>
			<option value=6>06</option>
			<option value=7>07</option>
			<option value=8>08</option>
			<option value=9>09</option>
			<option value=10>10</option>
			<option value=11>11</option>
			<option value=12>12</option>
			<option value=13>13</option>
			<option value=14>14</option>
			<option value=15>15</option>
			<option value=16>16</option>
			<option value=17>17</option>
			<option value=18>18</option>
			<option value=19>19</option>
			<option value=20>20</option>
			<option value=21>21</option>
			<option value=22>22</option>
			<option value=23>23</option>
			<option value=24>24</option>
			<option value=25>25</option>
			<option value=26>26</option>
			<option value=27>27</option>
			<option value=28>28</option>
			<option value=29>29</option>
			<option value=30>30</option>
			<option value=31>31</option>
			</select>
		</div>
		
		<div class="col" ng-repeat="item in items" id="container{{item.name | nospace}}">
			<label>{{item.name}}:</label>
			<button ng-click="addItem(item.name)" class="edit"></button>
			<button ng-click="removeItem(item.name,$event)" class="delete"></button>
			<div ng-switch on="item.grounp">
				<select id="{{item.name | nospace }}" ng-switch-when=2 ng-init="t=item.desc[0].name" ng-model="t" ng-change="change()">
					<option ng-repeat="x in item.desc|orderBy:'name'" value={{x.name}}>{{x.name}}</option>
				</select>
				<select id="{{item.name | nospace }}" ng-switch-when=3>
					<option ng-repeat="x in item.desc|filter:type|orderBy:'detail'" id="desc{{x.id}}" value={{x.type}}>{{x.detail}}</option>
				</select>
				<select id="{{item.name | nospace }}" ng-switch-when=4>
					<option ng-repeat="x in item.desc" value={{x.val}}>{{x.name}}</option>
				</select>
				<select id="{{item.name | nospace }}" ng-switch-default>
					<option ng-repeat="x in item.desc" value={{x}}>{{x}}</option>
				</select>
			</div>
		</div>
		<div class="amount">
			<button ng-click="uploadDetail()" class="upload" title="Upload">Upload</button>
			<button ng-click="addDetail()" class="save" title="Save"></button>
			<div style="float:right;line-height:35px;"><label>	Amount:$
				<input id="amount" type="tex" ng-model="amount" size=12 value={{amount}} ng-keypress="addCheck($event)" required/></label>
			</div>
		</div>
		<div class="key">
			<button ng-click="amount=amount*(-1)">-</button>
			<button ng-repeat="digit in digits" ng-click="getAmount(digit)">{{digit}}</button>
			<button class="back" ng-click="back()"></button>
		</div>
		
		<ul class="newRecord">
			<li class="plus" ng-click="showDetail($event)"></li>
			<li>{{newRecord.Category}}=>{{newRecord.Description}}</li>
			<li>{{newRecord['Pay By']}}</li>
			<li>{{newRecord.Amount| currency}}</li>
		</ul>
		<p id="msg">{{msg}}</p> <button id="paid" ng-click="paidConfirm()">Paid</button>
		<div class="hide">
		  <div id="filter">
			<div class="filterPay">
				<select ng-model="filterPayBy" ng-change="showCol()">
				<option value="">Pay By(All)</option>
				<option ng-repeat="x in PayBy" value={{x}}>{{x}}</option>
				</select>
			</div>
			<div class="filterPay">
				<select ng-model="filterPaid" ng-change="showCol()">
				<option value="">Paid?</option>
				<option value="0">Unpaid</option>
				<option value="1">Paid</option>
				</select>
			</div>	
		  
			<div class="filterDate">
				From:
				<select ng-model="filterYear" ng-options="x for x in yearRange" ng-change="filterChanged()"></select>
				<select ng-model="filterMonth" ng-options="x for x in monthlist" ng-change="filterChanged()"></select>
				<select ng-model="filterDay" ng-change="filterChanged()">
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
				<option value=5>5</option>
				<option value=6>6</option>
				<option value=7>7</option>
				<option value=8>8</option>
				<option value=9>9</option>
				<option value=10>10</option>
				<option value=11>11</option>
				<option value=12>12</option>
				<option value=13>13</option>
				<option value=14>14</option>
				<option value=15>15</option>
				<option value=16>16</option>
				<option value=17>17</option>
				<option value=18>18</option>
				<option value=19>19</option>
				<option value=20>20</option>
				<option value=21>21</option>
				<option value=22>22</option>
				<option value=23>23</option>
				<option value=24>24</option>
				<option value=25>25</option>
				<option value=26>26</option>
				<option value=27>27</option>
				<option value=28>28</option>
				<option value=29>29</option>
				<option value=30>30</option>
				<option value=31>31</option>
				</select>
				<button ng-click="newPage(0,0)" class="previous" title="previous"></button>
				<button ng-click="newPage(1,0)" class="next" title="next"></button>
			
			</div>
			<div class="filterDate">To:
			<select ng-model="filterEndYear" ng-options="x for x in yearRange" ng-change="filterChanged()"></select>
			<select ng-model="filterEndMonth" ng-options="x for x in monthlist" ng-change="filterChanged()"></select>
			<select ng-model="filterEndDay"  ng-change="filterChanged()">
			<option value=1>1</option>
			<option value=2>2</option>
			<option value=3>3</option>
			<option value=4>4</option>
			<option value=5>5</option>
			<option value=6>6</option>
			<option value=7>7</option>
			<option value=8>8</option>
			<option value=9>9</option>
			<option value=10>10</option>
			<option value=11>11</option>
			<option value=12>12</option>
			<option value=13>13</option>
			<option value=14>14</option>
			<option value=15>15</option>
			<option value=16>16</option>
			<option value=17>17</option>
			<option value=18>18</option>
			<option value=19>19</option>
			<option value=20>20</option>
			<option value=21>21</option>
			<option value=22>22</option>
			<option value=23>23</option>
			<option value=24>24</option>
			<option value=25>25</option>
			<option value=26>26</option>
			<option value=27>27</option>
			<option value=28>28</option>
			<option value=29>29</option>
			<option value=30>30</option>
			<option value=31>31</option>
			</select>
			<button ng-click="newPage(0,1)" class="previous" title="previous"></button>
			<button ng-click="newPage(1,1)" class="next" title="next"></button>
			</div>
		  </div>
		</div>
		<div class="records hide">
		
			<button ng-click="swapView()" class="swap">View Repeating Records</button>
			<table class="details">
			<tr>
			<th></th>
			<th class="colDate">Date</th>
			<th class="colCategory">Category</th>
			<th class="colDescription">Description</th>
			<th class="colPayBy">Pay By</th>
			<th class="colAmount">Amount</th>
			<th class="colPaid">Paid</th>
			</tr>
			<tr ng-repeat="record in details | orderBy:'Date':true  | filter:byRange('Date', filterStartDate, filterEndDate) | filter:byField('Spender',filterSpender) | filter:byField('Pay By',filterPayBy)| filter:byField('Paid',filterPaid)">
			<td ng-click="deleteDetail(record.Date)" class="delete"></td>
			<td class="colDate">{{record.Date|date:"MM/dd"}}</td>
			<td class="colCategory">{{record.Category}}</td>
			<td class="colDescription">{{record.Description}}</td>
			<td class="colPayBy">{{record['Pay By']}}</td>
			<td class="colAmount">{{record.Amount}}</td>
			<td class="colPaid"><input type="checkbox" ng-checked="record.Paid==1" ng-click="paidChange(record.Date)"></td>
			</tr>
			</table>
			<table class="repeatRecords">
			<tr>
			<th></th>
			<th class="colDate">Date</th>
			<th class="col{{x.name|nospace}}" ng-repeat="x in title" ng-if="x.name!='Date'">{{x.name}}</th>
			</tr>
			<tr ng-repeat="record in repeatRecords">
			<td ng-click="deleteRepeatRecords(record.Date)" class="delete"></td>
			<td class="colDate">{{record.Date|date:"MM/dd/yyyy"}}</td>
			<td class="col{{x.name|nospace}}" ng-repeat="x in title" ng-if="x.name!='Date' && x.name!='Paid' ">{{record[x.name]}}</td>
			<td class="colPaid"><input type="checkbox" ng-checked="record.Paid==1" ng-click="paidChange(record.Date)"></td>
			</tr>
			</table>
		</div>
	</div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</body>
</html>
