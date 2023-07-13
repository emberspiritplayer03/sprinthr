<?php

$pcovered = $period;
$pdate    = $payout_date;

//Custom Format
$date_format  =& $workbook->addformat(array(num_format => 'mm/dd/yy', size => 8, align => 'left', color => 'blue'));
$date_format->set_bold();
$emp_format   =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 0, align => 'right',color =>'red'));
$total_format =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 1, align => 'right',color =>'blue'));
//

//Header
$worksheet9->write(0, 0, $company_name, $header);
$worksheet9->write(1, 0, "ACCUMULATED CASH BOND", $header);
//$worksheet9->write(2, 0, "Period Covered:", $data);
//$worksheet9->write(2, 1, $pcovered, $date_format);
//$worksheet9->write(3, 0, "Pay Date:", $data);
//$worksheet9->write(3, 1, $pdate, $date_format);
//End Header

//Column Header
$worksheet9->set_column(5, 41, 10);
$worksheet9->set_column(0, 0, 10);
$worksheet9->set_column(1, 3, 15);

//$month_start = date('Y-m-01', strtotime($cutoff_start_date));
//$month_end = date('Y-m-01', strtotime($cutoff_end_date));

//$mktime_start = strtotime($month_start);
//$mktime_end = strtotime($month_end);
		
//while ($mktime_start <= $mktime_end) {
//	$first = date('Y-m-15', strtotime($month_start));			
//	$dates[$first] = $first;
//	$second = date('Y-m-t', strtotime($month_start));
//	$dates[$second] = $second;
//	
//	$month_start = date('Y-m-d', strtotime($month_start . "+ 1 month"));
//	$mktime_start = strtotime($month_start);
//}
foreach ($cutoff_period as $cutoff) {
	$dates[$cutoff->getPayoutDate()] = $cutoff->getPayoutDate();
}
		
$worksheet9->write(5, 0, 'Id No.', $field_name);
$worksheet9->write(5, 1, 'Name', $field_name);
$worksheet9->write(5, 2, 'Position', $field_name);
$worksheet9->write(5, 3, 'Daily Rate', $field_name);

$column = 4;
foreach ($dates as $the_date => $value) {
	$worksheet9->write(5, $column, date('d-M', strtotime($the_date)), $field_name);
	$column++;
}

$worksheet9->write(5, $column, 'Total', $field_name);

// VALUES
$start_row = 6;

$counter = 1;
foreach ($employee as $employee_id => $values) {
	$p = $employee[$employee_id]['payslip'];
	$ph = $employee[$employee_id]['payslip_helper'];
	$e = $employee[$employee_id]['employee'];
	$payslip_cash_bond = G_Payslip_Finder::findByEmployeeAndPayoutDate($e, $cutoff_start_date, $cutoff_end_date);
	$ph_cash_bond = new G_Payslip_Helper($payslip_cash_bond);
	
	$emp[$counter]['name'] = $e->getName();
	$emp[$counter]['emp_id'] = $e->getEmployeeCode();
	$emp[$counter]['position'] = $employee[$employee_id]['position'];
	$emp[$counter]['daily_rate'] = $employee[$employee_id]['rate'];
	
	$total = 0;
	foreach ($dates as $the_date => $value) {		
		$emp[$counter][$the_date] = (float) $ph_cash_bond->getValue('cash bond');
		$total += (float) $ph_cash_bond->getValue('cash bond');
	}
	$emp[$counter]['total'] = $total;
	$counter++;	
}

foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	
	$worksheet9->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$worksheet9->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$worksheet9->write($start_row,2,$emp[$key]['position'],$data); //col 2 position
	$worksheet9->write($start_row,3,$emp[$key]['daily_rate'],$number);//col 3 daily rate
	
	$counter = 4;
	foreach ($dates as $the_date => $value) {
		$worksheet9->write($start_row,$counter,$emp[$key][$the_date],$number);
		$counter++;
	}
	$worksheet9->write($start_row,$counter,$emp[$key]['total'],$number);
	$start_row++;	
}

//Footer
//$worksheet9->write($start_row,0,"Total",$field_name);
//$worksheet9->write_formula($start_row ,3,'SUM(D7' . ':D' . $write_col . ')',$total_format);	
//$worksheet9->write_formula($start_row ,4,'SUM(E7' . ':E' . $write_col . ')',$total_format);	
//$worksheet9->write_formula($start_row ,5,'SUM(F7' . ':F' . $write_col . ')',$total_format);	
//$worksheet9->write_formula($start_row ,6,'SUM(G7' . ':G' . $write_col . ')',$total_format);	
//$worksheet9->write_formula($start_row ,7,'SUM(H7' . ':H' . $write_col . ')',$total_format);	
//$worksheet9->write_formula($start_row ,8,'SUM(I7' . ':I' . $write_col . ')',$total_format);	
//$worksheet9->write_formula($start_row ,9,'SUM(J7' . ':J' . $write_col . ')',$total_format);	
//$worksheet9->write_formula($start_row ,10,'SUM(K7' . ':K' . $write_col . ')',$total_format);	
//$worksheet9->write_formula($start_row ,11,'SUM(L7' . ':L' . $write_col . ')',$total_format);	
//End Footer
?>