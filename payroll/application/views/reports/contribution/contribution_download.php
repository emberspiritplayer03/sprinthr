<?php
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

//$gross_payroll = 0;
//$month_13th = 0;

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
}

$pe->setActiveSheetIndex(0);
$pe->getActiveSheet()->setTitle('SSS');
include 'application/views/reports/contribution/sheet1_sss.php';

$pe->createSheet(1);
$pe->setActiveSheetIndex(1);
$pe->getActiveSheet()->setTitle('PHIC');
include 'application/views/reports/contribution/sheet2_philhealth.php';

$pe->createSheet(2);
$pe->setActiveSheetIndex(2);
$pe->getActiveSheet()->setTitle('Pag-ibig');
include 'application/views/reports/contribution/sheet3_pagibig.php';

$pe->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Contribution_'. $from .'_to_'. $to .'.xls"');
header('Cache-Control: max-age=0');

//$objWriter = PHPExcel_IOFactory::createWriter($pe, 'Excel2007');
//$objWriter->save('php://output');
//exit;
?>