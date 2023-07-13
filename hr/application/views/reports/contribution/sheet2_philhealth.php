<?php

$pcovered = $period;
$pdate    = $payout_date;

//Header
$pe->write(0, 0, $company_name, $header);
$pe->write(1, 0, "Employees Philhealth Monitoring", $header);
$pe->write(2, 0, "Period Covered: ". $pcovered, $date_format);
//End Header

//Column Header

$pe->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$pe->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$pe->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$pe->getActiveSheet()->mergeCells('D5:E5');

$pe->write(5, 0, 'Emp. No.', $field_name);
$pe->write(5, 1, 'Emp. Name', $field_name);
$pe->write(5, 2, 'PHIC #', $field_name);
$pe->write(4, 3, 'Share', $field_name);
$pe->write(5, 3, 'EE', $field_name);
$pe->write(5, 4, 'ER', $field_name);
//End Column Header

//Array
$start_row = 6;

$counter = 1;
foreach ($employee as $employee_id => $values) {
	$p = $employee[$employee_id]['payslip'];
	$ph = $employee[$employee_id]['payslip_helper'];
	$e = $employee[$employee_id]['employee'];
	
	$emp[$counter]['emp_id'] = $e->getEmployeeCode();
	$emp[$counter]['name'] = $e->getName();
	$emp[$counter]['phic_number'] = '';//$e->getSssNumber();
	$emp[$counter]['ee'] = $ph->getValue('philhealth');
	$emp[$counter]['er'] = $ph->getValue('philhealth_er');

	$counter++;
}

$write_col = $start_row;
foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$pe->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$pe->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$pe->write($start_row,2,$emp[$key]['phic_number'],$data); //col 2 position
	$pe->write($start_row,3,$emp[$key]['ee'],$number);
	$pe->write($start_row,4,$emp[$key]['er'],$number);
	
	$start_row++;	
}

//Footer
//$pe->write($start_row,0,"Total",$field_name);
//$pe->write($start_row ,3,'=SUM(D7' . ':D' . $write_col . ')',$total_format);	
//$pe->write($start_row ,4,'=SUM(E7' . ':E' . $write_col . ')',$total_format);	
//$pe->write($start_row ,5,'=SUM(F7' . ':F' . $write_col . ')',$total_format);	
//$pe->write($start_row ,6,'=SUM(G7' . ':G' . $write_col . ')',$total_format);	
//$pe->write($start_row ,7,'=SUM(H7' . ':H' . $write_col . ')',$total_format);	
//$pe->write($start_row ,8,'=SUM(I7' . ':I' . $write_col . ')',$total_format);	
//$pe->write($start_row ,9,'=SUM(J7' . ':J' . $write_col . ')',$total_format);	
//$pe->write($start_row ,10,'=SUM(K7' . ':K' . $write_col . ')',$total_format);	
//$pe->write($start_row ,11,'=SUM(L7' . ':L' . $write_col . ')',$total_format);	
//End Footer



?>