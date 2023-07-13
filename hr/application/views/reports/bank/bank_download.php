<?php
$pe = new G_Excel_Writer();
$pe->getDefaultStyle()->getFont()->setName('Arial');
$pe->getDefaultStyle()->getFont()->setSize(9); 

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

$right_bold = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	),
	'font' => array(
		'bold' => true,
		'size' => 9
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

foreach ($employees as $e) {
	$employee[$e->getId()]['employee'] = $e;
	$employee[$e->getId()]['payslip'] = $p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
	if ($p) {
		$payout_date = $p->getPayoutDate();	
	}
	$employee[$e->getId()]['payslip_helper'] = $ph = new G_Payslip_Helper($p);
}

$pe->setActiveSheetIndex(0);
$pe->getActiveSheet()->setTitle('Emps. Bank Acct. Number');
include 'application/views/reports/bank/sheet1.php';

$pe->createSheet(1);
$pe->setActiveSheetIndex(1);
$pe->getActiveSheet()->setTitle('Bank Report');
include 'application/views/reports/bank/sheet2.php';

$pe->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Bank_Report_'. $payout_date .'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($pe, 'Excel2007');
$objWriter->save('php://output');
exit;
?>