<?php
$pcovered = $period;
$pdate    = $payout_date;

//Custom Format
//$date_format      =& $workbook->addformat(array(num_format => 'mm/dd/yy', size => 8, align => 'left', color => 'blue'));
//$date_format->set_bold();
//$emphasize_format =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 0, align => 'right',color =>'red'));
//$total_format     =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 1, align => 'right',color =>'blue'));
//

//Header
$pe->write(0, 0,  $company_name, $header);
$pe->write(1, 0, "NET PAYROLL TABULATION", $header);
$pe->write(2, 0, "Period Covered: ". $pcovered, $date_format);
$pe->write(3, 0, "Pay Date: ". $pdate, $date_format);
//End Header

//Column Header
$pe->setWidth(5, 5, 15);
$pe->setWidth(5, 7, 15);
$pe->setWidth(5, 8, 15);
$pe->setWidth(5, 9, 15);
$pe->setWidth(5, 10, 8);
$pe->setWidth(5, 11, 8);
$pe->setWidth(5, 12, 8);
$pe->setWidth(5, 13, 10);
$pe->setWidth(5, 14, 11);
$pe->setWidth(5, 15, 15);
$pe->setWidth(5, 23, 15);
$pe->setWidth(5, 24, 20);

$pe->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$pe->write(5, 0, 'Id No.', $field_name);
$pe->write(5, 1, 'Name', $field_name);
$pe->write(5, 2, 'Position', $field_name);
$pe->write(5, 3, 'Daily Rate', $field_name);
$pe->write(5, 4, 'Wrk Dys.', $field_name);
$pe->write(5, 5, 'Meal Allow', $field_name);
$pe->write(5, 6, 'O.T', $field_name);
$pe->write(5, 7, 'Other Pay / (HOLIDAY)', $field_name);
$pe->write(5, 8, 'A/R Items (Client)', $field_name);
$pe->write(5, 9, 'Gross Earnings', $field_name);
$pe->write(5, 10, 'SSS Prem.', $field_name);
$pe->write(5, 11, 'PHIC Prem', $field_name);
$pe->write(5, 12, 'HDMF Prem.', $field_name);
$pe->write(5, 13, 'Rate/Hr.', $field_name);
$pe->write(5, 14, 'Amt Ded/Min', $field_name);
$pe->write(5, 15, 'Total', $field_name);
$pe->write(5, 16, 'I.D.', $field_name);
$pe->write(5, 17, 'Uniform', $field_name);
$pe->write(5, 18, 'Medical', $field_name);
$pe->write(5, 19, 'Cash Bond', $field_name);
$pe->write(5, 20, 'C.A.', $field_name);
$pe->write(5, 21, 'Items/Goods', $field_name);
$pe->write(5, 22, 'P.F.', $field_name);
$pe->write(5, 23, 'Deductions', $field_name);
$pe->write(5, 24, 'Net/Take Home Pay', $field_name);
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
	$emp[$counter]['other_pay'] = number_format((float) $ph->getValue('others') + (float) $ph->getValue('holiday') + (float) $ph->getValue('nightshift'), 3);
	$emp[$counter]['ar_items'] = abs((float) $ph->getValue('accounts receivable'));
	$emp[$counter]['gross_pay'] = $p->getGrossPay();
	$emp[$counter]['sss_prem'] = $ph->getValue('sss');
	$emp[$counter]['phic_prem'] = $ph->getValue('philhealth');
	$emp[$counter]['hdmf_pre'] = $ph->getValue('pagibig');
	$emp[$counter]['rate_hr'] = $employee[$employee_id]['rate_hourly'];
	$emp[$counter]['amt_ded_min'] = (float) $ph->getValue('late_hours') + (float) $ph->getValue('undertime_hours');
	$emp[$counter]['total_late_undertime'] = number_format((float) $ph->getValue('late_amount') + (float) $ph->getValue('undertime_amount'), 3);
	$emp[$counter]['id'] = $ph->getValue('id card');
	$emp[$counter]['uniform'] = $ph->getValue('uniform');
	$emp[$counter]['medical'] = $ph->getValue('medical');
	$emp[$counter]['cash_bond'] = $ph->getValue('cash bond');
	$emp[$counter]['ca'] = $ph->getValue('cash advance');
	$emp[$counter]['items_goods'] = $ph->getValue('items/goods');
	$emp[$counter]['pf'] = $ph->getValue('placement fee');
	$emp[$counter]['deductions'] = $ph->computeTotalDeductions();
	$emp[$counter]['net_pay'] = $p->getNetPay();
	
	$counter++;
}

$write_col = $start_row;
foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$pe->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$pe->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$pe->write($start_row,2,$emp[$key]['position'],$data); //col 2 position
	$pe->write($start_row,3,$emp[$key]['daily_rate'],$number);//col 3 daily rate
	$pe->write($start_row,4,$emp[$key]['wrk_days'],$emphasize_format);//col 4 work days
	$pe->write($start_row,5,$emp[$key]['meal_allwnce'],$number);//col 5 meal allowance
	$pe->write($start_row,6,$emp[$key]['ot'],$number);//col 6 ot
	$pe->write($start_row,7,$emp[$key]['other_pay'],$number);//col 7 other pay	
	$pe->write($start_row,8,$emp[$key]['ar_items'],$emphasize_format);//col 9 ar items
	$pe->write($start_row,9,$emp[$key]['gross_pay'],$number);// col 9 gross pay
	
	//Compute Gross
	//$pe->write($start_row ,9,'D' . $write_col . '*E' . $write_col . '+F' . $write_col . '+G' . $write_col . '+H' . $write_col . '-I' . $write_col,$number);	
	//End Compute Gross		
	
	$pe->write($start_row,10,$emp[$key]['sss_prem'],$number);//col 10 sss prem
	$pe->write($start_row,11,$emp[$key]['phic_prem'],$number);// col11 phic prem
	$pe->write($start_row,12,$emp[$key]['hdmf_pre'],$number);// col12 hdmf prem
	$pe->write($start_row,13,$emp[$key]['rate_hr'],$number);// col13 rate_hr
	$pe->write($start_row,14,$emp[$key]['amt_ded_min'],$emphasize_format);// col14 amt ded min
	$pe->write($start_row,15,$emp[$key]['total_late_undertime'],$emphasize_format);// col 15 total late/undertime
	
	
	//Compute Total 
	//$pe->write($start_row ,15,'N' . $write_col . '*O' . $write_col,$number);	
	//End Compute Gross	
	
	$pe->write($start_row,16,$emp[$key]['id'],$number);// col16 id
	$pe->write($start_row,17,$emp[$key]['uniform'],$number);// col17 uniform
	$pe->write($start_row,18,$emp[$key]['medical'],$number);// col18 medical
	$pe->write($start_row,19,$emp[$key]['cash_bond'],$number);// col19 cash bond
	$pe->write($start_row,20,$emp[$key]['ca'],$number);// col20 ca
	$pe->write($start_row,21,$emp[$key]['items_goods'],$number);// col21 items/goods
	$pe->write($start_row,22,$emp[$key]['pf'],$number);// col22 placement fee
	$pe->write($start_row,23,$emp[$key]['deductions'],$number);// col23 total deductions
	$pe->write($start_row,24,$emp[$key]['net_pay'],$number);// col24 net pay
	
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
$pe->write($start_row ,12,'=SUM(M7' . ':M' . $write_col . ')',$total_format);	
$pe->write($start_row ,13,'=SUM(N7' . ':N' . $write_col . ')',$total_format);	
$pe->write($start_row ,14,'=SUM(O7' . ':O' . $write_col . ')',$total_format);	
$pe->write($start_row ,15,'=SUM(P7' . ':P' . $write_col . ')',$total_format);	
$pe->write($start_row ,16,'=SUM(Q7' . ':Q' . $write_col . ')',$total_format);	
$pe->write($start_row ,17,'=SUM(R7' . ':R' . $write_col . ')',$total_format);	
$pe->write($start_row ,18,'=SUM(S7' . ':S' . $write_col . ')',$total_format);	
$pe->write($start_row ,19,'=SUM(T7' . ':T' . $write_col . ')',$total_format);	
$pe->write($start_row ,20,'=SUM(U7' . ':U' . $write_col . ')',$total_format);	
$pe->write($start_row ,21,'=SUM(V7' . ':V' . $write_col . ')',$total_format);	
$pe->write($start_row ,22,'=SUM(W7' . ':W' . $write_col . ')',$total_format);	
$pe->write($start_row ,23,'=SUM(X7' . ':X' . $write_col . ')',$total_format);	
$pe->write($start_row ,24,'=SUM(Y7' . ':Y' . $write_col . ')',$total_format);	
//End Footer

?>