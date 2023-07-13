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
$worksheet15->write(0, 0, "WABY ENTERPRISE", $header);
$worksheet15->write(1, 0, "NET PAYROLL TABULATION", $header);
$worksheet15->write(2, 0, "Period Covered:", $data);
$worksheet15->write(2, 1, $pcovered, $date_format);
$worksheet15->write(3, 0, "Pay Date:", $data);
$worksheet15->write(3, 1, $pdate, $date_format);
//End Header

//Column Header
$worksheet15->set_column(4, 41, 10);
$worksheet15->set_column(0, 0, 10);
$worksheet15->set_column(0, 4, 10);
$worksheet15->set_column(5, 8, 39);

$worksheet15->write(5, 0, 'No.', $field_name);
$worksheet15->write(5, 1, 'Name', $field_name);
$worksheet15->write(5, 2, 'Account Number', $field_name);
$worksheet15->write(5, 3, 'Net Income', $field_name);
$worksheet15->write(5, 4, 'Ded.', $field_name);
$worksheet15->write(5, 5, 'for Individual Account -  Loading/Crediting', $field_name);

$worksheet15->set_column(0, 1, 20);
//End Column Header

//Array
$start_row = 6;
$emp = array(
		1 => array(
			"name"   => "Bryan Bio",
			"accnt_no" => "199-2003030-8",
			"net_income" => "7185.22",
			"ded" => "0.00"			
		),
		2 => array(
			"name"   => "Jeniel Mangahis",
			"accnt_no" => "232-1232333-2",
			"net_income" => "6925.00",
			"ded" => "10.00"					
		),
		3 => array(
			"name"   => "Bryann Revina",
			"accnt_no" => "132-3463333-2",
			"net_income" => "7000.22",
			"ded" => "100.00"		
		),
		4 => array(
			"name"   => "Leo Diaz",
			"accnt_no" => "992-885472-8",
			"net_income" => "4193.16",
			"ded" => "0.00"						
		),
		5 => array(
			"name"   => "Randy Velasco",
			"accnt_no" => "872-602526-1",
			"net_income" => "4985.20",
			"ded" => "100.00"					
		)
		);
//End Array
$counter = 1;
foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$worksheet15->write($start_row,0,$counter,$data); //col 0 name
	$worksheet15->write($start_row,1,$emp[$key]['name'],$data); //col 1 name
	$worksheet15->write($start_row,2,$emp[$key]['accnt_no'], $data); //col 2 accnt_no	
	$worksheet15->write($start_row,3,$emp[$key]['net_income'], $data); //col 3 net_income'	
	$worksheet15->write($start_row,4,$emp[$key]['ded'], $data); //col 4 ded'	
	
	//Compute Loading/Crediting
	$worksheet15->write_formula($start_row ,5,'D' . $write_col . '-E' . $write_col,$number);	
	//End Compute Gross		
	
	$start_row++;
	$counter++;	
}

//Footer
$worksheet15->write($start_row,0,"Total",$field_name);
$worksheet15->write_formula($start_row ,3,'SUM(D7' . ':D' . $write_col . ')',$total_format);	
$worksheet15->write_formula($start_row ,4,'SUM(E7' . ':E' . $write_col . ')',$total_format);
$worksheet15->write_formula($start_row ,5,'SUM(F7' . ':F' . $write_col . ')',$total_format);	
//End Footer
?>