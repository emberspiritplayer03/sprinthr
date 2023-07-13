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
$worksheet4->write(0, 0, "WABY ENTERPRISE", $header);
$worksheet4->write(1, 0, "SERVICE FEE", $header);
$worksheet4->write(2, 0, "Period Covered:", $data);
$worksheet4->write(2, 1, $pcovered, $date_format);
$worksheet4->write(3, 0, "Pay Date:", $data);
$worksheet4->write(3, 1, $pdate, $date_format);
//End Header

//Column Header
$worksheet4->set_column(4,41,10);
$worksheet4->set_column(0,0,10);
$worksheet4->set_column(1,3,15);
$worksheet4->set_column(5,3,8);
$worksheet4->set_column(5,4,22);
$worksheet4->set_column(5,5,10);
$worksheet4->set_column(5,6,10);

$worksheet4->write(5, 0, 'Id No.', $field_name);
$worksheet4->write(5, 1, 'Name', $field_name);
$worksheet4->write(5, 2, 'Position', $field_name);
$worksheet4->write(5, 3, 'Daily Rate', $field_name);
$worksheet4->write(5, 4, 'S.F Rate(2.5%) per Day', $field_name);
$worksheet4->write(5, 5, 'No. of Days', $field_name);
$worksheet4->write(5, 6, 'Total', $field_name);
//End Column Header

//Array
$start_row = 6;
$emp = array(
		1 => array(
			"name"   => "Bryan Bio",
			"emp_id" => "G-1001",
			"position" => "Programmer/Team Leader",
			"daily_rate" => 400.00,
			"no_days" => 14.0			
		),
		2 => array(
			"name"   => "Jeniel Managahis",
			"emp_id" => "G-1002",
			"position" => "Programmer",
			"daily_rate" => 300.00,
			"no_days" => 13.0			
		),
		3 => array(
			"name"   => "Bryann Revina",
			"emp_id" => "G-1003",
			"position" => "Programmer",
			"daily_rate" => 300.00,
			"no_days" => 14.0			
		),
		4 => array(
			"name"   => "Leo Diaz",
			"emp_id" => "G-1004",
			"position" => "Programmer",
			"daily_rate" => 280.00,
			"no_days" => 14.0			
		),
		5 => array(
			"name"   => "Randy Velasco",
			"emp_id" => "G-1005",
			"position" => "Programmer",
			"daily_rate" => 280.00,
			"no_days" => 12.0			
		)
		);
//End Array

foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$worksheet4->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$worksheet4->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$worksheet4->write($start_row,2,$emp[$key]['position'],$data); //col 2 position
	$worksheet4->write($start_row,3,$emp[$key]['daily_rate'],$number);//col 3 daily 
	
	//Compute SF Rate
	$worksheet4->write_formula($start_row ,4,'D' . $write_col . '*0.025',$emphasize_format);
	//End Compute SF Rate
	
	$worksheet4->write($start_row,5,$emp[$key]['no_days'],$number);//col 5 no. days 
	
	//Compute Total
	$worksheet4->write_formula($start_row ,6,'E' . $write_col . '*F' . $write_col,$emphasize_format);
	//End Compute Total
	
	$start_row++;	
}

//Footer
$worksheet4->write($start_row,0,"Total",$field_name);
$worksheet4->write_formula($start_row ,3,'SUM(D7' . ':D' . $write_col . ')',$total_format);	
$worksheet4->write_formula($start_row ,4,'SUM(E7' . ':E' . $write_col . ')',$total_format);	
$worksheet4->write_formula($start_row ,5,'SUM(F7' . ':F' . $write_col . ')',$total_format);	
$worksheet4->write_formula($start_row ,6,'SUM(G7' . ':G' . $write_col . ')',$total_format);	
//End Footer
?>