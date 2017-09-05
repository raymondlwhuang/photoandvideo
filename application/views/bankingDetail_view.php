<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Banking Detail</title>

</head>
<body style="font-size:25px;">
<div id="main">
	<table width="100%" style="font-size:25px;">
		<tr>
			<td>
			<input type="image" src="/images/home.png" name="Home" value="Home" width="80" onClick="window.open('/',target='_top');">
			</td>
			<td>
			</td>
			<td>
			<input type="image" src="/images/account.png" name="account" value="account" width="80" onClick="window.open('CreditPayOff.php',target='_top');">
			</td>
			<td>
			</td>
			<td>
			<input type="image" src="/images/pin.jpg" name="pid" value="pid" width="80" onClick="window.open('getPin.php',target='_top');">
			</td>
			<td>
			</td>
			<td>
			<input type="image" src="/images/HERecorder.jpg" name="Back" value="Back" width="80" onclick="window.open('HERecorder.php',target='_top');">
			</td>
			<td colspan="3" style="float:right;"><input type="image" src="/images/logout.png" name="Cancel" value="Cancel" width="80" onClick="window.open('signout.php',target='_top');"></td>
		</tr>
		<tr>
			<td colspan="7" align="center"><font color="red"><b id="ErrorMessage"></b></font></td>
		</tr>
	</table>
	<div id="Result">
		<table width="100%" style="border-style: solid;border-color:#0000ff;border-width: 3px;">
			<tr>
			<th style='font-size:25px;color:red;border-style: solid;border-color:#0000ff;border-width: 3px;text-align:center;' colspan="8">
			<?php echo strtoupper($BankName); ?>
			</th>
			</tr>		
			<tr>
			<th style='font-size:25px;border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
			Date
			</th>
			<th style='font-size:25px;border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
			Category
			</th>
			<th style='font-size:25px;border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
			Description
			</th>
			<th style='font-size:25px;border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
			Amount
			</th>
			<th style='font-size:25px;border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
			Spender
			</th>
			<th style='font-size:25px;border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
			Type
			</th>
			<th style='font-size:25px;border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
			Paid Status
			</th>
			<th style='font-size:25px;border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
			Balance
			</th>
			</tr>
			<?php
			$cloak_keyword = "raymond".$this->session->userdata('pin');
			$e = new endec();  
			$NewBalance=$balance;
			if($RangResult){
				foreach($RangResult as $nt){
					$expenses = $e->new_decode($nt->expenses);
					$category = $optionCategory[$nt->category_id];
					if($nt->paid==1) $PaidStatus = "Paid";
					elseif($nt->paid==4) $PaidStatus = "Set Paid In later date";
					else $PaidStatus = "Unpaid";
					$option5=$this->general_model->_get(array('table'=>'category_item','category_id'=>$nt->category_id,'id'=>$nt->item_id,'user_id'=>0));
					if($option5) {
						$category_item = $option5->category_item;
					}	
					
					$GetSpender=$this->general_model->_get(array('table'=>'spender','id'=>$nt->spender_id));
					if($GetSpender) {
						$name = $GetSpender->name;
					}	
					$GetType=$this->general_model->_get(array('table'=>'sp_payment_type','id'=>$nt->type_id));
					if($GetType) {
					$type = $GetType->Type;
					}				
				echo "
				<tr>
					<td  style='font-size:25px;border-style: solid;border-width: 3px;text-align:right;'>";
					echo @substr($nt->date,-5);
				echo "	
					</td>
					<td align='right'  style='font-size:25px;border-style: solid;border-width: 3px;text-align:right;'>
					$category
					</td>
					<td align='right'  style='font-size:25px;border-style: solid;border-width: 3px;text-align:right;'>
					$category_item
					</td>
					<td align='right'  style='font-size:25px;border-style: solid;border-width: 3px;text-align:right;'>
					$expenses
					</td>
					<td  style='font-size:25px;border-style: solid;border-width: 3px;text-align:right;'>
					$name
					</td>
					<td align='right'  style='font-size:25px;border-style: solid;border-width: 3px;text-align:right;'>
					$type
					</td>
					<td align='right'  style='font-size:25px;border-style: solid;border-width: 3px;text-align:right;'>
					$PaidStatus
					</td>
					</td>
					<td align='right'  style='font-size:25px;border-style: solid;border-width: 3px;text-align:right;'>";
					if($nt->paid==1) echo $NewBalance;
					echo "
					</td>
				</tr>
				";
				if($nt->paid==1) $NewBalance=$NewBalance-$expenses;

				}
			}
			?>
		</table>
	</div>
</div>	
</body>
</html>
