<?php
//$pe->write(0, 0, $company_name, $header);
//$pe->write(1, 0, "CASH ADVANCES", $template_name);
//$pe->write(2, 0, "FOR THE PERIOD", $label);
//$pe->write(2, 1, $period, $label_value_n);
//$pe->write(3, 0, "PAY DATE", $label);
//$pe->write(3, 1, $payout_date, $label_value_n);

$pe->write(0, 0, "CASH ADVANCES", $header);
$pe->write(1, 0, "Period Covered: ". $pcovered, $date_format);
$pe->write(2, 0, "Pay Date: ". $pdate, $date_format);

$pe->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

$pe->setWidth(4, 41, 20);
$pe->setWidth(0, 0, 20);
$pe->setWidth(1, 3, 25);

$pe->write(5, 0, 'Employee Number', $field_name);
$pe->write(5, 1, 'Employee Name', $field_name);
$pe->write(5, 2, 'Amount of C.A.', $field_name);


//$tmp_array = array(
//				array("id" => "WAB-11-001", "name" => "Reyes, Efren Bata", "amount" => "5000"),
//				array("id" => "WAB-11-002", "name" => "Reyes, Efren Bata", "amount" => "6000"),
//				array("id" => "WAB-11-003", "name" => "Reyes, Efren Bata", "amount" => "7000"),
//				array("id" => "WAB-11-004", "name" => "Reyes, Efren Bata", "amount" => "8000"),
//				array("id" => "WAB-11-005", "name" => "Reyes, Efren Bata", "amount" => "9000"),
//		);	
		
$start_row = 6;

$counter = 1;
foreach ($employee as $employee_id => $values) {
	$p = $employee[$employee_id]['payslip'];
	$ph = $employee[$employee_id]['payslip_helper'];
	$e = $employee[$employee_id]['employee'];
	
	$emp[$counter]['id'] = $e->getEmployeeCode();
	$emp[$counter]['name'] = $e->getName();
	$emp[$counter]['amount'] = $ph->getValue('cash advance');
	$counter++;
}

foreach($emp as $key => $value):
	$pe->write($start_row, 0, $emp[$key]['id'], $data);
	$pe->write($start_row, 1, $emp[$key]['name'], $data); 
	$pe->write($start_row, 2, $emp[$key]['amount'], $number);
	$start_row++;
endforeach;
?>