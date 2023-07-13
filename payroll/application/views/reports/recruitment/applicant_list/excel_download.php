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

$pe->write(0, 0, 'Applicant List', $header);

$pe->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$pe->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$pe->getActiveSheet()->getColumnDimension('C')->setWidth(16);
$pe->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$pe->getActiveSheet()->getColumnDimension('E')->setWidth(22);
$pe->getActiveSheet()->getColumnDimension('F')->setWidth(22);
$pe->getActiveSheet()->mergeCells('A1:D1');
$pe->getActiveSheet()->mergeCells('A3:C3');
//$pe->getActiveSheet()->mergeCells('D3:D4');
$pe->write(2, 0, 'Applicant List', $field_name);

$x=0;

foreach($excel_title as $key=>$title) {
	$pe->write(3, $x, Tools::friendlyTitle($title), $field_name);
	$x++;
	//echo Tools::friendlyTitle($title);
}

$start_row = 4;
$x=0;
$y = count($excel_title);
foreach ($data as $key => $val) {
	
	while($x<=$y) {
		$pe->write($start_row, $x, $val[$excel_title[$x]], $text);	
		$x++;
		//echo $val[$excel_title[$x]];
	}
	$x=0;
	$start_row++;
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="APPLICANT_LIST.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($pe, 'Excel5');
$objWriter->save('php://output');
exit;
?>