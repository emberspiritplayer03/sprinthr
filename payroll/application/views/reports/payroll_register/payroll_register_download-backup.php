<?php
//$fname = tempnam("/tmp", "simple.xls");
//include 'application/views/report/payroll_register/_functions.php';

$pe = new G_Excel_Writer();
$pe->getDefaultStyle()->getFont()->setName('Arial');
$pe->getDefaultStyle()->getFont()->setSize(8); 

$header = array(
	'font' => array(
		'bold' => true
	)
);
$field_name = array(
	'font' => array(
		'bold' => true,
		'size' => 9
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'wrap' => true
	)
);
$template_name = array(
	'font' => array(
		'bold' => true
	)
);
$label = array(
	'font' => array(
		'bold' => true
	)
);
$label_value_n = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);
$center = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);
$data = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	)
);
$text = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	)
);

$number = array(
	'numberformat' => array(
		'code' => '#,##0.00'
	)
);

$total_format = array(
	'numberformat' => array(
		'code' => '#,##0.00'
	),
	'font' => array(
		'bold' => true
	)	
);

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
	$accumulated_payslips = G_Payslip_Finder::findAllByEmployeeAndPayoutDateRange($e, $cutoff_start_date, $cutoff_end_date);
	foreach ($accumulated_payslips as $accumulated_payslip) {
		$employee[$e->getId()]['accumulated_payslips'][$accumulated_payslip->getPayoutDate()] = $accumulated_payslip;
	}
		
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

$pe->setActiveSheetIndex(0);
$pe->getActiveSheet()->setTitle('Gross Tabulation');
include 'application/views/reports/payroll_register/sheet1.php';

$pe->createSheet(1);
$pe->setActiveSheetIndex(1);
$pe->getActiveSheet()->setTitle('Net Tabulation');
include 'application/views/reports/payroll_register/sheet2.php';

$pe->createSheet(2);
$pe->setActiveSheetIndex(2);
$pe->getActiveSheet()->setTitle('Service Fee Tabulation');
include 'application/views/reports/payroll_register/sheet4.php';

$pe->createSheet(3);
$pe->setActiveSheetIndex(3);
$pe->getActiveSheet()->setTitle('E.R. Share');
include 'application/views/reports/payroll_register/sheet5.php';

$pe->createSheet(4);
$pe->setActiveSheetIndex(4);
$pe->getActiveSheet()->setTitle('Billing');
include 'application/views/reports/payroll_register/sheet6.php';

$pe->createSheet(5);
$pe->setActiveSheetIndex(5);
$pe->getActiveSheet()->setTitle('Accumulated 13th Month');
include 'application/views/reports/payroll_register/sheet8.php';

$pe->createSheet(6);
$pe->setActiveSheetIndex(6);
$pe->getActiveSheet()->setTitle('Accumulated Cash Bond');
include 'application/views/reports/payroll_register/sheet9.php';

$pe->createSheet(7);
$pe->setActiveSheetIndex(7);
$pe->getActiveSheet()->setTitle('Cash Advances');
include 'application/views/reports/payroll_register/sheet10.php';

$pe->setActiveSheetIndex(0);

// Redirect output to a client's web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Payroll_Register_'. $from .'_to_'. $to .'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($pe, 'Excel2007');
$objWriter->save('php://output');
exit;
?>

