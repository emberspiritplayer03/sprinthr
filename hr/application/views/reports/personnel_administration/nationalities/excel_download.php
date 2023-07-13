<?php
/*$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');*/
			
$pe = new G_Excel_Writer();
$pe->getDefaultStyle()->getFont()->setName('Arial');
$pe->getDefaultStyle()->getFont()->setSize(9); 

$pe->setActiveSheetIndex(0);
$pe->getActiveSheet()->setTitle('Nationality');

//foreach ($data as $e) {
//	$candidate[$e['id']]['applicant'] = $e;
//}

$pe->write(0, 0, 'Nationality', $header);

$pe->getActiveSheet()->getColumnDimension('A')->setAutoSize(true); //->setWidth(15)
$pe->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$pe->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$pe->getActiveSheet()->mergeCells('A1:D1');
$pe->getActiveSheet()->mergeCells('A3:C3');
//$pe->getActiveSheet()->mergeCells('D3:D4');
$pe->write(2, 0, 'EMPLOYEE NAME', $field_name);

$pe->write(3, 0, 'Employee Code', $field_name);
$pe->write(3, 1, 'Employee Name', $field_name);
$pe->write(3, 2, 'Position', $field_name);
$pe->write(3, 3, 'Nationality', $field_name);
$pe->write(3, 4, 'Employment Status', $field_name);
$start_row = 4;

foreach ($data as $key => $val) {
	
	$pe->write($start_row, 0, $val['employee_code'], $text);
	$pe->write($start_row, 1, $val['employee_name'], $text);
	$pe->write($start_row, 2, $val['position'], $text);
	$pe->write($start_row, 3, $val['nationality'], $text);
	$pe->write($start_row, 4, $val['employment_status'], $text);

	$start_row++;
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="NATIONALITY.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($pe, 'Excel5');
$objWriter->save('php://output');
exit;
?>