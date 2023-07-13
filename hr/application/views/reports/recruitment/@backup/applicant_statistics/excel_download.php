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
$pe->getActiveSheet()->setTitle('Applicant List');

//foreach ($data as $e) {
//	$candidate[$e['id']]['applicant'] = $e;
//}

$pe->write(0, 0, 'Applicant List', $header);

$pe->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$pe->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$pe->getActiveSheet()->getColumnDimension('C')->setWidth(16);
$pe->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$pe->getActiveSheet()->getColumnDimension('E')->setWidth(22);
$pe->getActiveSheet()->mergeCells('A1:D1');
$pe->getActiveSheet()->mergeCells('A3:C3');
//$pe->getActiveSheet()->mergeCells('D3:D4');
$pe->write(2, 0, 'EMPLOYEE NAME', $field_name);

$pe->write(3, 0, 'Lastname', $field_name);
$pe->write(3, 1, 'Firstname', $field_name);
$pe->write(3, 2, 'Middlename', $field_name);
$pe->write(3, 3, 'Extension Name', $field_name);
$pe->write(3, 4, 'Date Applied', $field_name);

$start_row = 4;

foreach ($data as $key => $val) {
	//$e = $values[$key]['employee'];
	
	$pe->write($start_row, 0, $val['lastname'], $text);
	$pe->write($start_row, 1, $val['firstname'], $text);
	$pe->write($start_row, 2, $val['middlename'], $text);
	$pe->write($start_row, 3, $val['extension_name'], $text);
	$pe->write($start_row, 4, $val['applied_date_time'], $text);
	$start_row++;
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="APPLICANT_LIST.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($pe, 'Excel5');
$objWriter->save('php://output');
exit;
?>