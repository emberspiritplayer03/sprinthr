<?php
$pe = new PHPExcel();
$pe->setActiveSheetIndex(0);
$pe->getActiveSheet()->setTitle('Simple');
$pe->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Some value');

$pe->createSheet(1);
$pe->setActiveSheetIndex(1);
$pe->getActiveSheet()->setTitle('ase');
$pe->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'haaaay');

// Redirect output to a client's web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="test.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($pe, 'Excel2007');
$objWriter->save('php://output');
exit;
?>