<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link type="text/css" rel="stylesheet" href="/css/MyResource.css" />
<style type="text/css">
input.wide {display:block; width: 99%} 
</style>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Adding description of My kidsreward</title>
<script type="text/javascript">
window.onload = SetAmount;
function SetAmount() {
document.getElementById("amount").value = document.getElementById("amount1").value * document.getElementById("amount2").value;
}
</script>
</head>
<body style="font-size:40px;">
<form name="MyForm" enctype="application/x-www-form-urlencoded" method="post">
<table width="100%" border="1">
	<tr>
    <th>
	Reward To
	</th>
	<th align="center">
	Amount
	</th>
	<th align="center">
	Signature
	</th>
	</tr>
	<tr>
    <td align="center">
	<select name="rewardid" id="rewardid" style="font-size:50px;border-color:#0000ff;border-style:ridge;border-width: 7px;" onChange="SetAmount();">
	   <option value="1" selected>Carly</option>
	   <option value="2">Jessica</option>
	 </select>	
	</td>
	<td  align="center">
	<select name="amount1" id="amount1" style="font-size:50px;border-color:#0000ff;border-style:ridge;border-width: 7px;" onChange="SetAmount();">
	   <option value="0" selected>0</option>
	   <option value="0.25">0.25</option>
	   <option value="0.5" >0.50</option>
	   <option value="0.75" >0.75</option>
	   <option value="1" >1</option>
	 </select>	
	<select name="amount2" id="amount2" style="font-size:50px;border-color:#0000ff;border-style:ridge;border-width: 7px;" onChange="SetAmount();">
	   <option value="1">1</option>
	   <option value="2">2</option>
	   <option value="3">3</option>
	   <option value="4">4</option>
	   <option value="5">5</option>
	   <option value="-1">-1</option>
	   <option value="-2">-2</option>
	   <option value="-3">-3</option>
	   <option value="-4">-4</option>
	   <option value="-5">-5</option>
	   <option value="6">6</option>
	   <option value="7">7</option>
	   <option value="8">8</option>
	   <option value="9">9</option>
	   <option value="10">10</option>
	   <option value="11">11</option>
	   <option value="12">12</option>
	   <option value="13">13</option>
	   <option value="14">14</option>
	   <option value="15">15</option>
	   <option value="16">16</option>
	   <option value="17">17</option>
	   <option value="18">18</option>
	   <option value="19">19</option>
	   <option value="20">20</option>
	   <option value="-6">-6</option>
	   <option value="-7">-7</option>
	   <option value="-8">-8</option>
	   <option value="-9">-9</option>
	   <option value="-10">-10</option>
	   <option value="-11">-11</option>
	   <option value="-12">-12</option>
	   <option value="-13">-13</option>
	   <option value="-14">-14</option>
	   <option value="-15">-15</option>
	   <option value="-16">-16</option>
	   <option value="-17">-17</option>
	   <option value="-18">-18</option>
	   <option value="-19">-19</option>
	   <option value="-20">-20</option>
	 </select>
	 <B>=</b>
	<input type="text" name="amount" id="amount" size="5" maxlength="5"  style="border-color:#0000ff;border-style:ridge;font-size:50px;border-width: 7px;">
	</td>
	<td align="center">
	<select name="signature" id="signature" style="font-size:50px;border-color:#0000ff;border-style:ridge;border-width: 7px;" onChange="SetAmount();">
	   <option value="0" selected>0</option>
	   <option value="1">1</option>
	   <option value="2">2</option>
	   <option value="3">3</option>
	   <option value="4">4</option>
	   <option value="5">5</option>
	   <option value="-1">-1</option>
	   <option value="-2">-2</option>
	   <option value="-3">-3</option>
	   <option value="-4">-4</option>
	   <option value="-5">-5</option>
	   <option value="6">6</option>
	   <option value="7">7</option>
	   <option value="8">8</option>
	   <option value="9">9</option>
	   <option value="10">10</option>
	   <option value="11">11</option>
	   <option value="12">12</option>
	   <option value="13">13</option>
	   <option value="14">14</option>
	   <option value="15">15</option>
	   <option value="16">16</option>
	   <option value="17">17</option>
	   <option value="18">18</option>
	   <option value="19">19</option>
	   <option value="20">20</option>
	   <option value="-6">-6</option>
	   <option value="-7">-7</option>
	   <option value="-8">-8</option>
	   <option value="-9">-9</option>
	   <option value="-10">-10</option>
	   <option value="-11">-11</option>
	   <option value="-12">-12</option>
	   <option value="-13">-13</option>
	   <option value="-14">-14</option>
	   <option value="-15">-15</option>
	   <option value="-16">-16</option>
	   <option value="-17">-17</option>
	   <option value="-18">-18</option>
	   <option value="-19">-19</option>
	   <option value="-20">-20</option>
	 </select>	
	</td>
  </tr>
  <tr>
      <td colspan="3" align="left"><input type="text" name="description"  class="wide" maxlength="200" style="border-color:#0000ff;border-style:ridge;font-size:60px;border-width: 7px;"  value="<?php if (isset($description)){ echo $description; } else ''; ?>"></td>
  </tr>
</table>    
	<input type="image" src="/images/save.jpg" name="Save" value="Save">
	<input type="image" src="/images/cancel.jpg" name="Cancel" value="Cancel" onClick="this.form.reset();window.open( 'KidsReward', '_top');return false;">
	</form>
	<p text-align="right" >
	<font color=red><b><?php if (isset($ErrorMessage)){ echo '**'.$ErrorMessage.'**   '; } else ''; ?></b></font>
	</p>

<table width="100%" style="border-style: solid;border-color:#0000ff;border-width: 3px;">
	<tr>
    <th style='border-style: solid;border-width: 3px;text-align:right;'>
	<p>Reward To</p>
	</th>
	<th style='border-style: solid;border-width: 3px;text-align:right;'>
	<p>Amount</p>
	</th>
	<th style='border-style: solid;border-width: 3px;text-align:right;'>
	<p>Signature</p>
	</th>
	<th style='border-style: solid;border-width: 3px;text-align:right;'>
	<p>description</p>
	</th>
	</tr>
<?php
if(isset($result)){
	foreach($result as $nt){
	echo "
	<tr>
		<td style='font-size:40px;border-style: solid;border-width: 3px;text-align:right;'>";
		if ($nt->rewardid == '1') echo "Carly"; 
		ELSE ECHO 'Jessica';
	echo "</td><td style='font-size:40px;border-style: solid;border-width: 3px;text-align:right;'>
		$nt->amount
		</td>
		<td style='font-size:40px;border-style: solid;border-width: 3px;text-align:right;'>
		$nt->signature
		</td>
		<td style='font-size:40px;border-style: solid;border-width: 3px;text-align:right;'>
		$nt->description
		</td>
	</tr>
	";
	}
}
?>
</table>
</body>
</html>
	