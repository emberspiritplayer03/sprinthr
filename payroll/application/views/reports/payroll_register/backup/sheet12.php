<?php

$pcovered = "Jan 26  to Feb 10 2012";
$pdate    = "3-15-2012";

//Custom Format
$date_format      =& $workbook->addformat(array(num_format => 'mm/dd/yy', size => 8, align => 'left', color => 'blue'));
$date_format->set_bold();
$emphasize_format =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 0, align => 'right',color =>'red'));
$total_format     =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 1, align => 'right',color =>'blue'));
//

//Header
$worksheet12->write(0, 0, "WABY ENTERPRISE", $header);
$worksheet12->write(1, 0, "Employees PHIC Monitoring", $header);
$worksheet12->write(2, 0, "Period Covered:", $data);
$worksheet12->write(2, 1, $pcovered, $date_format);
//End Header

//Column Header
$worksheet12->set_column(4,41,10);
$worksheet12->set_column(0,0,10);
$worksheet12->set_column(1,3,15);

$worksheet12->write(5, 0, 'Id No.', $field_name);
$worksheet12->write(5, 1, 'Name', $field_name);
$worksheet12->write(5, 2, 'PHILHEALTH#', $field_name);
$worksheet12->write(5, 3, 'EE', $field_name);
$worksheet12->write(5, 4, 'ER', $field_name);
//End Column Header

//Array
$start_row = 6;
$emp = array(
		1 => array(
			"name"   => "Bryan Bio",
			"emp_id" => "G-1001",
			"philhealth" => "1234567890",
			"ee" => 50.00,
			"er" => 50.00			
		),
		2 => array(
			"name"   => "Jeniel Mangahis",
			"emp_id" => "G-1002",
			"philhealth" => "3213548532152",
			"ee" => 50.00,
			"er" => 50.00			
		),
		3 => array(
			"name"   => "Bryann Revina",
			"emp_id" => "G-1003",
			"philhealth" => "875132187546",
			"ee" => 50.00,
			"er" => 50.00			
		),
		4 => array(
			"name"   => "Leo Diaz",
			"emp_id" => "G-1004",
			"philhealth" => "9731321651321",
			"ee" => 50.00,
			"er" => 50.00			
		),
		5 => array(
			"name"   => "Randy Velasco",
			"emp_id" => "G-1005",
			"philhealth" => "313546874864",
			"ee" => 50.00,
			"er" => 50.00			
		)
		);
//End Array

foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$worksheet12->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$worksheet12->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$worksheet12->write($start_row,2,$emp[$key]['philhealth'],$number_standard); //col 2 sss
	$worksheet12->write($start_row,3,$emp[$key]['ee'],$number);//col 3 ee 
	$worksheet12->write($start_row,4,$emp[$key]['er'],$number);//col 3 er 
	$start_row++;	
}

//Footer
$worksheet12->write($start_row,0,"Total",$field_name);
$worksheet12->write_formula($start_row ,3,'SUM(D7' . ':D' . $write_col . ')',$total_format);	
$worksheet12->write_formula($start_row ,4,'SUM(E7' . ':E' . $write_col . ')',$total_format);
$worksheet12->write($start_row+3,1,'NOTE: To link it with the PHIC Er2 Report for those newly hired employees in one applicablemonth.',$data);//
$worksheet12->write($start_row+4,1,' To link it PHIC Er2 with the PHIC RF1 Report.',$data);//
//End Footer
?>