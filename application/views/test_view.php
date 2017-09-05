<!DOCTYPE html>
<html>
<head>
<title>Responsive Expenses Recorder</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css" />
<?php echo link_tag('css/dialog.css'); ?>
<?php echo link_tag('css/normalize.css'); ?>	
<?php echo link_tag('css/jquery.keypad.css'); ?>
<?php echo link_tag('css/hher.css'); ?>
<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.keypad.js"></script>
<script type="text/javascript">
var ItemResult = <?php echo json_encode($ItemResult); ?>,
optionItem=<?php echo json_encode($optionItem); ?>,
itemPayType=<?php echo json_encode($itemPayType); ?>,
optionSpender=<?php echo json_encode($optionSpender); ?>,
optionCategory=<?php echo json_encode($optionCategory); ?>,
itemPayType=<?php echo json_encode($itemPayType); ?>,
itemBank=<?php echo json_encode($itemBank); ?>,
optionItem=<?php echo json_encode($optionItem); ?>,
optionType=<?php echo json_encode($optionType); ?>,
optionfrequency=<?php echo json_encode($optionfrequency); ?>,
optionBank=<?php echo json_encode($optionBank); ?>,
optionBalance=<?php echo json_encode($optionBalance); ?>,
optionPayFrq=<?php echo json_encode($optionPayFrq); ?>,
optionName=<?php echo json_encode($optionName); ?>,
categoryPayType=<?php echo json_encode($categoryPayType); ?>,
categoryTotal=<?php echo json_encode($categoryTotal); ?>,
defaultPayType=<?php echo json_encode($defaultPayType); ?>,
itemTotal=<?php echo json_encode($itemTotal); ?>;
var selectList = [
{ "thisLable" : "Spender" , "deleteName" : "DeleteSpender","editName":"AddSpender","thisOption":optionSpender,"thisId":"spender_id" }, 
{ "thisLable" : "Category" , "deleteName" : "","editName":"AddCategory","thisOption":optionCategory,"thisId":"category_id" },
{ "thisLable" : "Description" , "deleteName" : "","editName":"AddItem","thisOption":optionItem,"thisId":"item_id" },
{ "thisLable" : "Payment Type" , "deleteName" : "","editName":"AddType","thisOption":optionType,"thisId":"type_id" },
{ "thisLable" : "Will Pay From" , "deleteName" : "","editName":"AddBank","thisOption":optionBank,"thisId":"bank_id" } ];
function parseOption(thisLable,deleteName,editName,thisOption,thisId)
{
	document.write('<article><ul><li>'+thisLable+'</li>');
	if(selectList.deleteName!="") document.write('<li><input type="image" src="/images/delete.png" name="'+deleteName+'" id="'+deleteName+'" value="Delete"/></li>');
	if(selectList.editName!="") document.write('<li><input type="image" src="/images/edit.png" name="'+editName+'" id="'+editName+'" value="Edit"/></li>');
	document.write('<li><select name="'+thisId+'" id="'+thisId+'" class="input">');
	$.each(thisOption, function(key, value) {
		document.write('<option value='+key+'>'+value+'</option>');
	});
	document.write('</select></li></ul></article>');
}

$(document).ready(function(){
  $("button").click(function(){
    $.getJSON("/test2",function(data){
      $.each(data['optionCategory'], function(i, field){
			
                   alert(field);
      });
    });
  });
}); 
</script>
<script type="text/javascript" src="/scripts/hher.js"></script>	
	
</head>
<body>
<button>click</button>
	<nav id="menu" class="clearfix">
		<ul>
			<li><a href="./">Home</a></li>
			<li><a href="./CreditPayOff">Pay Bill</a></li>
			<li name="Save" id="Save"><a href="">Save</a></li>
			<li><a href="./Signout">Logout</a></li>
		</ul>
	</nav>
	<header>&nbsp;
	</header>	
	<div id="AddDialog">
		<input type="image" name="close" class="close" src="/images/close_icon.png"/>
		<font id="dialog_text">Name</font>
		<input type="text" name="input_text" id="input_text" value="" size="32" maxlength="30" />
		<div id="adjustment">
			<font id="adj_title">Balance Ajustment</font>
			<input type="text" name="input_amount" id="input_amount"  value="0" size="32" maxlength="15">
		</div>
		<div id="insert">
		<input type="image" name="insert" src="/images/submit.jpg"><br/><br/>
		<font color="red">Delete may not allowed<br/> after submit.<br/>Please make sure! </font>
		</div>
	</div>
	<section class="clearfix" id="reminder_list">
		<form name="ReminderDel" method="Post">
			<input type="hidden" name="reminder_id" id="reminder_id" value=""/>
		</form>
			
		<?php
			$e = new endec();
			if(isset($queryResult3)||isset($queryResult1)) { echo "<h3>It's time to pay</h3>";
			if(isset($queryResult1)){
				echo "<ul>";
				foreach($queryResult1 as $typeResult1) {
					$typeResult2=$this->general_model->_get(array('table'=>'sp_payment_type','user_id'=>0,'id'=>$typeResult1->type_id,'limit'=>1));
					if($typeResult2) {
						echo "<li>**".$typeResult2[0]->Type."**</li>";
					}
				}
			}			
			if(isset($queryResult3)) {
				foreach($queryResult3 as $typeResult3) {
					$reminder_id=$typeResult3->id;
					$CategoryResult1=$this->general_model->_get(array('table'=>'spending_category','id'=>$typeResult3->category_id,'limit'=>1));
					echo "<li><input type='image' src='/images/delete.png' name='Delete' class='delete_reminder' value='$reminder_id'/>";
					if($CategoryResult1){
						echo $CategoryResult1[0]->category."=>";
						$ItemResult1=$this->general_model->_get(array('table'=>'category_item','id'=>$typeResult3->item_id,'limit'=>1));
						if($ItemResult1){
							echo $ItemResult1[0]->category_item."</li>";
						}
					}
				}
			}
			echo "</ul>";
		}
		?>				
	</section>
	<button>click</button>
  <div id="main" class="clearfix">
		<h3 id="ErrorMessage"></h3>
		<script type="text/javascript">
		$.each(selectList, function(key, value) {
			parseOption(value.thisLable, value.deleteName,value.editName,value.thisOption,value.thisId);
		});	
		</script>	
	<article>
		<ul>
		<li>Category</li> 
		<li><input type="image" src="/images/edit.png" name="AddCategory" id="AddCategory" value="Add Category"/></li>
		<li><select name="category_id" id="category_id" class="input">
		<script type="text/javascript">
			$.each(optionCategory, function(key, value) {
				document.write('<option value='+key+'>'+value+'</option>');
			});	
		</script>			
		</select></li>	
		</ul>
	</article>
	<article>
		<ul id="item_comment">
			<li>Description</li> 
			<li><input type="image" src="/images/edit.png" name="AddItem" id="AddItem" value="Add" /></li>
			<li><select name="item_id" id="item_id" class="input">
			<script type="text/javascript">
				$.each(optionItem, function(key, value) {
					document.write('<option value='+key+'>'+value+'</option>');
				});	
			</script>
			</select></li>
		</ul>		
	</article>
	<article>
		<ul>
		<li>Payment Type </li>
		<li><input type="image" src="/images/edit.png" name="AddType" id="AddType" value="Add"/></li>
		<li><select name="type_id" id="type_id" class="input">
		<script type="text/javascript">
			$.each(optionType, function(key, value) {
				document.write('<option value='+key+'>'+value+'</option>');
			});	
		</script>		
		</select></li>
		</ul>
	</article>
	<article>
	  <ul id="bank_balance">
		<li id="bank_desc">Will Pay From</li>
		<li><input type="image" src="/images/edit.png" name="AddBank" id="AddBank" value="Add Bank"/></li>
		<li><select name="bank_id" id="bank_id" class="input">
			<script type="text/javascript">
				$.each(optionBank, function(key, value) {
				  document.write('<option value='+key+'>'+value+'</option>');
				});	
			</script>	
		</select></li>
	  </ul>
	</article>
	<article>
	  <ul>
	    <li>Balance : $</li>
		<li id="balance"></li>
	  </ul>
	</article>
	<article>
	  <ul id="float_right">
		<li id="to_bank_label">To:</li>
		<li><select name="to_bank" id="to_bank" class="input">
		<script type="text/javascript">
			$.each(optionBank, function(key, value) {
			  document.write('<option value='+key+'>'+value+'</option>');
			});	
		</script>	
		</select></li>			
	  </ul>
	</article>
	<article>
		<ul>
		<li>Amount: $</li> 
		<li><input type="text" name="amount" id="amount" class="input" maxlength="10" readonly="readonly" /></li>
		</ul>
	</article>
	<article>
		<ul>
		<li>How to Pay?</li>
		<li><select name="frequency_id" id="frequency_id" class="input">
		<script type="text/javascript">
			$.each(optionfrequency, function(key, value) {
				document.write('<option value='+key+'>'+value+'</option>');
			});	
		</script>		
		</select></li>	
		</ul>
	</article>
	<article>
		<ul id="Date">
		<li  id="Date_label" >Starting Date</li>
		<li><input type="image" src="/images/calendar.jpg" name="calender" id="calender" value="calender"/></li>
		<li><input type="text" name="start_date" id="start_date" class="input" maxlength="10" ></li>
		</ul>	
	</article>
	<article>
	  <ul>
		<li>Rimind</li>
		<li><select name="reminder" id="reminder" class="input">
			<option value='1' selected>Never</option>
			<option value='2'>Daily</option>
			<option value='3'>Weekly</option>
			<option value='4'>Bi-Weekly</option>
			<option value='5'>Monthly</option>
			<option value='9'>Bi-Monthly</option>
			<option value='6'>Quarterly</option>
			<option value='7'>Semi-Annually</option>
			<option value='8'>Yearly</option>
		</select></li>
	  </ul>	
	</article>

  </div>
	<aside id="Result" class="clearfix">
		<ul>
			<li>Information</li>
			<li><input type="image" src="/images/delete.png" name="DeleteAutoRec" id="DeleteAutoRec" value="Delete"/></li>
			<li><input type="image" src="/images/edit.png" name="AddAutoRec" id="AddAutoRec" value="Modify Auto Recording"/></li>
			<li><select name="item_frequency_id" id="item_frequency_id" style='width:99%;'>
			<script type="text/javascript">
				$.each(optionPayFrq, function(key, value) {
					document.write('<option value='+key+'>'+value+'</option>');
				});	
			</script>			
			</select></li>
		</ul>
			<ul>
				<li>Income:</li>
				<li><select name='MonthlyIncome' id='MonthlyIncome' class="input">
				<?php echo $Income ?>
				</select></li>
			</ul>
			<ul>
				<li>Expenese:</li>
				<li><select name='MonthlyExpenese' id='MonthlyExpenese' class="input">
				<?php echo $Expenese ?>
				</select></li>
			</ul>
			<ul>
				<li>Year:</li>
				<li><select name='Yearly' id='Yearly' class="input">
				<?php
				$year = date('Y');	
				for($i=2011;$i<2111;$i++) {
					if($i==$year) echo "<option value='$i' selected='selected'>$i</option>";
					else echo "<option value='$i'>$i</option>";
				}
				?>
				</select></li>
			</ul>
			<div id="mytable" class="clearfix">
				<ul id="insertline">
				<li>
				Day
				</li>
				<li>
				Description
				</li>
				<li>
				Amt
				</li>
				<li>
				Type
				</li>
				<li>
				Account
				</li>
				</ul>
		<?php
				if($RangResult) { 
					foreach($RangResult as $nt){
						$type=substr($optionType[$nt->type_id],0,4);
						$bankname=$optionBank[$nt->bank_id];
						$expenses3 = $e->new_decode($nt->expenses);
						$category3 = $optionCategory[$nt->category_id];
						$option5=$this->general_model->_get(array('table'=>'category_item','category_id'=>$nt->category_id,'id'=>$nt->item_id,'user_id'=>0,'limit'=>1));
						if($option5) {
							$category_item4 = $option5[0]->category_item;
						}	
					echo "<ul>
						<li>";
						echo substr($nt->date,-2);
					echo "	
						</li>
						<li>
						$category_item4
						</li>
						<li>
						$expenses3
						</li>
						<li>
						$type
						</li>
						<li>
						$bankname
						</li></ul>
					";
					}
				}
				echo "</div>";
				?>
			<table>
				<caption>Monthly Expenses information</caption>		
				<tr>
				<th>
				Category
				</th>	
				<th>
				Detail
				</th>
				<th>
				Total
				</th>	
				</tr>
				<?php
				if(isset($categoryTotal)) {
					$grandtotal=0;
					$incometotal=0;
					$transfer=0;
					foreach($categoryTotal as $category_id4=>$total){
						if($total >0) {
						if($category_id4!=1) $grandtotal+=$total;
						else $incometotal+=$total;
						echo"
						<tr>
						<td>
						$optionCategory[$category_id4]
						</td>
						<td>
							<table>";
							$Item_list = $itemTotal["$category_id4"];
							$thistransfer=0;
							foreach($Item_list as $item_id3=>$Subtotal){
								if($item_id3==77) {
									$thistransfer+=$Subtotal;
									$transfer+=$Subtotal;
								}
								$option4=$this->general_model->_get(array('table'=>'category_item','category_id'=>$category_id4,'id'=>$item_id3,'user_id'=>0,'limit'=>1));
								if($option4) {
									$category_item3 = $option4[0]->category_item;
									echo "
									<tr>	
									<td>
									$category_item3
									</td>
									<td width='80'>
									$Subtotal
									</td>
									</tr>";
								}	
							}
							$total=$total-$thistransfer;
							echo "
							</table>
						</td>
						<td>
						$total
						</td>
						</tr>";
						}
					}
					$incometotal=$incometotal-$transfer;
					echo "<tr><td></td><td>Grand Total</td><td>$grandtotal</td></tr>";
				}
				?>
			</table>	
	</aside>
	<footer>&nbsp;
	</footer>

</body>
</html>


