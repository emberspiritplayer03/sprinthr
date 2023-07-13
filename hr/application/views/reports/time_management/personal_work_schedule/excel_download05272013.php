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
$pe->getActiveSheet()->setTitle('Daily Work Schedule');

//foreach ($data as $e) {
//	$candidate[$e['id']]['applicant'] = $e;
//}

$pe->write(0, 0, 'Daily Work Schedule', $header);

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
$pe->write(2, 0, $department_name, $field_name);

$pe->write(3, 0, 'Department', $field_name);
$pe->write(3, 1, 'Employee Code', $field_name);
$pe->write(3, 2, 'Employee Name', $field_name);
$pe->write(3, 3, 'Working Schedule', $field_name);
$pe->write(3, 4, 'Time In', $field_name);
$pe->write(3, 5, 'Time Out', $field_name);

$start_row = 4;

foreach ($data as $key => $val) {
	//$e = $values[$key]['employee'];
	
	$pe->write($start_row, 0, $val['lastname'], $text);
	$pe->write($start_row, 1, $val['firstname'], $text);
	$pe->write($start_row, 2, $val['middlename'], $text);
	$pe->write($start_row, 3, $val['extension_name'], $text);
	
	$start_row++;
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DAILY_WORK_SCHEDULE.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($pe, 'Excel5');
$objWriter->save('php://output');
exit;
?>