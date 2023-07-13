<?php
$fname = tempnam("/tmp", "simple.xls");
//include 'application/views/report/payroll_register/_functions.php';

$workbook = &new writeexcel_workbook($fname);
$header =& $workbook->addformat();
$header->set_bold();

$template_name =& $workbook->addformat();
$template_name->set_underline();
$template_name->set_bold();

$label =& $workbook->addformat();
$label->set_size(8);
$label->set_bold();

$label_value_n =& $workbook->addformat();
$label_value_n->set_size(8);
$label_value_n->set_align('center');

$field_name =& $workbook->addformat();
$field_name->set_bold();
$field_name->set_text_wrap();

$data =& $workbook->addformat();
$data->set_align('left');
$data->set_size(8);

$text =& $workbook->addformat(array(size => 8, bold => 0, align => 'left'));
$number =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 0, align => 'right'));
$number_standard =& $workbook->addformat(array(num_format => '#,##0.000', size => 8, bold => 0, align => 'right'));

/*
Custom Formatting :
Cash Advance (Sheet 10)
*/
$field_name_10_f =& $workbook->addformat();
$field_name_10_f->set_font("Calibri");
$field_name_10_f->set_size(9);
$field_name_10_f->set_bold();
$field_name_10_f->set_text_wrap();
$field_name_10_f->set_border(1);
$field_name_10_f->set_border_color('black');

$data_sheet_10_f =& $workbook->addformat();
$data_sheet_10_f->set_border(1);
$data_sheet_10_f->set_border_color('black');
$data_sheet_10_f->set_size(8);

// Accumulated Cash Bond
$field_name_9_f =& $workbook->addformat();
$field_name_9_f->set_bold();
$field_name_9_f->set_text_wrap();
$field_name_9_f->set_border(1);
$field_name_9_f->set_border_color('black');
$field_name_9_f->set_bg_color(17);
$field_name_9_f->set_fg_color(12);
$field_name_9_f->set_font("Courier New");
$field_name_9_f->set_size(9);
$field_name_9_f->set_align('center');

$data_sheet_9_f =& $workbook->addformat();
$data_sheet_9_f->set_font("Courier New");
$data_sheet_9_f->set_border(1);
$data_sheet_9_f->set_border_color('black');
$data_sheet_9_f->set_size(8);


//Billing end of the month

$addr_header_7 =& $workbook->addformat();
$addr_header_7->set_align('right');
$addr_header_7->set_size('8');

$gross_payroll = 0;
$month_13th = 0;

$temp_cutoff_period = G_Cutoff_Period_Finder::findAll();
$temp_end = $temp_cutoff_period[0];
$cutoff_end_date = $temp_end->getPayoutDate();
$temp_start = end($temp_cutoff_period);
$cutoff_start_date = $temp_start->getPayoutDate();
$cutoff_period = array_reverse($temp_cutoff_period);
		
foreach ($employees as $e) {
	$employee[$e->getId()]['employee'] = $e;
	$employee[$e->getId()]['payslip'] = $p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
	$employee[$e->getId()]['payslip_helper'] = $ph = new G_Payslip_Helper($p);
	$employee[$e->getId()]['position'] = G_Employee_Job_History_Finder::findCurrentJob($e)->getName();
	$salary_type = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e)->getType();
	$gross_payroll += $p->getGrossPay();
	$month_13th += $p->get13thMonth();
	if ($salary_type == 'daily_rate') {
		$employee[$e->getId()]['rate'] = $rate_daily = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e)->getBasicSalary();
		$employee[$e->getId()]['rate_hourly'] = $rate_daily / 8;
	} else if ($salary_type == 'hourly_rate') {
		$employee[$e->getId()]['rate'] = $rate_hourly = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e)->getBasicSalary();
		$employee[$e->getId()]['rate_hourly'] = $rate_hourly;	
	}
}

$worksheet = &$workbook->addworksheet('Gross Tabulation');
include 'application/views/reports/payroll_register/sheet1.php';

//$worksheet2 = &$workbook->addworksheet('Net Tabulation');
//include 'application/views/reports/payroll_register/sheet2.php';
//
////$worksheet3 = &$workbook->addworksheet('Payslip');
////include 'application/views/sub_reports/payroll_register/sheet3.php';
//
//$worksheet4 = &$workbook->addworksheet('Service Fee Tabulation');
//include 'application/views/reports/payroll_register/sheet4.php';
//
//$worksheet5 = &$workbook->addworksheet('E.R. Share');
//include 'application/views/reports/payroll_register/sheet5.php';
//
//$worksheet6 = &$workbook->addworksheet('Billing 15th');
//include 'application/views/reports/payroll_register/sheet6.php';
//
//$worksheet7 = &$workbook->addworksheet('Billing end of the Month');
//include 'application/views/reports/payroll_register/sheet7.php';
//
//$worksheet9 = &$workbook->addworksheet('Accumulated Cash Bond');
//include 'application/views/reports/payroll_register/sheet9.php';
//
//$worksheet10 = &$workbook->addworksheet('Cash Advances');
//include 'application/views/reports/payroll_register/sheet10.php';
//
//$worksheet11 = &$workbook->addworksheet('SSS');
//include 'application/views/reports/payroll_register/sheet11.php';
//
//$worksheet12 = &$workbook->addworksheet('PHIC');
//include 'application/views/reports/payroll_register/sheet12.php';
//
//$worksheet13 = &$workbook->addworksheet('Pag-ibig');
//include 'application/views/reports/payroll_register/sheet13.php';
//
//$worksheet14= &$workbook->addworksheet('Emps. Bank Acct. Number');
//include 'application/views/reports/payroll_register/sheet14.php';
//
//$worksheet15= &$workbook->addworksheet('Bank Report');
//include 'application/views/reports/payroll_register/sheet15.php';

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"Payroll_Register_". $from ."_to_". $to .".xls\"");
header("Content-Disposition: inline; filename=\"Payroll_Register_". $from ."_to_". $to .".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);	
?>

