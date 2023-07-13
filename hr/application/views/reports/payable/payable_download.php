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

$pe->setActiveSheetIndex(0);
$pe->getActiveSheet()->setTitle('Uniform');
include 'application/views/reports/payable/sheet1_uniform.php';

$pe->createSheet(1);
$pe->setActiveSheetIndex(1);
$pe->getActiveSheet()->setTitle('Medical');
include 'application/views/reports/payable/sheet2_medical.php';


$pe->createSheet(2);
$pe->setActiveSheetIndex(2);
$pe->getActiveSheet()->setTitle('ID Card');	
include 'application/views/reports/payable/sheet3_id_card.php';

$pe->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Employee_Deduction.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($pe, 'Excel2007');
$objWriter->save('php://output');
exit;
?>