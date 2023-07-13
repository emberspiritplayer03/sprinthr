<div>Salary Type : <?php echo $payslip_info['salary_type']; ?> / Monthly Rate : <?php echo $payslip_info['monthly_rate']; ?> / Daily Rate : <?php echo $payslip_info['daily_rate']; ?> / Hourly Rate : <?php echo $payslip_info['hourly_rate']; ?></div>
<hr />
<?php
	echo "<b>EARNINGS</b><br />";
	echo "<table width = '800px' border = '1' padding = '0'>";
	foreach($new_earnings as $n_earning_key => $nearning ) {
		echo "<tr>";
			echo "<td>" . $nearning['label'] . " ( Total Days: " . $nearning['total_days'] . " | Total Hours: " . $nearning['total_hours'] . " )" . "</td>";
			echo "<td>" . Tools::currencyFormat($nearning['amount']) . "</td>";
		echo "</tr>";
	}
	
	foreach ($other_earnings as $oear){
		echo "<tr>";
			echo "<td>" . $oear->getLabel() . "</td>";
			echo "<td>" . Tools::currencyFormat($oear->getAmount()) . "</td>";
		echo "</tr>";
	}
	echo "<tr>";
		echo "<td>Total: </td>";	
		echo "<td>" . Tools::currencyFormat($total_earnings) . "</td>";	
	echo "</tr>";

	echo "</table></br></br>";
?>

<?php 
	echo "<b>DEDUCTIONS</b><br />";
	echo "<table width = '800px' border = '1' padding = '0'>";
	foreach($new_deductions as $n_deduction_key => $ndeduction ) {
		echo "<tr>";
			echo "<td>" . $ndeduction['label'] . "</td>";
			echo "<td>" . Tools::currencyFormat($ndeduction['amount']) . "</td>";
		echo "</tr>";
	}

	foreach($other_deductions as $odeduction ) {
		echo "<tr>";
			echo "<td>" . $odeduction->getLabel() . "</td>";
			echo "<td>" . Tools::currencyFormat($odeduction->getAmount()) . "</td>";
		echo "</tr>";
	}			
	echo "<tr>";
		echo "<td>Total: </td>";	
		echo "<td>" . Tools::currencyFormat($total_deductions) . "</td>";	
	echo "</tr>";	
	echo "</table></br></br>";
?>

<?php 
	echo "<b>PAYSLIP SUMMARY</b><br />";
	echo "<table width = '800px' border = '1' padding = '0'>";
	echo "<tr>";
		echo "<td>Earnings: </td>";
		echo "<td>" . Tools::currencyFormat($total_earnings) . "</td>";
		echo "<td>Deduction: </td>";
		echo "<td>" . Tools::currencyFormat($total_deductions) . "</td>";
		echo "<td>Net Pay: </td>";
		echo "<td>" . Tools::currencyFormat($net_pay) . "</td>";				
	echo "<tr>";
	echo "</table>";	
?>