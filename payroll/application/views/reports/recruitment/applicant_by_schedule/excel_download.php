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
$pe->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$pe->getActiveSheet()->getColumnDimension('C')->setWidth(16);
$pe->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$pe->getActiveSheet()->getColumnDimension('E')->setWidth(22);
$pe->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$pe->getActiveSheet()->mergeCells('A1:D1');
$pe->getActiveSheet()->mergeCells('A3:C3');
//$pe->getActiveSheet()->mergeCells('D3:D4');
$pe->write(2, 0, 'Applicant Schedule', $field_name);

$pe->write(3, 0, 'Event Type', $field_name);
$pe->write(3, 1, 'Position Applied', $field_name);
$pe->write(3, 2, 'Status', $field_name);
$pe->write(3, 3, 'Applicant Name', $field_name);
$pe->write(3, 4, 'Date/ Time', $field_name);
$pe->write(3, 5, 'Assigned', $field_name);

$start_row = 4;

foreach ($data as $key => $val) {
	//$e = $values[$key]['employee'];
	
	$pe->write($start_row, 0, $GLOBALS['hr']['application_event'][$val['event_type']], $text);
	$pe->write($start_row, 1, $val['position_applied'], $text);
	$pe->write($start_row, 2, $GLOBALS['hr']['application_status'][$val['application_status_id']], $text);
	
	$pe->write($start_row, 3, $val['applicant_name'], $text);
	$pe->write($start_row, 4, $val['date_time_event'], $text);
	$pe->write($start_row, 5, $val['hiring_manager'], $text);

	$start_row++;
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="APPLICANT_BY_SCHEDULE.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($pe, 'Excel5');
$objWriter->save('php://output');
exit;
?>