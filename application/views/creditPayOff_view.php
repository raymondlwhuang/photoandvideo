<div id='BlankMsg' class="nodisp"></div>
<table>
	<tr>
		<td>
			<font  id="Date_label">Total </font><b><font  id="total" color="red">$0</font></b> will paid from
			<select name="bank_id" id="bank_id" style="font-size:30px; width:300px;border-color:#5050FF;border-width: 3px;" onChange="Action(0,0,false);">
			<?php
				foreach($optionBank as $id=>$bank) {
					if($id==3) echo "<option value='$id' selected='selected'>$bank</option>";
					else echo "<option value='$id'>$bank</option>";
				}
			?>
			</select>
			</font>
		</td>
	</tr>
	<tr>
			<td>
				at date: <input type="text" name="paid_date" id="paid_date" maxlength="10" size="10" style='font-size:40px;border-style: solid;border-color:#ff0000;border-width: 3px;text-align:right;'>
				<input type="image" src="/images/calendar.jpg" name="calender" id="calender" width="60" value="calender" style='position:relative;top:10px;'>
				<input type="image" src="/images/click-here.gif" name="Save" value="Save" width="60" onclick="Action_Final();" style='position:relative;top:10px;'>
				<b><font color="red" style='position:relative;top:-10px;'><=Confirm</font></b>
			</td>
	</tr>
</table>
<table name="mytable" id="Result">
  <thead> 
	<tr>
	<th>
	<p>Spender</p>
	</th>
	<th>
	<p>Description</p>
	</th>
    <th>
	<p>Type</p>
	</th>
	<th>
	<p>Date</p>
	</th>
	<th>
	<p>Amount</p>
	</th>
	<th class="small">
	<form name="myform">
	<input type="radio" name="paid_status" value="All" onClick="Action(0,0,false);">All<br/>
	<input type="radio" name="paid_status" value="Unpaid" checked="checked"  onClick="Action(0,0,false);">Unpaid<br/>
	<input type="radio" name="paid_status" value="Paid" onClick="Action(0,0,false);">Paid<br/>
	<input type="radio" name="paid_status" value="Future" onClick="Action(0,0,false);">Paid Future
	</form>
	</th>
	</tr>
 </thead>
 <tbody>  
<?php
$paid_amount = 0;
$e = new endec(); //$cloak_keyword has set in controller
if(isset($queryResult)){
	foreach($queryResult as $nt){
		$amount = $e->new_decode($nt->expenses);
		$category_id = $nt->category_id;
		$item_id = $nt->item_id;
		$type_id = $nt->type_id;
		$bank_id2 = $nt->bank_id;
		$Description = "$optionCategory[$category_id]=>$optionItem[$item_id]";
		if($nt->paid == 3) $paid_amount = $paid_amount + $amount;
		echo "
		<tr>
			<td>";
		echo $optionSpender[$nt->spender_id];
		echo "</td>
			<td>
			$Description
			</td>
			<td>
			$optionType[$type_id]
			</td>
			<td>";
			echo substr($nt->date,-5);
		echo"</td>
			<td align='right'>
			$amount
			</td>
			<td>";
			if($nt->paid == 3) {
				echo "
				<input type='checkbox' name='$nt->id' id='$nt->id' value='$amount' checked='checked' onChange='Action(this.name,this.value,this.checked);' />
				";
			}
			elseif($nt->paid == 0) {
				echo "
				<input type='checkbox' name='$nt->id' id='$nt->id' value='$amount' onChange='Action(this.name,this.value,this.checked);' />
				";
			}
			else {
				echo "
				<input type='checkbox' name='$nt->id' id='$nt->id' value='$amount' checked='checked' disabled='disabled' />
				";
			}
			echo "
			</td>
			</tr>";
			if($nt->paid == 1) $curr_balance = $curr_balance - $amount;
	}
}
?>
 <tbody> 
</table>
</body>
</html>
	