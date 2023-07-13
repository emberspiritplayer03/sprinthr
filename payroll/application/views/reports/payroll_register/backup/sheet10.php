<?php
$worksheet10->write(0, 0, "WABY ENTERPRISE", $header);
$worksheet10->write(1, 0, "CASH ADVANCES", $template_name);
$worksheet10->write(2, 0, "FOR THE PERIOD", $label);
$worksheet10->write(2, 1, $from . ' to ' . $to, $label_value_n);
$worksheet10->write(3, 0, "PAY DATE", $label);
$worksheet10->write(3, 1, date('F j, Y'), $label_value_n);

$worksheet10->set_column(4, 41, 20);
$worksheet10->set_column(0, 0, 20);
$worksheet10->set_column(1, 3, 25);

$worksheet10->write(5, 0, 'Employee Number', $field_name_10_f);
$worksheet10->write(5, 1, 'Employee Name', $field_name_10_f);
$worksheet10->write(5, 2, 'Amount of C.A.', $field_name_10_f);


$tmp_array = array(
				array("id" => "WAB-11-001", "name" => "Reyes, Efren Bata", "amount" => "5000"),
				array("id" => "WAB-11-002", "name" => "Reyes, Efren Bata", "amount" => "6000"),
				array("id" => "WAB-11-003", "name" => "Reyes, Efren Bata", "amount" => "7000"),
				array("id" => "WAB-11-004", "name" => "Reyes, Efren Bata", "amount" => "8000"),
				array("id" => "WAB-11-005", "name" => "Reyes, Efren Bata", "amount" => "9000"),
		);	
		
$start_row = 6;
foreach($tmp_array as $arr=>$value):
	$worksheet10->write($start_row, 0, $value['id'], $data_sheet_10_f);
	$worksheet10->write($start_row, 1, $value['name'], $data_sheet_10_f); 
	$worksheet10->write($start_row, 2, $value['amount'], $data_sheet_10_f);
	$start_row++;
endforeach;
?>