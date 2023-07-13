<?php

$pcovered = $period;
$pdate    = $payout_date;

//Custom Format
//$date_format  = $workbook->addformat(array(num_format => 'mm/dd/yy', size => 8, align => 'left', color => 'blue'));
//$date_format->set_bold();
//$emp_format   = $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 0, align => 'right',color =>'red'));
//$total_format = $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 1, align => 'right',color =>'blue'));
//

//Header
$pe->write(0, 0, $company_name, $header);
$pe->write(1, 0, "GROSS PAYROLL TABULATION", $header);
$pe->write(2, 0, "Period Covered: ". $pcovered, $date_format);
$pe->write(3, 0, "Pay Date: ". $pdate, $date_format);
//End Header

//Column Header

$pe->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

$pe->setWidth(5, 41, 10);
$pe->setWidth(0, 0, 10);
$pe->setWidth(1, 3, 15);

$pe->setWidth(5, 5, 15);
$pe->setWidth(5, 7, 30);
$pe->setWidth(5, 9, 20);
$pe->setWidth(5, 10, 20);
$pe->setWidth(5, 11, 20);

$pe->write(5, 0, 'Id No.', $field_name);
$pe->write(5, 1, 'Name', $field_name);
$pe->write(5, 2, 'Position', $field_name);
$pe->write(5, 3, 'Daily Rate', $field_name);
$pe->write(5, 4, 'Wrk Dys.', $field_name);
$pe->write(5, 5, 'Meal Allow', $field_name);
$pe->write(5, 6, 'O.T', $field_name);
$pe->write(5, 7, 'Other Pay / (HOLIDAY)', $field_name);
$pe->write(5, 8, 'Earnings', $field_name);
$pe->write(5, 9, 'A/R Items (Client)', $field_name);
$pe->write(5, 10, 'Gross Pay', $field_name);
$pe->write(5, 11, '13th Month', $field_name);
//End Column Header

//Array
$start_row = 6;

$counter = 1;
foreach ($employee as $employee_id => $values) {
	$p = $employee[$employee_id]['payslip'];
	$ph = $employee[$employee_id]['payslip_helper'];
	$e = $employee[$employee_id]['employee'];
	
	$emp[$counter]['name'] = $e->getName();
	$emp[$counter]['emp_id'] = $e->getEmployeeCode();
	$emp[$counter]['position'] = $employee[$employee_id]['position'];
	$emp[$counter]['daily_rate'] = $employee[$employee_id]['rate'];
	$emp[$counter]['wrk_days'] = $ph->getValue('days_worked');
	$emp[$counter]['meal_allwnce'] = $ph->getValue('meal allowance');
	$emp[$counter]['ot'] = $ph->getValue('overtime');
	$emp[$counter]['other_pay'] = (float) $ph->getValue('others') + (float) $ph->getValue('holiday') + (float) $ph->getValue('nightshift');
	$emp[$counter]['ar_items'] = abs((float) $ph->getValue('accounts receivable'));
	$emp[$counter]['gross_pay'] = $p->getGrossPay();
	$emp[$counter]['13th_month'] = $p->get13thMonth();
	$counter++;
}

$write_col = $start_row;
foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$pe->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$pe->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$pe->write($start_row,2,$emp[$key]['position'],$data); //col 2 position
	$pe->write($start_row,3,$emp[$key]['daily_rate'],$number);//col 3 daily rate
	$pe->write($start_row,4,$emp[$key]['wrk_days'],$emp_format);//col 4 work days
	$pe->write($start_row,5,$emp[$key]['meal_allwnce'],$number);//col 5 meal allowance
	$pe->write($start_row,6,$emp[$key]['ot'],$number);//col 6 ot
	$pe->write($start_row,7,$emp[$key]['other_pay'],$number);//col 7 other pay			
	$pe->write($start_row,9,$emp[$key]['ar_items'],$emp_format);//col 9 ar items
	$pe->write($start_row,10,$emp[$key]['gross_pay'],$number);//col 10 gross pay
	$pe->write($start_row,11,$emp[$key]['13th_month'],$number);//col 11 13th month
	
	//Compute Earnings
	$pe->write($start_row ,8,'=(D' . $write_col . '*E' . $write_col . '+F' . $write_col . '+G' . $write_col . '+H' . $write_col.')',$number);
	//End Compute Earnings
	$start_row++;	
}

//Footer
$pe->write($start_row,0,"Total",$field_name);
$pe->write($start_row ,3,'=SUM(D7' . ':D' . $write_col . ')',$total_format);	
$pe->write($start_row ,4,'=SUM(E7' . ':E' . $write_col . ')',$total_format);	
$pe->write($start_row ,5,'=SUM(F7' . ':F' . $write_col . ')',$total_format);	
$pe->write($start_row ,6,'=SUM(G7' . ':G' . $write_col . ')',$total_format);	
$pe->write($start_row ,7,'=SUM(H7' . ':H' . $write_col . ')',$total_format);	
$pe->write($start_row ,8,'=SUM(I7' . ':I' . $write_col . ')',$total_format);	
$pe->write($start_row ,9,'=SUM(J7' . ':J' . $write_col . ')',$total_format);	
$pe->write($start_row ,10,'=SUM(K7' . ':K' . $write_col . ')',$total_format);	
$pe->write($start_row ,11,'=SUM(L7' . ':L' . $write_col . ')',$total_format);	
//End Footer



?>