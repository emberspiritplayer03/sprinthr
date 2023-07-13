<?php

$pcovered = "Jan 26  to Feb 10 2012";
$pdate    = "3-15-2012";

//Custom Format
$date_format  =& $workbook->addformat(array(num_format => 'mm/dd/yy', size => 8, align => 'left', color => 'blue'));
$date_format->set_bold();
$emp_format   =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 0, align => 'right',color =>'red'));
$total_format =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 1, align => 'right',color =>'blue'));
//

//Header
$worksheet->write(0, 0, "WABY ENTERPRISE", $header);
$worksheet->write(1, 0, "GROSS PAYROLL TABULATION", $header);
$worksheet->write(2, 0, "Period Covered:", $data);
$worksheet->write(2, 1, $pcovered, $date_format);
$worksheet->write(3, 0, "Pay Date:", $data);
$worksheet->write(3, 1, $pdate, $date_format);
//End Header

//Column Header
$worksheet->set_column(5, 41, 10);
$worksheet->set_column(0, 0, 10);
$worksheet->set_column(1, 3, 15);

$worksheet->set_column(5, 5, 15);
$worksheet->set_column(5, 7, 30);
$worksheet->set_column(5, 9, 20);
$worksheet->set_column(5, 10, 20);
$worksheet->set_column(5, 11, 20);

$worksheet->write(5, 0, 'Id No.', $field_name);
$worksheet->write(5, 1, 'Name', $field_name);
$worksheet->write(5, 2, 'Position', $field_name);
$worksheet->write(5, 3, 'Daily Rate', $field_name);
$worksheet->write(5, 4, 'Wrk Dys.', $field_name);
$worksheet->write(5, 5, 'Meal Allow', $field_name);
$worksheet->write(5, 6, 'O.T', $field_name);
$worksheet->write(5, 7, 'Other Pay / (HOLIDAY)', $field_name);
$worksheet->write(5, 8, 'Earnings', $field_name);
$worksheet->write(5, 9, 'A/R Items (Client)', $field_name);
$worksheet->write(5, 10, 'Gross Pay', $field_name);
$worksheet->write(5, 11, '13th Month', $field_name);
//End Column Header

//Array
$start_row = 6;
$emp = array(
		1 => array(
			"name"   => "Bryan Bio",
			"emp_id" => "G-1001",
			"position" => "Programmer/Team Leader",
			"daily_rate" => 400.00,
			"wrk_days" => 14.0,
			"meal_allwnce" => 150.00,
			"ot" => 200.00,
			"other_pay" => 100.00,
			"ar_items" => 25.00
		),
		2 => array(
			"name"   => "Jeniel Mangahis",
			"emp_id" => "G-1002",
			"position" => "Programmer",
			"daily_rate" => 300.00,
			"wrk_days" => 11.0,
			"meal_allwnce" => 100.00,
			"ot" => 200.00,
			"other_pay" => 20.00,
			"ar_items" => 100.00
		),
		3 => array(
			"name"   => "Bryann Revina",
			"emp_id" => "G-1003",
			"position" => "Programmer",
			"daily_rate" => 300.00,
			"wrk_days" => 12.0,
			"meal_allwnce" => 100.00,
			"ot" => 200.00,
			"other_pay" => 120.00,
			"ar_items" => 100.00
		),
		4 => array(
			"name"   => "Leo Diaz",
			"emp_id" => "G-1003",
			"position" => "Programmer",
			"daily_rate" => 280.00,
			"wrk_days" => 14.0,
			"meal_allwnce" => 0.00,
			"ot" => 200.00,
			"other_pay" => 120.00,
			"ar_items" => 0.00
		),
		5 => array(
			"name"   => "Randy Velasco",
			"emp_id" => "G-1004",
			"position" => "Programmer",
			"daily_rate" => 280.00,
			"wrk_days" => 11.0,
			"meal_allwnce" => 0.00,
			"ot" => 150.00,
			"other_pay" => 20.00,
			"ar_items" => 100.00
		)
);
///End Array

foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$worksheet->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$worksheet->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$worksheet->write($start_row,2,$emp[$key]['position'],$data); //col 2 position
	$worksheet->write($start_row,3,$emp[$key]['daily_rate'],$number);//col 3 daily rate
	$worksheet->write($start_row,4,$emp[$key]['wrk_days'],$emp_format);//col 4 work days
	$worksheet->write($start_row,5,$emp[$key]['meal_allwnce'],$number);//col 5 meal allowance
	$worksheet->write($start_row,6,$emp[$key]['ot'],$number);//col 6 ot
	$worksheet->write($start_row,7,$emp[$key]['other_pay'],$number);//col 7 other pay			
	$worksheet->write($start_row,9,$emp[$key]['ar_items'],$emp_format);//col 9 ar items
	
	//Compute Earnings
	$worksheet->write_formula($start_row ,8,'D' . $write_col . '*E' . $write_col . '+F' . $write_col . '+G' . $write_col . '+H' . $write_col,$number);
	//End Compute Earnings
	
	//Compute Gross
	$worksheet->write_formula($start_row ,10,'I' . $write_col . '-J' . $write_col ,$number);	
	//End Compute Gross
	
	//Compute 13th Month
	$worksheet->write_formula($start_row ,11,'D' . $write_col . '*0.0833' . '*E' . $write_col,$number);	
	//End Compute Gross
	$start_row++;	
}

//Footer
$worksheet->write($start_row,0,"Total",$field_name);
$worksheet->write_formula($start_row ,3,'SUM(D7' . ':D' . $write_col . ')',$total_format);	
$worksheet->write_formula($start_row ,4,'SUM(E7' . ':E' . $write_col . ')',$total_format);	
$worksheet->write_formula($start_row ,5,'SUM(F7' . ':F' . $write_col . ')',$total_format);	
$worksheet->write_formula($start_row ,6,'SUM(G7' . ':G' . $write_col . ')',$total_format);	
$worksheet->write_formula($start_row ,7,'SUM(H7' . ':H' . $write_col . ')',$total_format);	
$worksheet->write_formula($start_row ,8,'SUM(I7' . ':I' . $write_col . ')',$total_format);	
$worksheet->write_formula($start_row ,9,'SUM(J7' . ':J' . $write_col . ')',$total_format);	
$worksheet->write_formula($start_row ,10,'SUM(K7' . ':K' . $write_col . ')',$total_format);	
$worksheet->write_formula($start_row ,11,'SUM(L7' . ':L' . $write_col . ')',$total_format);	
//End Footer



?>