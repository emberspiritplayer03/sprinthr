<?php
$payment_name = 'id card';
$pe->write(0, 0, 'EMPLOYEES ID CARD DEDUCTION MONITORING', $header);

$pe->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$pe->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$pe->getActiveSheet()->mergeCells('C3:F3');

$pe->write(2, 0, 'Employee No.', $field_name);
$pe->write(2, 1, 'Employee Name', $field_name);
$pe->write(2, 2, 'Payment', $field_name);
$pe->write(2, 6, 'Amount Paid', $field_name);
$pe->write(2, 7, 'Total Amount', $field_name);
$pe->write(2, 8, 'Balance', $field_name);

$start_row = 3;
foreach ($payments as $p) {
	if (strtolower($p->getName()) == $payment_name) {
		$employee_id = $p->getEmployeeId();
		$e = Employee_Factory::get($employee_id);
		$histories = $p->getPaymentHistories();
		$total_amount = $p->getTotalAmount();
		
		$pe->write($start_row, 0, $e->getEmployeeCode(), $data);
		$pe->write($start_row, 1, $e->getName(), $data);	
		
		$history_column = 2;
		$total_amount_paid = 0;
		foreach ($histories as $h) {
			if ($h->getAmountPaid() > 0) {
				$amount_paid = $h->getAmountPaid();
				$total_amount_paid += $amount_paid;
				
				$pe->write($start_row, $history_column, $amount_paid, $number);			
				
				$history_column++;
			}
		}
		$pe->write($start_row, 6, $total_amount_paid, $number);
		$pe->write($start_row, 7, $total_amount, $number);
		$pe->write($start_row, 8, ($total_amount - $total_amount_paid), $number);

		$start_row++;		
	}	
}
?>