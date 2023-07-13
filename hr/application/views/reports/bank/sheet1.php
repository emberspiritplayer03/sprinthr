<?php
$pe->write(0, 0, 'BANK ACCOUNT NUMBER MONITORING', $header);

$pe->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$pe->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$pe->getActiveSheet()->getColumnDimension('C')->setWidth(8);
$pe->getActiveSheet()->getColumnDimension('D')->setWidth(18);
$pe->getActiveSheet()->mergeCells('A1:D1');
$pe->getActiveSheet()->mergeCells('A3:C3');
$pe->getActiveSheet()->mergeCells('D3:D4');
$pe->write(2, 0, 'EMPLOYEE NAME', $field_name);
$pe->write(3, 0, 'Last', $field_name);
$pe->write(3, 1, 'First', $field_name);
$pe->write(3, 2, 'M.I.', $field_name);
$pe->write(2, 3, 'ACCOUNT NUMBER', $field_name);

$start_row = 4;

foreach ($employee as $employee_id => $values) {
	$p = $employee[$employee_id]['payslip'];
	$ph = $employee[$employee_id]['payslip_helper'];
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
	
	$pe->write($start_row, 0, $e->getLastname(), $text);
	$pe->write($start_row, 1, $e->getFirstname(), $text);
	$pe->write($start_row, 2, $middle_initial, $text);
	$pe->write($start_row, 3, $account_number, $text);
	$start_row++;
}
?>