<?php
$pe->write(0, 0, $company_name, $header);

$pe->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$pe->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$pe->getActiveSheet()->getColumnDimension('D')->setWidth(8);
$pe->getActiveSheet()->getColumnDimension('E')->setWidth(18);
$pe->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$pe->getActiveSheet()->getColumnDimension('H')->setWidth(23);
$pe->getActiveSheet()->mergeCells('A1:D1');
$pe->getActiveSheet()->mergeCells('B4:D4');
$pe->getActiveSheet()->mergeCells('E4:E5');
$pe->getActiveSheet()->mergeCells('A4:A5');
$pe->getActiveSheet()->mergeCells('F4:F5');
$pe->getActiveSheet()->mergeCells('G4:G5');
$pe->write(1, 0, 'Pay Date:', $text);
$pe->write(1, 1, date('M j, Y', strtotime($payout_date)), $data);
$pe->write(1, 2, 'SEMI-MONTHLY', $data);
$pe->write(3, 1, 'EMPLOYEE NAME', $field_name);
$pe->write(4, 1, 'Last', $field_name);
$pe->write(4, 2, 'First', $field_name);
$pe->write(4, 3, 'M.I.', $field_name);
$pe->write(3, 4, 'ACCOUNT NUMBER', $field_name);
$pe->write(3, 0, 'No.', $field_name);
$pe->write(3, 5, 'NET INCOME', $field_name);
$pe->write(3, 6, 'Ded.', $field_name);
$pe->write(3, 7, 'for Individual Account', $field_name);
$pe->write(4, 7, ' Loading/Crediting', $field_name);

$fix_start_row = 5;
$start_row = $fix_start_row;
$counter = 1;
foreach ($employee as $employee_id => $values) {
	$p = $employee[$employee_id]['payslip'];
	//$ph = $employee[$employee_id]['payslip_helper'];
	$e = $employee[$employee_id]['employee'];
	
	$account_number = '';
	$bank_accounts = G_Employee_Direct_Deposit_Finder::findByEmployeeId($e->getId());
	foreach ($bank_accounts as $bank_account) {
		$account_number = $bank_account->getAccount();	
	}
	
	$middle_initial = '';
	$middle_name = $e->getMiddlename();
	if ($middle_name) {
		$middle_initial = $middle_name[0] .'.';
	}	
	
	$pe->write($start_row, 0, $counter, $center);
	$pe->write($start_row, 1, $e->getLastname(), $text);
	$pe->write($start_row, 2, $e->getFirstname(), $text);
	$pe->write($start_row, 3, $middle_initial, $text);
	$pe->write($start_row, 4, $account_number, $text);
	$pe->write($start_row, 5, $p->getNetPay(), $number);
	$pe->write($start_row, 6, 10, $number);
 	$pe->write($start_row, 7, "=SUM(F". ($start_row + 1) ."-G". ($start_row + 1) .")", $number);
	$start_row++;
	$counter++;
}

$new_row = $start_row + 1;
$pe->write($new_row, 4, 'PHP', $right_bold);
$pe->write($new_row, 5, '=SUM(F'. $fix_start_row .':F'. $start_row .')', $total_format);
$pe->write(($new_row + 1), 5, 'TOTAL CHECK', $center);
$pe->write(($new_row + 2), 5, 'WITHDRAWAL', $center);
$pe->write($new_row, 7, '=SUM(H'. $fix_start_row .':H'. $start_row .')', $total_format);
$pe->write(($new_row + 1), 7, 'TOTAL NET AMOUNT', $center);
$pe->write(($new_row + 2), 7, 'PER CASH DEPOSIT SLIP', $center);
?>