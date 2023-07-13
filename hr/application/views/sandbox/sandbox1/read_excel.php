<?php
$file = BASE_PATH . 'application/views/sandbox/sandbox1/timesheet.xls'; //given example
//$file = BASE_PATH . 'application/views/sandbox/sandbox1/timesheet - test data with 3000 rows.xls'; // test data

$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objReader = $objReader->load($file);
$read_sheet = $objReader->getActiveSheet();

$column_position = 0; // this will be the pointer of the cell iterator
$excluded_headers = array('A1','B1','C1','D1','E1'); //these are the headers of the excel

$timesheets = array();
foreach ($read_sheet->getRowIterator() as $row) {
   $cellIterator = $row->getCellIterator();
   foreach ($cellIterator as $cell) {
      if (($cell->getValue() != "")) {
         $coord = $cell->getCoordinate();
		
		//prevents the reading of headers, checks the array header
		if(!in_array($coord, $excluded_headers)) {
			if($column_position == 0) {
				$id = $cell->getValue(); // get the id of 
			} else if($column_position == 3) {
				$type = $cell->getValue();
			} else if($column_position == 4) {
				$date = date('m/d/Y',strtotime($cell->getValue())); // convert the excel date to php date format
				$time = date('h:i:s',strtotime($cell->getValue())); // convert the excel time to php time format
				$new_date = date('Y-m-d',strtotime($cell->getValue()));
				$new_time = date('H:i:s',strtotime($cell->getValue()));
				$timesheets[$id][$new_date][$new_time] = $new_date;
	
			}
			
			$column_position++;
			if($column_position == 5) {
				$column_position = 0;	
				
			}
			 
		} 
      }
   }
}

echo '<pre>';
print_r($timesheets);
echo '</pre>';

$tr = new Timesheet_Raw_Reader;
$x = $tr->getTimeInAndOut($timesheets);	
echo '<pre>';
print_r($x);
echo '</pre>';
?>