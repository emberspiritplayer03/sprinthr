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
$worksheet2->write(0, 0, "WABY ENTERPRISE", $header);
$worksheet2->write(1, 0, "NET PAYROLL TABULATION", $header);
$worksheet2->write(2, 0, "Period Covered:", $data);
$worksheet2->write(2, 1, $pcovered, $date_format);
$worksheet2->write(3, 0, "Pay Date:", $data);
$worksheet2->write(3, 1, $pdate, $date_format);
//End Header

//Column Header
$worksheet2->set_column(4, 41, 10);
$worksheet2->set_column(0, 0, 10);
$worksheet2->set_column(5, 5, 15);
$worksheet2->set_column(5, 7, 15);
$worksheet2->set_column(5, 8, 15);
$worksheet2->set_column(5, 9, 15);
$worksheet2->set_column(5, 10, 8);
$worksheet2->set_column(5, 11, 8);
$worksheet2->set_column(5, 12, 8);
$worksheet2->set_column(5, 13, 8);
$worksheet2->set_column(5, 14, 8);
$worksheet2->set_column(5, 15, 15);
$worksheet2->set_column(5, 23, 15);
$worksheet2->set_column(5, 24, 20);

$worksheet2->write(5, 0, 'Id No.', $field_name);
$worksheet2->write(5, 1, 'Name', $field_name);
$worksheet2->write(5, 2, 'Position', $field_name);
$worksheet2->write(5, 3, 'Daily Rate', $field_name);
$worksheet2->write(5, 4, 'Wrk Dys.', $field_name);
$worksheet2->write(5, 5, 'Meal Allow', $field_name);
$worksheet2->write(5, 6, 'O.T', $field_name);
$worksheet2->write(5, 7, 'Other Pay / (HOLIDAY)', $field_name);
$worksheet2->write(5, 8, 'A/R Items (Client)', $field_name);
$worksheet2->write(5, 9, 'Gross Earnings', $field_name);
$worksheet2->write(5, 10, 'SSS Prem.', $field_name);
$worksheet2->write(5, 11, 'PHIC Prem', $field_name);
$worksheet2->write(5, 12, 'HDMF Prem.', $field_name);
$worksheet2->write(5, 13, 'Rate/Hr.', $field_name);
$worksheet2->write(5, 14, 'Amt Ded/Min', $field_name);
$worksheet2->write(5, 15, 'Total', $field_name);
$worksheet2->write(5, 16, 'I.D.', $field_name);
$worksheet2->write(5, 17, 'Uniform', $field_name);
$worksheet2->write(5, 18, 'Medical', $field_name);
$worksheet2->write(5, 19, 'Cash Bond', $field_name);
$worksheet2->write(5, 20, 'C.A.', $field_name);
$worksheet2->write(5, 21, 'Items/Goods', $field_name);
$worksheet2->write(5, 22, 'P.F.', $field_name);
$worksheet2->write(5, 23, 'Deductions', $field_name);
$worksheet2->write(5, 24, 'Net/Take Home Pay', $field_name);
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
			"ar_items" => 25.00,
			"sss_prem" => 120.00,
			"phic_prem" => 100.00,
			"hdmf_pre" => 220.00,
			"rate_hr" => 42.13,
			"amt_ded_min" => 0.00,
			"id" => "10.00",
			"uniform" => "200.00",
			"medical" => "500.00",
			"cash_bond" => "150.00",
			"ca" => "120.00",
			"items_goods" => "100.00"
		),
		2 => array(
			"name"   => "Jeniel Mangahis",
			"emp_id" => "G-1002",
			"position" => "Programmer",
			"daily_rate" => 350.00,
			"wrk_days" => 13.0,
			"meal_allwnce" => 150.00,
			"ot" => 100.00,
			"other_pay" => 200.00,
			"ar_items" => 10.00,
			"sss_prem" => 120.00,
			"phic_prem" => 100.00,
			"hdmf_pre" => 220.00,
			"rate_hr" => 35.13,
			"amt_ded_min" => 0.00,
			"id" => "10.00",
			"uniform" => "300.00",
			"medical" => "500.00",
			"cash_bond" => "150.00",
			"ca" => "220.00",
			"items_goods" => "100.00"
		),
		3 => array(
			"name"   => "Bryann Revina",
			"emp_id" => "G-1003",
			"position" => "Programmer",
			"daily_rate" => 350.00,
			"wrk_days" => 14.0,
			"meal_allwnce" => 150.00,
			"ot" => 110.00,
			"other_pay" => 200.00,
			"ar_items" => 10.00,
			"sss_prem" => 120.00,
			"phic_prem" => 100.00,
			"hdmf_pre" => 220.00,
			"rate_hr" => 35.13,
			"amt_ded_min" => 0.00,
			"id" => "10.00",
			"uniform" => "150.00",
			"medical" => "500.00",
			"cash_bond" => "150.00",
			"ca" => "220.00",
			"items_goods" => "0.00"
		),
		4 => array(
			"name"   => "Leo Diaz",
			"emp_id" => "G-1004",
			"position" => "Programmer",
			"daily_rate" => 300.00,
			"wrk_days" => 12.0,
			"meal_allwnce" => 0.00,
			"ot" => 0.00,
			"other_pay" => 0.00,
			"ar_items" => 00.00,
			"sss_prem" => 100.00,
			"phic_prem" => 100.00,
			"hdmf_pre" => 180.00,
			"rate_hr" => 30.13,
			"amt_ded_min" => 0.00,
			"id" => "10.00",
			"uniform" => "150.00",
			"medical" => "500.00",
			"cash_bond" => "0.00",
			"ca" => "0.00",
			"items_goods" => "0.00"
		),
		5 => array(
			"name"   => "Randy Velasco",
			"emp_id" => "G-1005",
			"position" => "Programmer",
			"daily_rate" => 300.00,
			"wrk_days" => 13.0,
			"meal_allwnce" => 0.00,
			"ot" => 200.00,
			"other_pay" => 0.00,
			"ar_items" => 00.00,
			"sss_prem" => 100.00,
			"phic_prem" => 100.00,
			"hdmf_pre" => 180.00,
			"rate_hr" => 30.13,
			"amt_ded_min" => 10.20,
			"id" => "10.00",
			"uniform" => "150.00",
			"medical" => "500.00",
			"cash_bond" => "0.00",
			"ca" => "0.00",
			"items_goods" => "0.00"
		)
		
);
//End Array

foreach($emp as $key => $value){	
	$write_col = $start_row + 1;
	$worksheet2->write($start_row,0,$emp[$key]['emp_id'],$data); //col 0 id
	$worksheet2->write($start_row,1,$emp[$key]['name'], $data); //col 1 name
	$worksheet2->write($start_row,2,$emp[$key]['position'],$data); //col 2 position
	$worksheet2->write($start_row,3,$emp[$key]['daily_rate'],$number);//col 3 daily rate
	$worksheet2->write($start_row,4,$emp[$key]['wrk_days'],$emphasize_format);//col 4 work days
	$worksheet2->write($start_row,5,$emp[$key]['meal_allwnce'],$number);//col 5 meal allowance
	$worksheet2->write($start_row,6,$emp[$key]['ot'],$number);//col 6 ot
	$worksheet2->write($start_row,7,$emp[$key]['other_pay'],$number);//col 7 other pay	
	$worksheet2->write($start_row,8,$emp[$key]['ar_items'],$emphasize_format);//col 9 ar items
	
	//Compute Gross
	$worksheet2->write_formula($start_row ,9,'D' . $write_col . '*E' . $write_col . '+F' . $write_col . '+G' . $write_col . '+H' . $write_col . '-I' . $write_col,$number);	
	//End Compute Gross		
	
	$worksheet2->write($start_row,10,$emp[$key]['sss_prem'],$number);//col 10 sss prem
	$worksheet2->write($start_row,11,$emp[$key]['phic_prem'],$number);// col11 phic prem
	$worksheet2->write($start_row,12,$emp[$key]['hdmf_pre'],$number);// col12 hdmf prem
	$worksheet2->write($start_row,13,$emp[$key]['rate_hr'],$number);// col13 rate_hr
	$worksheet2->write($start_row,14,$emp[$key]['amt_ded_min'],$emphasize_format);// col14 amt ded min
	
	//Compute Total 
	$worksheet2->write_formula($start_row ,15,'N' . $write_col . '*O' . $write_col,$number);	
	//End Compute Gross	
	
	$worksheet2->write($start_row,16,$emp[$key]['id'],$number);// col16 id
	$worksheet2->write($start_row,17,$emp[$key]['uniform'],$number);// col17 uniform
	$worksheet2->write($start_row,18,$emp[$key]['medical'],$number);// col18 medical
	$worksheet2->write($start_row,19,$emp[$key]['cash_bond'],$number);// col19 cash bond
	$worksheet2->write($start_row,20,$emp[$key]['ca'],$number);// col20 ca
	$worksheet2->write($start_row,21,$emp[$key]['items_goods'],$number);// col21 items/goods
	
	//Compute PF 	
	$worksheet2->write_formula($start_row ,22,'D' . $write_col . '*0.03' . '*E' . $write_col,$emphasize_format);	
	//End Compute PF
	
	//Compute DEDUCATIONS 	
	$worksheet2->write_formula($start_row ,23,'K' . $write_col . '+L' . $write_col . '+M' . $write_col . '+P' . $write_col . '+Q' . $write_col . '+R' . $write_col . '+S' . $write_col . '+T' . $write_col . '+U' . $write_col . '+V' . $write_col . '+W' . $write_col,$emphasize_format);	
	//End Compute DEDUCTIONS
	
	//Compute Net / Take Home Pay 	
	$worksheet2->write_formula($start_row ,24,'J' . $write_col . '-X' . $write_col,$emphasize_format);	
	//End Compute PF
	
	$start_row++;	
}

//Footer
$worksheet2->write($start_row,0,"Total",$field_name);
$worksheet2->write_formula($start_row ,3,'SUM(D7' . ':D' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,4,'SUM(E7' . ':E' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,5,'SUM(F7' . ':F' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,6,'SUM(G7' . ':G' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,7,'SUM(H7' . ':H' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,8,'SUM(I7' . ':I' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,9,'SUM(J7' . ':J' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,10,'SUM(K7' . ':K' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,11,'SUM(L7' . ':L' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,12,'SUM(M7' . ':M' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,13,'SUM(N7' . ':N' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,14,'SUM(O7' . ':O' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,15,'SUM(P7' . ':P' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,16,'SUM(Q7' . ':Q' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,17,'SUM(R7' . ':R' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,18,'SUM(S7' . ':S' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,19,'SUM(T7' . ':T' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,20,'SUM(U7' . ':U' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,21,'SUM(V7' . ':V' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,22,'SUM(W7' . ':W' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,23,'SUM(X7' . ':X' . $write_col . ')',$total_format);	
$worksheet2->write_formula($start_row ,24,'SUM(Y7' . ':Y' . $write_col . ')',$total_format);	
//End Footer

?>