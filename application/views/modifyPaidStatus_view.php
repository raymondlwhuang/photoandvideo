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
	<input type="radio" name="paid_status" value="All" <?php if($paid_status=="All") echo "checked='checked'"; ?> onClick="Action(0,0,false);">All<br/>
	<input type="radio" name="paid_status" value="Unpaid" <?php if($paid_status=="Unpaid") echo "checked='checked'"; ?> onClick="Action(0,0,false);">Unpaid<br/>
	<input type="radio" name="paid_status" value="Paid" <?php if($paid_status=="Paid") echo "checked='checked'"; ?> onClick="Action(0,0,false);">Paid<br/>
	<input type="radio" name="paid_status" value="Future" <?php if($paid_status=="Future") echo "checked='checked'"; ?> onClick="Action(0,0,false);">Paid Future
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
	echo"	</td>
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
		elseif ($nt->paid == 1 ||$nt->paid == 4){
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
<script type="text/javascript">
total = <?php echo $paid_amount; ?>;
document.getElementById('total').innerHTML = "$"+total;
</script>
