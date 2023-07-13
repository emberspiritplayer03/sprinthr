<?php

$pcovered = $period;
$pdate    = $payout_date;

//Custom Format
//$date_format  =& $workbook->addformat(array(num_format => 'mm/dd/yy', size => 8, align => 'left', color => 'blue'));
//$date_format->set_bold();
//$emp_format   =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 0, align => 'right',color =>'red'));
//$total_format =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 1, align => 'right',color =>'blue'));
//

//Header
$pe->write(0, 0, $company_name, $header);
$pe->write(1, 0, "ACCUMULATED 13th Month", $header);
//$pe->write(2, 0, "Period Covered:", $data);
//$pe->write(2, 1, $pcovered, $date_format);
//$pe->write(3, 0, "Pay Date:", $data);
//$pe->write(3, 1, $pdate, $date_format);
//End Header

//Column Header
$pe->setWidth(5, 41, 10);
$pe->setWidth(0, 0, 10);
$pe->setWidth(1, 3, 15);

$pe->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

foreach ($cutoff_period as $cutoff) {
	$dates[$cutoff->getPayoutDate()] = $cutoff->getPayoutDate();
}
		
$pe->write(5, 0, 'Id No.', $field_name);
$pe->write(5, 1, 'Name', $field_name);
$pe->write(5, 2, 'Position', $field_name);
$pe->write(5, 3, 'Daily Rate', $field_name);

$column = 4;
foreach ($dates as $the_date => $value) {
	$pe->write(5, $column, date('d-M', strtotime($the_date)), $field_name);
	$column++;
}

$pe->write(5, $column, 'Total', $field_name);

// VALUES
$start_row = 6;

$counter = 1;
$grant_total = 0;
foreach ($employee as $employee_id => $values) {
	$p = $employee[$employee_id]['payslip'];
	$ph = $employee[$employee_id]['payslip_helper'];
	$e = $employee[$employee_id]['employee'];
	
	$emp[$counter]['name'] = $e->getName();
	$emp[$counter]['emp_id'] = $e->getEmployeeCode();
	$emp[$counter]['position'] = $employee[$employee_id]['position'];
	$emp[$counter]['daily_rate'] = $employee[$employee_id]['rate'];
	
	$total = 0;
	foreach ($dates as $payout_date => $value) {
		$obj_accumulated_payslip = $employee[$employee_id]['accumulated_payslips'][$payout_date];
		if ($obj_accumulated_payslip) {
			$emp[$counter][$payout_date] = (float) $obj_accumulated_payslip->get13thMonth();
			if ($emp[$counter][$payout_date] == 0) {
				$emp[$counter][$payout_date] = '-';
			}			
			$total += (float) $obj_accumulated_payslip->get13thMonth();
		} else {
			$emp[$counter][$payout_date] = '-';
		}
	}
	$emp[$counter]['total'] = $total;
	$grand_total += $total;
	$counter++;	
}

$write_col = $start_row;
foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	
	$pe->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$pe->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$pe->write($start_row,2,$emp[$key]['position'],$data); //col 2 position
	$pe->write($start_row,3,$emp[$key]['daily_rate'],$number);//col 3 daily rate
	
	$counter = 4;
	foreach ($dates as $the_date => $value) {
		if ($emp[$key][$the_date] == 0) {
			$pe->write($start_row,$counter,$emp[$key][$the_date], $center);
		} else {
			$pe->write($start_row,$counter,$emp[$key][$the_date],$number);
		}
		$counter++;
	}
	$pe->write($start_row,$counter,$emp[$key]['total'],$number);
	$start_row++;	
}

$pe->write($start_row ,$counter, $grand_total,$total_format);

//Footer
//$pe->write($start_row,0,"Total",$field_name);
//$pe->write_formula($start_row ,3,'SUM(D7' . ':D' . $write_col . ')',$total_format);	
//$pe->write_formula($start_row ,4,'SUM(E7' . ':E' . $write_col . ')',$total_format);	
//$pe->write_formula($start_row ,5,'SUM(F7' . ':F' . $write_col . ')',$total_format);	
//$pe->write_formula($start_row ,6,'SUM(G7' . ':G' . $write_col . ')',$total_format);	
//$pe->write_formula($start_row ,7,'SUM(H7' . ':H' . $write_col . ')',$total_format);	
//$pe->write_formula($start_row ,8,'SUM(I7' . ':I' . $write_col . ')',$total_format);	
//$pe->write_formula($start_row ,9,'SUM(J7' . ':J' . $write_col . ')',$total_format);	
//$pe->write_formula($start_row ,10,'SUM(K7' . ':K' . $write_col . ')',$total_format);	
//$pe->write_formula($start_row ,11,'SUM(L7' . ':L' . $write_col . ')',$total_format);	
//End Footer
?>