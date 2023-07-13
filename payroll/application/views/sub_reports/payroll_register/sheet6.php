<?php

$pcovered = "Jan 26  to Feb 10 2012";
$pdate    = "3-15-2012";

//Custom Format
$date_format      =& $workbook->addformat(array(num_format => 'mm/dd/yy', size => 8, align => 'left', color => 'blue'));
$date_format->set_bold();
$emphasize_format =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 0, align => 'right',color =>'red'));
$total_format     =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 1, align => 'right',color =>'blue'));
//
//Counter start row
$p01 = 0;
$p02 = 1;
$p03 = 2;
$p04 = 3;
$p05 = 4;
//End counter start row
for($x=0;$x<=2;$x++){
	
	$worksheet6->write($p01, 0, "FCJA-ATM", $header);
	$worksheet6->write($p01, 5, "Period", $header);
	$worksheet6->write($p01, 6, $pcovered, $date_format);
	$worksheet6->write($p01, 8, "PAY DATE", $header);
	$worksheet6->write($p01, 9, $pcovered, $date_format);
	
	
	$worksheet6->write($p02, 0, "EMPLOYEE PAY SLIP", $header);
	$worksheet6->write($p02, 6, "Position:", $header);
	$worksheet6->write($p02, 6, "OFFICE STAFF", $header);
	
	
	$worksheet6->write($p03, 0, "Employee:", $header);
	$worksheet6->write($p03, 2, "WAB-11-002", $header);
	$worksheet6->write($p03, 4, "BIO, BRYAN T.", $header);
	
	
	$worksheet6->write($p04, 0, "", $header); //RATE
	$worksheet6->write($p04, 1, "TRANSACTION", $header);
	$worksheet6->write($p04, 2, "", $header);
	$worksheet6->write($p04, 4, "", $header); //EARNINGS TITLE
	$worksheet6->write($p04, 5, "EARNINGS", $header);
	$worksheet6->write($p04, 7, "", $header);
	$worksheet6->write($p04, 8, "DEDUCTIONS", $header);
	$worksheet6->write($p04, 10, "", $header); //SUMMARY
	
	
	//Transaction title
	$worksheet6->write($p04+1, 0, "Rate", $header);
	$worksheet6->write($p04+2, 0, "Wrk Dys:", $header);
	$worksheet6->write($p04+3, 0, "Meal Allow:", $header);
	$worksheet6->write($p04+4, 0, "OT w/in >8:", $header);
	$worksheet6->write($p04+5, 0, "OTHR PAY:", $header);
	$worksheet6->write($p04+6, 0, "(-) Item/Chrgs:", $header);
	$worksheet6->write($p04+12, 0, "BASIC PAY:", $header);
	//End Rate Title
	
	//Transaction Values
	$worksheet6->write($p04+1, 1, "337.00", $number);
	$worksheet6->write($p04+2, 1, "14", $number);
	$worksheet6->write($p04+3, 1, "0.00", $number);
	$worksheet6->write($p04+4, 1, "0.00", $number);
	$worksheet6->write($p04+5, 1, "0.00", $number);
	$worksheet6->write($p04+6, 1, "147.39", $number);
	$worksheet6->write($p04+12, 1, "4570.61", $number);
	//End Transaction Values
	
	//$worksheet6->write($p04, 5, "EARNINGS", $header);
	
	//Earnings Title
	$worksheet6->write($p04+1, 5, "BASIC PAY :", $header);
	$worksheet6->write($p04+12, 0, "GROSS PAY:", $header);
	//End Earnings Title
	
	//Earnings Values
	$worksheet6->write($p04+1, 5, "4570.61", $header);
	$worksheet6->write($p04+12, 1, "4570.61", $number);
	//End Earnings Title
	
	
	//$worksheet6->write($p04, 8, "DEDUCTIONS", $header);
	//Deductions Title
	$worksheet6->write($p04+1, 8, "SSS Prem", $header);
	$worksheet6->write($p04+2, 8, "PhHealth", $header);
	$worksheet6->write($p04+3, 8, "Pag-ibig", $header);
	$worksheet6->write($p04+4, 8, "Late/UT", $header);
	$worksheet6->write($p04+5, 8, "I.D.Card", $header);
	$worksheet6->write($p04+6, 8, "Uniform", $header);
	$worksheet6->write($p04+7, 8, "Medicard", $header);
	$worksheet6->write($p04+8, 8, "Cash Bond", $header);
	$worksheet6->write($p04+9, 8, "Others:   C.A. ", $header);
	$worksheet6->write($p04+9, 8, "Items/Goods", $header);
	$worksheet6->write($po4+11, 8, "P.F", $header);
	$worksheet6->write($p04+12, 8, "TOTAL DEDN", $header);
	//End Deductions Title
	
	//Deductions values
	$worksheet6->write($p04+1, 9, 223.30, $number);
	$worksheet6->write($p04+2, 9, 50.00, $number);
	$worksheet6->write($p04+3, 9, 100.00, $number);
	$worksheet6->write($p04+4, 9, 0.00, $number);
	$worksheet6->write($p04+5, 9, 0.00, $number);
	$worksheet6->write($p04+6, 9, 0.00, $number);
	$worksheet6->write($p04+7, 9, 0.00, $number);
	$worksheet6->write($p04+8, 9, 0.00, $number);
	$worksheet6->write($p04+9, 9, 500.00, $number);
	$worksheet6->write($p04+9, 9, 0.00, $number);
	$worksheet6->write($po4+11, 9, 141.54, $number);
	$worksheet6->write($p04+12, 9, 1024.84, $number);
	//End Deductions Title
	
	//Summary Net Pay Columns Text
	$worksheet6->write($p04+4, 10, "NET PAY:", $header);
	$worksheet6->write($p04+5, 10, "I acknowledge to have received the amount", $header);
	$worksheet6->write($p04+6, 10, "stated above as salary/compensation for", $header);
	$worksheet6->write($p04+7, 10, "the services rendered for the period indicated.", $header);
	
	$worksheet6->write($p04+12, 10, "PRINT NAME & SIGN", $header);
	$worksheet6->write($p04+12, 12, "DATE", $header);
	$worksheet6->write($p04, 10, "", $header); //SUMMARY
	//End Summary Net Pay Columns Text
	
	
	//Summary Net Pay Columns Value
	$worksheet6->write($p04+4, 10, 3545.77, $header);
	//End Net Pay Value
	
	$p01 += 19;
	$p02 += 20;
	$p03 += 21;
	$p04 += 22;
	$p05 += 23;
}
?>