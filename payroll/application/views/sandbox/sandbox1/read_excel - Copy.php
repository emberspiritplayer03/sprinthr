<?php
$file = BASE_PATH . 'application/views/sandbox/sandbox1/timesheet.xls'; //given example
//$file = BASE_PATH . 'application/views/sandbox/sandbox1/timesheet - test data with 3000 rows.xls'; // test data

$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objReader = $objReader->load($file);
$read_sheet = $objReader->getActiveSheet();


$column_position = 0; // this will be the pointer of the cell iterator
$excluded_headers = array('A1','B1','C1','D1','E1'); //these are the headers of the excel

$tmp = array();
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
				$shift = strtoupper(substr($cell->getValue(),-2,2));
				//create tmp multidimensional associative array that will hold the time info
/*				if(strtolower($type) == 'in') { 
					if(empty($tmp[$id]['time_in'])) { // check if the employee alrady time in
						$tmp[$id]['date_in'] = $date;  
						$tmp[$id]['time_in'] = $time . ' ' . $shift;
					}
				} else {
					$tmp[$id]['date_out'] = $date;
					$tmp[$id]['time_out'] = $time . ' ' . $shift;
				}*/
				$new_date = date('Y-m-d',strtotime($cell->getValue()));
				$new_time = date('H:i:s',strtotime($cell->getValue()));
				$timesheet[$id][$new_date][$new_time] = $new_date;
	
			}
			
			$column_position++;
			if($column_position == 5) {
				$column_position = 0;	
				
			}
			 
		}
		 
//       echo "Cell:$coord - " . $cell->getValue() ."<BR>";		 
//		 echo "Cell:". $cell->columnIndexFromString($cell->getColumn()) ." - " . $cell->getValue() ."<BR>";	
//		 echo $cell->getValue();
//		 echo '<br>'; 
//		 $cells[$cell->getColumn()][$cell->getRow()] = $cell->getValue();	 
      }
   }
}


/*
$array['0011'] = array('date_in' => '7/17/2012', 'time_in' => '8:30:15 PM', 'date_out' => '7/18/2012', 'time_out' => '5:23:45 AM');
$array['0074'] = array('date_in' => '7/17/2012', 'time_in' => '9:45:08 AM', 'date_out' => '7/17/2012', 'time_out' => '6:14:01 PM');
$array['0057'] = array('date_in' => '7/17/2012', 'time_in' => '9:05:45 AM', 'date_out' => '7/17/2012', 'time_out' => '6:06:31 PM');
$array['0060'] = array('date_in' => '7/17/2012', 'time_in' => '9:14:29 AM', 'date_out' => '7/17/2012', 'time_out' => '6:14:29 PM');
*/

//$array2 = array("date"=>"sdada","date1"=>"adasd");
//$array = array($array2);

echo '<pre>';
print_r($timesheet);
echo '</pre>';

$tr = new Timesheet_Raw_Reader;
$x = $tr->getTimeInAndOut($timesheet);	
echo '<pre>';
print_r($x);
echo '</pre>';
?>