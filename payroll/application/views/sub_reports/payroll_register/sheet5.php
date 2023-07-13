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
$worksheet5->write(0, 0, "WABY ENTERPRISE", $header);
$worksheet5->write(1, 0, "MANDATED ER SHARE TABULATION (MONTHLY)", $header);
$worksheet5->write(2, 0, "Period:", $data);
$worksheet5->write(2, 1, $pcovered, $date_format);
//End Header

//Column Header
$worksheet5->set_column(4,41,10);
$worksheet5->set_column(0,0,10);
$worksheet5->set_column(1,3,15);

$worksheet5->write(5, 0, 'Id No.', $field_name);
$worksheet5->write(5, 1, 'Name', $field_name);
$worksheet5->write(5, 2, 'Position', $field_name);
$worksheet5->write(5, 3, 'SSS - ER Share', $field_name);
$worksheet5->write(5, 4, 'PHIC - ER Share', $field_name);
$worksheet5->write(5, 5, 'HDMF - ER Share', $field_name);

$worksheet5->set_column(5,1,10);
$worksheet5->set_column(5,2,10);
$worksheet5->set_column(5,3,20);
$worksheet5->set_column(5,4,20);
$worksheet5->set_column(5,5,20);
//End Column Header

//Array
$start_row = 6;
$emp = array(
		1 => array(
			"name"   => "Bryan Bio",
			"emp_id" => "G-1001",
			"position" => "Programmer/Team Leader",
			"sss" => 504.00,
			"phic" => 50.00,
			"hdmf" => 100.00			
		),		
		2 => array(
			"name"   => "Jeniel Mangahis",
			"emp_id" => "G-1002",
			"position" => "Programmer",
			"sss" => 469.00,
			"phic" => 50.00,
			"hdmf" => 100.00			
		),	
		3 => array(
			"name"   => "Bryann Revina",
			"emp_id" => "G-1003",
			"position" => "Programmer",
			"sss" => 469.00,
			"phic" => 50.00,
			"hdmf" => 100.00			
		),	
		4 => array(
			"name"   => "Leo Diaz",
			"emp_id" => "G-1004",
			"position" => "Programmer",
			"sss" => 350.00,
			"phic" => 50.00,
			"hdmf" => 100.00			
		),
		5 => array(
			"name"   => "Randy Velasco",
			"emp_id" => "G-1005",
			"position" => "Programmer",
			"sss" => 350.00,
			"phic" => 50.00,
			"hdmf" => 100.00			
		)		
		);
//End Array

foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$worksheet5->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$worksheet5->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$worksheet5->write($start_row,2,$emp[$key]['position'],$data); //col 2 position
	$worksheet5->write($start_row,3,$emp[$key]['sss'],$number);//col 3 sss 
	$worksheet5->write($start_row,4,$emp[$key]['phic'],$number);//col 4 phic 
	$worksheet5->write($start_row,5,$emp[$key]['hdmf'],$number);//col 5 hdmf 
	$start_row++;	
}

//Footer
$worksheet5->write($start_row,0,"Total",$field_name);
$worksheet5->write_formula($start_row ,3,'SUM(D7' . ':D' . $write_col . ')',$total_format);	
$worksheet5->write_formula($start_row ,4,'SUM(E7' . ':E' . $write_col . ')',$total_format);	
$worksheet5->write_formula($start_row ,5,'SUM(F7' . ':F' . $write_col . ')',$total_format);	
//End Footer
?>