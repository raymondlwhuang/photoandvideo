<table   style='border-style: solid;border-color:#0000ff;border-width: 3px;' width="100%">
	<tr>
	<th  style='border-style: solid;border-color:#0000ff;border-width: 3px;'>
	<p>Reward To</p>
	</th>
    <th  style='border-style: solid;border-color:#0000ff;border-width: 3px;'>
	<p>Date</p>
	</th>
	<th  style='border-style: solid;border-color:#0000ff;border-width: 3px;'>
	<p>Amount</p>
	</th>
	<th  style='border-style: solid;border-color:#0000ff;border-width: 3px;'>
	<p>Signature</p>
	</th>
	<th  style='border-style: solid;border-color:#0000ff;border-width: 3px;'>
	<p>Description</p>
	</th>	
	</tr>
<?php
$CarlyAmt = 0;
$CarlySignature = 0;
$JessicaAmt = 0;
$JessicaSignature = 0;
$CarlyTotal = 0;
$JessicaTotal = 0;
while($nt=mysql_fetch_array($result)){
echo "
<tr>
    <td  style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
	<a href='../PHP/KidsReward.php?searchID=$nt[id]'>";
	if (@$nt[rewardid] == '1') {
	echo "Carly"; 
	$CarlyAmt += $nt['amount'];
	$CarlySignature += $nt['signature'];
	$CarlyTotal = $CarlyAmt + $CarlySignature * 2;
	}
	ELSE {
	ECHO 'Jessica';	
	$JessicaAmt += $nt['amount'];
	$JessicaSignature += $nt['signature'];
	$JessicaTotal = $JessicaAmt + $JessicaSignature * 2;
	}
echo "
</a></td>
    <td  style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>";
	echo @substr($nt[date],-5);
echo "	
	</td>
	<td align='right'  style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
	<a href='../PHP/KidsReward.php?searchID=$nt[id]'>$nt[amount]</a>
	</td>
	<td  style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
	<a href='../PHP/KidsReward.php?searchID=$nt[id]'>$nt[signature]</a>
	</td>
	<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
	<font color = blue>$nt[description]&nbsp;</font>
	</td>
</tr>
";
}
echo "
<tr>
<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;' colspan='2'>
	Carly Total Now
</td>	
<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
	$CarlyAmt
</td>	
<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
	$CarlySignature
</td>
<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
\$$CarlyTotal
</td>
</tr>
<tr>	
<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;' colspan='2'>
	Jessica Total Now
</td>	
<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
	$JessicaAmt
</td>	
<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
	$JessicaSignature
</td>	
<td style='border-style: solid;border-color:#0000ff;border-width: 3px;text-align:right;'>
\$$JessicaTotal  
</td>
</tr>";
?>
</table>
