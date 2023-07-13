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
$pe->write(1, 0, "MANDATED ER SHARE TABULATION (MONTHLY)", $header);
$pe->write(2, 0, "Period Covered: ". $pcovered, $date_format);
//End Header

//Column Header
$pe->setWidth(4,41,10);
$pe->setWidth(0,0,10);
$pe->setWidth(1,3,15);

$pe->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

$pe->write(5, 0, 'Id No.', $field_name);
$pe->write(5, 1, 'Name', $field_name);
$pe->write(5, 2, 'Position', $field_name);
$pe->write(5, 3, 'SSS - ER Share', $field_name);
$pe->write(5, 4, 'PHIC - ER Share', $field_name);
$pe->write(5, 5, 'HDMF - ER Share', $field_name);

//End Column Header

//Array
$start_row = 6;

$counter = 1;
$total_contribution = 0;
foreach ($employee as $employee_id => $values) {
	$p = $employee[$employee_id]['payslip'];
	$ph = $employee[$employee_id]['payslip_helper'];
	$e = $employee[$employee_id]['employee'];
	
	$emp[$counter]['name'] = $e->getName();
	$emp[$counter]['emp_id'] = $e->getEmployeeCode();
	$emp[$counter]['position'] = $employee[$employee_id]['position'];
		
	$emp[$counter]['sss'] = $ph->getValue('sss');
	$total_contribution += $emp[$counter]['sss'];
	$emp[$counter]['phic'] = $ph->getValue('philhealth');
	$total_contribution += $emp[$counter]['phic'];
	$emp[$counter]['hdmf'] = $ph->getValue('pagibig');
	$total_contribution += $emp[$counter]['hdmf'];
	$counter++;
}

$write_col = $start_row;
foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$pe->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$pe->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$pe->write($start_row,2,$emp[$key]['position'],$data); //col 2 position
	$pe->write($start_row,3,$emp[$key]['sss'],$number);//col 3 sss 
	$pe->write($start_row,4,$emp[$key]['phic'],$number);//col 4 phic 
	$pe->write($start_row,5,$emp[$key]['hdmf'],$number);//col 5 hdmf 
	$start_row++;	
}

//Footer
$pe->write($start_row,0,"Total",$field_name);
$pe->write($start_row ,3,'=SUM(D7' . ':D' . $write_col . ')',$total_format);	
$pe->write($start_row ,4,'=SUM(E7' . ':E' . $write_col . ')',$total_format);	
$pe->write($start_row ,5,'=SUM(F7' . ':F' . $write_col . ')',$total_format);	
//End Footer
?>