<?php
// 8,000 rows lang ang kaya

$file = BASE_PATH . 'application/views/benchmark/read_large_excel_data/whole period.xlsx';

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader = $objReader->load($file);
$read_sheet = $objReader->getActiveSheet();

foreach ($read_sheet->getRowIterator() as $row) {
   $cellIterator = $row->getCellIterator();
   foreach ($cellIterator as $cell) {
      if (($cell->getValue() != "")) {
         $coord = $cell->getCoordinate();
         //echo "Cell:$coord - " . $cell->getValue() ."<BR>";		 
		 //echo "Cell:". $cell->columnIndexFromString($cell->getColumn()) ." - " . $cell->getValue() ."<BR>";	
		 echo $cell->getValue();
		 echo '<br>'; 
		 //$cells[$cell->getColumn()][$cell->getRow()] = $cell->getValue();	 
      }
   }
}

echo 'ok';
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="Payslip_'. $from .'_to_'. $to .'.xlsx"');
//header('Cache-Control: max-age=0');
//
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save('php://output');
//exit;
?>