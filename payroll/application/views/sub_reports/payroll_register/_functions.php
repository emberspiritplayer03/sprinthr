<?php
function get_remarks($payslip) {
	$other_earnings = (array) $payslip->getOtherEarnings(Earning::EARNING_TYPE_ADJUSTMENT);
	foreach ($other_earnings as $e) {
		$earnings[] = $e->getLabel();			
	}
	return implode(', ', $earnings);
}
?>