<?php
//title of worksheet / header
$worksheet2->write(0, 0, $company_name, $header);
$worksheet2->write(1, 0, "ACCUMULATED CASH BONDS", $template_name);
$worksheet2->write(2, 0, "As of _____________", $label);

$worksheet2->set_column(4, 41, 20);
$worksheet2->set_column(0, 0, 10);
$worksheet2->set_column(1, 3, 25);
$worksheet2->set_column('D:O', 15);


$input_date = '2012-01-01';  // start date
$month_duration = 6; 		// 6 months
$day_period     = 15;		// bi-monthly cycle
$tmp_arr_date = array();

//this will compute the date dynamically, depends on the start_date, month_duration, and day_period
for($i=0; $i<$month_duration*2; $i++):
	$to    = date('d-M-Y',(strtotime ( '+ ' . $day_period .'day' , strtotime ($input_date))));
	array_push($tmp_arr_date,$to);
	$input_date = $to;
endfor;

$worksheet2->write(5, 0, 'No.', $field_name_9_f);
$worksheet2->write(5, 1, 'Name', $field_name_9_f);
$worksheet2->write(5, 2, 'Position', $field_name_9_f);
$start_column = 3;
foreach($tmp_arr_date as $date):
	$worksheet2->write(5, $start_column, $date, $field_name_9_f);
	$start_column++;
endforeach;

$worksheet2->write(5, $start_column, 'TOTAL', $field_name_9_f);

// sample data, employee info
$tmp_array = array(
				array(
						"id" 	   => "WAB-11-001", 
						"name" 	   => "Reyes, Efren Bata", 
						"position" => "Delivery Helper",
						"jan_1"      => "200",
						"jan_2"      => "200",
						"feb_1"      => "0",
						"feb_2"      => "0",
						"mar_1"      => "200",
						"mar_2"      => "200",
						"apr_1"      => "0",
						"apr_2"      => "0",
						"may_1"      => "200",
						"may_2"      => "200",
						"jun_1"      => "50",
						"jun_2"      => "50"
					),
				array(
						"id" => "WAB-11-002", 
						"name" => "Reyes, Efren Bata", 
						"position" => "Delivery Helper",
						"jan_1"      => "200", "jan_2"      => "200",
						"feb_1"      => "0"  , "feb_2"      => "0",
						"mar_1"      => "200", "mar_2"      => "200",
						"apr_1"      => "0",   "apr_2"      => "0",
						"may_1"      => "200", "may_2"      => "200",
						"jun_1"      => "50",  "jun_2"      => "50"
					),
				array(
						"id" => "WAB-11-003", 
						"name" => "Reyes, Efren Bata", 
						"position" => 
						"Delivery Helper",
						"jan_1"      => "200", "jan_2"      => "200",
						"feb_1"      => "0"  , "feb_2"      => "0",
						"mar_1"      => "200", "mar_2"      => "200",
						"apr_1"      => "0",   "apr_2"      => "0",
						"may_1"      => "200", "may_2"      => "200",
						"jun_1"      => "50",  "jun_2"      => "50"
					),
				array(
						"id" => "WAB-11-004", 
						"name" => "Reyes, Efren Bata", 
						"position" => 
						"Delivery Helper",
						"jan_1"      => "200", "jan_2"      => "200",
						"feb_1"      => "0"  , "feb_2"      => "0",
						"mar_1"      => "200", "mar_2"      => "200",
						"apr_1"      => "0",   "apr_2"      => "0",
						"may_1"      => "200", "may_2"      => "200",
						"jun_1"      => "50",  "jun_2"      => "50"
					),
				array(
						"id" => "WAB-11-005", 
						"name" => "Reyes, Efren Bata", 
						"position" => "Delivery Helper",
						"jan_1"      => "200", "jan_2"      => "200",
						"feb_1"      => "0"  , "feb_2"      => "0",
						"mar_1"      => "200", "mar_2"      => "200",
						"apr_1"      => "0",   "apr_2"      => "0",
						"may_1"      => "200", "may_2"      => "200",
						"jun_1"      => "50",  "jun_2"      => "50"
					),
		);	
		

//these lines of code is for setting the default values (which is 0) for the rows and colums
$start_row     = 6;	
//$start_column  = 3;
/*
for($i=$start_row; $i<count($tmp_array)+$start_row; $i++):
	for($j=$start_column; $j<=14; $j++):
		$worksheet2->write($i, $j, 0, $data_sheet_9_f);
	endfor;
endfor;
*/

foreach($tmp_array as $arr=>$value):
$worksheet2->write($start_row, 0, $value['id'], $data_sheet_9_f);
	$worksheet2->write($start_row, 1, $value['name'], $data_sheet_9_f); 
	$worksheet2->write($start_row, 2, $value['position'], $data_sheet_9_f);
	
		//static adding of cash values
		$worksheet2->write($start_row, 3,  $value['jan_1'], $data_sheet_9_f);  
		$worksheet2->write($start_row, 4,  $value['jan_2'], $data_sheet_9_f);
		
		$worksheet2->write($start_row, 5,  $value['feb_1'], $data_sheet_9_f);
		$worksheet2->write($start_row, 6,  $value['feb_2'], $data_sheet_9_f);
		
		$worksheet2->write($start_row, 7,  $value['mar_1'], $data_sheet_9_f);
		$worksheet2->write($start_row, 8,  $value['mar_2'], $data_sheet_9_f);
	
		$worksheet2->write($start_row, 9,  $value['apr_1'], $data_sheet_9_f);
		$worksheet2->write($start_row, 10,  $value['apr_2'], $data_sheet_9_f);
		
		$worksheet2->write($start_row, 11,  $value['may_1'], $data_sheet_9_f);
		$worksheet2->write($start_row, 12,  $value['may_2'], $data_sheet_9_f);
		
		$worksheet2->write($start_row, 13,  $value['jun_1'], $data_sheet_9_f);
		$worksheet2->write($start_row, 14,  $value['jun_2'], $data_sheet_9_f);
		$worksheet2->write_formula($start_row, $start_column, '=SUM(D'.($start_row+1).':O'.($start_row+1).')',$data_sheet_9_f);
	
	$start_row++;
endforeach;




?>