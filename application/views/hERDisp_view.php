	<div><?php echo $optionItem ?>
		Information<input type="image" src="/images/delete.png" name="DeleteAutoRec" value="Delete Auto Recording"/>
		<input type="image" src="/images/edit.png" name="AddAutoRec" id="AddAutoRec" value="Modify Auto Recording"/>
		<select name="item_frequency_id" id="item_frequency_id" class="wide" >
		<?php
			if(isset($optionPayFrq)) {
				foreach($optionPayFrq as $id=>$disp) {
					echo "<option value='$id'>$disp</option>";
				}
			}
		?>
		</select>
		
	</div>
	
	
	<div>
		<font>Income:</font>
		<select name='MonthlyIncome' id='MonthlyIncome' class="input" onChange="document.getElementById('MonthlyExpenese').value = this.value;Action('Disp');">
		<?php echo $Income ?>
		</select>
		<font>Expenese:</font>
		<select name='MonthlyExpenese' id='MonthlyExpenese' class="input" onChange="document.getElementById('MonthlyIncome').value = this.value;Action('Disp');">
		<?php echo $Expenese ?>
		</select>
		<font>Year:</font>
		<select name='Yearly' id='Yearly' class="input" onChange="Action('yearly');">
		<?php
		for($i=2011;$i<2111;$i++) {
			if($i==$yearly) echo "<option value='$i' selected='selected'>$i</option>";
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
<?php	
if($action != 'yearly')	{
echo <<<_END
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
_END;
	$e = new endec(); //$cloak_keyword has set in hERDisp
	if($RangResult){
		foreach($RangResult as $nt){
			$type=substr($optionType[$nt->type_id],0,4);
			$bankname=substr($optionBank[$nt->bank_id],0,4);
			if($nt->category_id!=0) {
				$expenses2 = $e->new_decode($nt->expenses);
				$category3 = $optionCategory[$nt->category_id];
				$option5=$this->general_model->_get(array('table'=>'category_item','category_id'=>$nt->category_id,'id'=>$nt->item_id,'user_id'=>0));
				if($option5) {
					$category_item3 = $option5[0]->category_item;
				}	
				$comment = '';
				$GetComment=$this->general_model->_get(array('table'=>'sp_comment','id'=>$nt->comment_id));
				if($GetComment) {
					$comment = $GetComment[0]->comment;
				}	
				$GetSpender=$this->general_model->_get(array('table'=>'spender','id'=>$nt->spender_id));
				if($GetSpender) {
					$name = $GetSpender[0]->name;
				}	
				echo "
				<tr>
				<td>";
				echo @substr($nt->date,-5);
				echo "	
				</td>
				<td align='right'>
				$category3
				</td>
				<td align='right'>
				$category_item3
				</td>
				<td align='right'>
				$expenses2
				</td>
				<td>
				$type
				</td>
				<td>
				$bankname
				</td>
				<td>
				$name
				</td>
				<td align='right'>
				$comment
				</td>
				</tr>
				";
			}
		}
		echo "</tbody></table>";
	}
}	
?>
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
			foreach($Item_list as $item_id5=>$Subtotal){
				if($item_id3==77) {
					$thistransfer+=$Subtotal;
					$transfer+=$Subtotal;
				}
				$option4=$this->general_model->_get(array('table'=>'category_item','category_id'=>$category_id4,'id'=>$item_id5,'user_id'=>0));
				if($option4) {
					$category_item2 = $option4[0]->category_item;
					echo "
					<tr>	
					<td>
					$category_item2
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
?>	
</table>
<script type="text/javascript">
<?php if(isset($itemTotal)) { ?>
itemTotal=<?php echo json_encode($itemTotal); ?>;
<?php } ?>
		$.each(itemTotal, function(key, value) {
			if($("#category_id").val()==key) {
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
		$("#total").html(total.toFixed(2));
</script>	

	