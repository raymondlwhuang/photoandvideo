<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Household Expenses Recorder</title>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css" />
<?php echo link_tag('css/dialog.css'); ?>
<?php echo link_tag('css/herecorder.css'); ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.tablesorter.js"></script> 
<!-- tablesorter widgets (optional) --> 
<script type="text/javascript" src="/scripts/jquery.tablesorter.widgets.js"></script> 
<script type="text/javascript" src="/scripts/jquery.blockUI.js"></script>
<script type="text/javascript">
var ItemResult = <?php echo json_encode($ItemResult); ?>,optionItem=<?php echo json_encode($optionItem); ?>,itemPayType=<?php echo json_encode($itemPayType); ?>,optionSpender=<?php echo json_encode($optionSpender); ?>,optionCategory=<?php echo json_encode($optionCategory); ?>,itemPayType=<?php echo json_encode($itemPayType); ?>,itemBank=<?php echo json_encode($itemBank); ?>,optionItem=<?php echo json_encode($optionItem); ?>,optionType=<?php echo json_encode($optionType); ?>,optionfrequency=<?php echo json_encode($optionfrequency); ?>,optionBank=<?php echo json_encode($optionBank); ?>,optionBalance=<?php echo json_encode($optionBalance); ?>,optionPayFrq=<?php echo json_encode($optionPayFrq); ?>,
optionName=<?php echo json_encode($optionName); ?>,categoryPayType=<?php echo json_encode($categoryPayType); ?>,categoryTotal=<?php echo json_encode($categoryTotal); ?>,defaultPayType=<?php echo json_encode($defaultPayType); ?>,itemTotal=<?php echo json_encode($itemTotal); ?>;
</script>
<script type="text/javascript" src="/scripts/herecorder.js"></script>
</head>
<body>
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
<div id="keyboard" style="position:fixed;bottom:0px;right:0px;background-color:white;display:none;">
	<div>
		<input type="image" src="/images/subtract.png" name="subtract" id="subtract" value="-">
		<input type="image" src="/images/0.png" name="0" id="0" value="0">
		<input type="image" src="/images/1.png" name="1" id="1" value="1">
		<input type="image" src="/images/2.png" name="2" id="2" value="2">
		<input type="image" src="/images/3.png" name="3" id="3" value="3">
		<input type="image" src="/images/4.png" name="4" id="4" value="4">
		<input type="image" src="/images/5.png" name="5" id="5" value="5">
		<input type="image" src="/images/6.png" name="6" id="6" value="6">
		<input type="image" src="/images/7.png" name="7" id="7" value="7">
		<input type="image" src="/images/8.png" name="8" id="8" value="8">
		<input type="image" src="/images/9.png" name="9" id="9" value="9">
	</div>
	<div>
		<input type="image" src="/images/home.png" name="Home" id="Home" value="Home">
		<input type="image" src="/images/account.png" name="account" id="account" value="account">
<!--		<input type="image" src="/images/pin.jpg" name="pid" id="pid" value="pid">-->
		<input type="image" src="/images/save.jpg" name="Save" id="Save" value="Save">
		<input type="image" src="/images/Backspace.png" name="backward" id="backward" value="Back Space"/>
		<input type="image" src="/images/KeyboardHide.png" name="hide" id="hide" value="Hide Keyboard"/>
		<input type="image" src="/images/logout.png" name="Logout" id="Logout" value="Logout" class="Logout">
	</div>
</div>
<div id="main">

<div id="reminder_list">
	<form name="ReminderDel" method="Post">
		<input type="hidden" name="reminder_id" id="reminder_id" value=""/>
	</form>
		
	<?php
		$e = new endec();
		if(isset($queryResult3)) {
			echo "<h3>It's time to make payment for the following items</h3>";
			echo "<ul>";
			foreach($queryResult3 as $typeResult3) {
				$reminder_id=$typeResult3->id;
				$CategoryResult1=$this->general_model->_get(array('table'=>'spending_category','id'=>$typeResult3->category_id,'limit'=>1));
				echo "<li><input type='image' src='/images/delete.png' name='Delete' class='delete_reminder' value='$reminder_id'/>";
				if($CategoryResult1){
					echo $CategoryResult1[0]->category."=>";
					$ItemResult1=$this->general_model->_get(array('table'=>'category_item','id'=>$typeResult3->item_id,'limit'=>1));
					if($ItemResult1){
						echo $ItemResult1[0]->category_item;
					}
					$TypeResult4=$this->general_model->_get(array('table'=>'sp_payment_type','id'=>$typeResult3->type_id,'limit'=>1));
					if($TypeResult4) {
						echo "with <font color=\"red\"><b>".$TypeResult4[0]->Type."</b></font> ";
					}
					$BankResult1=$this->general_model->_get(array('table'=>'sp_bank','id'=>$typeResult3->bank_id,'limit'=>1));
					if($BankResult1) {
						echo "pay from <font color=\"red\"><b>".$BankResult1[0]->bank."</b></font>&nbsp;";
					}
					$BankResult2=$this->general_model->_get(array('table'=>'sp_bank','id'=>$typeResult3->to_bank_id,'limit'=>1));
					if($BankResult2) {
						echo " to <font color=\"red\"><b>".$BankResult2[0]->bank."</b></font> ";
					}
					$amount=$e->new_decode($typeResult3->amount);
				}
				if ($layout!="desktop")	echo "with amount: <input type='text' class='reminder_amount' id='amt$reminder_id' value='$amount' readonly=\"readonly\"/>";
				else echo "with amount: <input type='text' class='reminder_amount' id='amt$reminder_id' value='$amount' />";
				echo "<input type='image' src='/images/paynow.png' name='$reminder_id' class='paynow' id='$reminder_id' value='$amount'/></li>";
			}
			echo "</ul>";
		}
		if(isset($queryResult1)){
			echo "<h3>It's time to pay the overdue amount on your</h3><ul>";
			foreach($queryResult1 as $typeResult1) {
				$typeResult2=$this->general_model->_get(array('table'=>'sp_payment_type','user_id'=>0,'id'=>$typeResult1->type_id,'limit'=>1));
				if($typeResult2) {
					echo "<li>".$typeResult2[0]->Type."</li>";
				}
			}
			echo "</ul>";
		}
	?>				
</div>
<h3 id="ErrorMessage"><input type="image" src="/images/loader.gif" /><input type="image" src="/images/ajax-loader.gif" /><input type="image" src="/images/loader.gif" /></h3>
<div class="col_left">
	<div>
	Spender <input type="image" src="/images/delete.png" name="DeleteSpender" id="DeleteSpender" value="Delete Spender"/>
			<input type="image" src="/images/edit.png" name="AddSpender" id="AddSpender" value="Add Spender"/>
			<select name="spender_id" id="spender_id" class="input">
			<script type="text/javascript">
				$.each(optionSpender, function(key, value) {
					document.write('<option value='+key+'>'+value+'</option>');
				});	
			</script>		
			</select>

	</div>
	<div id="item_comment">
		Description <input type="image" src="/images/edit.png" name="AddItem" id="AddItem" value="Add Item" />
		<select name="item_id" id="item_id" class="input">
		<script type="text/javascript">
			$.each(optionItem, function(key, value) {
				document.write('<option value='+key+'>'+value+'</option>');
			});	
		</script>		
		</select>
	</div>
	<div>
		Payment Type <input type="image" src="/images/edit.png" name="AddType" id="AddType" value="Add Type"/>
		<select name="type_id" id="type_id" class="input">
		<script type="text/javascript">
			$.each(optionType, function(key, value) {
				document.write('<option value='+key+'>'+value+'</option>');
			});	
		</script>		
		</select>	
	</div>
	<div>
		How to Pay?
		<select name="frequency_id" id="frequency_id" class="input">
		<script type="text/javascript">
			$.each(optionfrequency, function(key, value) {
				document.write('<option value='+key+'>'+value+'</option>');
			});	
		</script>		
		</select>
	</div>
</div>
<div class="col_mid">
</div>
<div class="col_right">
	<div >
	Category <input type="image" src="/images/edit.png" name="AddCategory" id="AddCategory" value="Add Category"/>
			<select name="category_id" id="category_id" class="input">
			<script type="text/javascript">
				$.each(optionCategory, function(key, value) {
					document.write('<option value='+key+'>'+value+'</option>');
				});	
			</script>			
			</select>
	</div>
	<div>
		Comment <input type="text" name="comment_id" id="comment_id" class="input" value="" />
	</div>
	<div>
		Amount: $ <input type="text" name="amount" id="amount" class="input" maxlength="10"  <?php if ($layout!="desktop") echo 'readonly="readonly"'; ?> />
	</div>
	<div>
		<div id="Date">
		<font  id="Date_label" >Starting Date</font>
		<input type="image" src="/images/calendar.jpg" name="calender" id="calender" value="calender"/>
		<input type="text" name="start_date" id="start_date" class="input" maxlength="10" >
		</div>
	</div>
</div>
<div class="col_left" id="bank_balance">
	<font id="bank_desc">Will Pay From</font>
	<input type="image" src="/images/edit.png" name="AddBank" id="AddBank" value="Add Bank"/>
	<select name="bank_id" id="bank_id" class="input">
		<script type="text/javascript">
			$.each(optionBank, function(key, value) {
			  document.write('<option value='+key+'>'+value+'</option>');
			});	
		</script>	
	</select>
	<font id="balance"></font>
	<div>
	Rimind
	<select name="reminder" id="reminder" class="input">
		<option value='1' selected>Never</option>
		<option value='2'>Daily</option>
		<option value='3'>Weekly</option>
		<option value='4'>Bi-Weekly</option>
		<option value='5'>Monthly</option>
		<option value='9'>Bi-Monthly</option>
		<option value='6'>Quarterly</option>
		<option value='7'>Semi-Annually</option>
		<option value='8'>Yearly</option>
	</select>
	</div>
</div>
<div  id="float_right">
		<font id="to_bank_label">To:</font>
		<select name="to_bank" id="to_bank" class="input">
		<script type="text/javascript">
			$.each(optionBank, function(key, value) {
			  document.write('<option value='+key+'>'+value+'</option>');
			});	
		</script>	
		</select>			
</div>
<div id="Result">
	<div>
		Information<input type="image" src="/images/delete.png" name="DeleteAutoRec" id="DeleteAutoRec" value="Delete Auto Recording"/>
		<input type="image" src="/images/edit.png" name="AddAutoRec" id="AddAutoRec" value="Modify Auto Recording"/>
		<select name="item_frequency_id" id="item_frequency_id" class="wide" >
		<script type="text/javascript">
			$.each(optionPayFrq, function(key, value) {
				document.write('<option value='+key+'>'+value+'</option>');
			});	
		</script>			
		</select>
		
	</div>
	<div>
		<font>Income:</font>
		<select name='MonthlyIncome' id='MonthlyIncome' class="input">
		<?php echo $Income ?>
		</select>
		<font>Expenese:</font>
		<select name='MonthlyExpenese' id='MonthlyExpenese' class="input">
		<?php echo $Expenese ?>
		</select>
		<font>Year:</font>
		<select name='Yearly' id='Yearly' class="input">
		<?php
		$year = date('Y');	
		for($i=2011;$i<2111;$i++) {
			if($i==$year) echo "<option value='$i' selected='selected'>$i</option>";
			else echo "<option value='$i'>$i</option>";
		}
		?>
		</select>
	</div>
	<table>
		<tr>
		<td id="CategoryShow">
		</td>	
		<td id="Detail">
		</td>
		<td id="total">
		</td>	
		</tr>
	</table>
	<table id="mytable">
	<thead>
		<tr>
		<th>
		Date
		</th>
		<th>
		Category
		</th>
		<th>
		Description
		</th>
		<th>
		Amt
		</th>
		<th>
		Type
		</th>
		<th>
		Bank
		</th>
		<th>
		Who
		</th>
		<th>
		Com.
		</th>
		</tr>
	</thead>
	<tbody>
<?php
		if($RangResult) { 
			foreach($RangResult as $nt){
				$type=substr($optionType[$nt->type_id],0,4);
				$bankname=substr($optionBank[$nt->bank_id],0,4);
				$expenses3 = $e->new_decode($nt->expenses);
				$category3 = $optionCategory[$nt->category_id];
				$option5=$this->general_model->_get(array('table'=>'category_item','category_id'=>$nt->category_id,'id'=>$nt->item_id,'user_id'=>0,'limit'=>1));
				if($option5) {
					$category_item4 = $option5[0]->category_item;
				}	
				$comment = '';
				$queryComment = $this->general_model->_get(array('table'=>'sp_comment','id'=>$nt->comment_id,'limit'=>1));
				if($queryComment) {
				$comment = $queryComment[0]->comment;
				}	
			echo "
			<tr>
				<td>";
				echo substr($nt->date,-5);
			echo "	
				</td>
				<td>
				$category3
				</td>
				<td>
				$category_item4
				</td>
				<td>
				$expenses3
				</td>
				<td>
				$type
				</td>
				<td>
				$bankname
				</td>
				<td>";
				echo $optionName[$nt->spender_id];
				echo "</td>
				<td>
				$comment
				</td>
			</tr>
			";
			}
		}
		?>
	</tbody>		
	</table>
	<table>
		<caption>Monthly Expenses information(Date:<?php echo date('l jS \of F Y') ?>)</caption>		
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
	
</div>
</div>
<div id='BlankMsg'></div>
<script type="text/javascript">
	document.getElementById('ErrorMessage').innerHTML = "";
</script>
</body>
</html>
