<?php
//Header
$worksheet14->write(0, 0, "WABY ENTERPRISE", $header);
$worksheet14->write(1, 0, "BANK ACCOUNT NUMBER MONITORING", $header);
//End Header

//Column Header
$worksheet14->set_column(4, 41, 10);
$worksheet14->set_column(0, 0, 10);
$worksheet14->set_column(1, 3, 15);

$worksheet14->write(5, 0, 'Name', $field_name);
$worksheet14->write(5, 1, 'Account Number', $field_name);

$worksheet14->set_column(0, 1, 20);
//End Column Header

//Array
$start_row = 6;
$emp = array(
		1 => array(
			"name"   => "Bryan Bio",
			"accnt_no" => "199-2003030-8"				
		),
		2 => array(
			"name"   => "Jeniel Mangahis",
			"accnt_no" => "232-1232333-2"			
		),
		3 => array(
			"name"   => "Bryann Revina",
			"accnt_no" => "132-3463333-2"
		),
		4 => array(
			"name"   => "Leo Diaz",
			"accnt_no" => "992-885472-8"				
		),
		5 => array(
			"name"   => "Randy Velasco",
			"accnt_no" => "872-602526-1"				
		)
		);
//End Array

foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$worksheet14->write($start_row,0,$emp[$key]['name'],$data); //col 0 name
	$worksheet14->write($start_row,1,$emp[$key]['accnt_no'], $data); //col 1 accnt_no	
	$start_row++;	
}
?>