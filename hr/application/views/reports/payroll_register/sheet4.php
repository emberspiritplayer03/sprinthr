<?php

$pcovered = $period;
$pdate    = $payout_date;

$sf = G_Service_Fee_Finder::findById(1);
$company_service_fee = $sf->getPercentage();

//Custom Format
//$date_format      =& $workbook->addformat(array(num_format => 'mm/dd/yy', size => 8, align => 'left', color => 'blue'));
//$date_format->set_bold();
//$emphasize_format =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 0, align => 'right',color =>'red'));
//$total_format     =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 1, align => 'right',color =>'blue'));
//

//Header
$pe->write(0, 0,  $company_name, $header);
$pe->write(1, 0, "SERVICE FEE", $header);
$pe->write(2, 0, "Period Covered: ". $pcovered, $date_format);
$pe->write(3, 0, "Pay Date: ". $pdate, $date_format);
//End Header

//Column Header
$pe->setWidth(4,41,10);
$pe->setWidth(0,0,10);
$pe->setWidth(1,3,15);
$pe->setWidth(5,3,8);
$pe->setWidth(4,4,22);
$pe->setWidth(5,5,12);
$pe->setWidth(6,6,15);

$pe->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$pe->write(5, 0, 'Id No.', $field_name);
$pe->write(5, 1, 'Name', $field_name);
$pe->write(5, 2, 'Position', $field_name);
$pe->write(5, 3, 'Daily Rate', $field_name);
$pe->write(5, 4, "S.F Rate ({$company_service_fee}%) per Day", $field_name);
$pe->write(5, 5, 'No. of Days', $field_name);
$pe->write(5, 6, 'Total', $field_name);
//End Column Header

//Array
$start_row = 6;

$counter = 1;

$total_service_fee = 0;
foreach ($employee as $employee_id => $values) {
	$p = $employee[$employee_id]['payslip'];
	$ph = $employee[$employee_id]['payslip_helper'];
	$e = $employee[$employee_id]['employee'];
	
	$emp[$counter]['name'] = $e->getName();
	$emp[$counter]['emp_id'] = $e->getEmployeeCode();
	$emp[$counter]['position'] = $employee[$employee_id]['position'];
	$emp[$counter]['daily_rate'] = $daily_rate = $employee[$employee_id]['rate'];
	$emp[$counter]['service_fee'] = $service_fee = ($daily_rate * $company_service_fee / 100);
	$emp[$counter]['wrk_days'] = $working_days = $ph->getValue('days_worked');
	$emp[$counter]['total'] = $service_fee * $working_days;
	$total_service_fee += $emp[$counter]['total'];
	$counter++;
}

$write_col = $start_row;
foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$pe->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$pe->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$pe->write($start_row,2,$emp[$key]['position'],$data); //col 2 position
	$pe->write($start_row,3,$emp[$key]['daily_rate'],$number);//col 3 daily 
	$pe->write($start_row,4,$emp[$key]['service_fee'],$number); 
	$pe->write($start_row,5,$emp[$key]['wrk_days'],$number);
	$pe->write($start_row,6,$emp[$key]['total'],$number); 
	
	$start_row++;	
}

//Footer
$pe->write($start_row,0,"Total",$field_name);
$pe->write($start_row ,3,'=SUM(D7' . ':D' . $write_col . ')',$total_format);	
$pe->write($start_row ,4,'=SUM(E7' . ':E' . $write_col . ')',$total_format);	
$pe->write($start_row ,5,'=SUM(F7' . ':F' . $write_col . ')',$total_format);	
$pe->write($start_row ,6,'=SUM(G7' . ':G' . $write_col . ')',$total_format);	
//End Footer
?>