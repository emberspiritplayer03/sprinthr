<?php
//title of worksheet / header
$address  = '3/F Unit 14 AMJB Bldg., Aguinaldo Highway,';
$street   = 'cor By-Pass Road, Palico IV';
$city     = 'Imus, Cavite';

$tmp_array = array(
				array(
					"employee_id"    => "WAB-11-001", 
					"bill_date" 	 => "11-Feb-12",
					"billing_period" => $period,
					"due_date" 		 => "14-Feb-12",
					"total_payroll"  => $gross_payroll,
					"pay_13th_mon"   => $month_13th,
					"service_fee"    => $total_service_fee,
					"mandated_sss"   => $total_sss
					)
		);	

$worksheet6->write(0, 0, $address, $addr_header_7);
$worksheet6->write(1, 0, $street, $addr_header_7);
$worksheet6->write(2, 0, $city, $addr_header_7);

$worksheet6->set_column(0, 0, 30);
$worksheet6->set_column(0, 1, 30);
$worksheet6->set_column(0, 3, 15);
$worksheet6->set_column(0, 4, 15);

$worksheet6->write(4, 1, 'SEMI-MONTHLY STATEMENT OF ACCOUNT', $template_name);
$worksheet6->write(6, 1, '001105', $addr_header_7);
$worksheet6->write(7, 1, $company_name, $addr_header_7);
$worksheet6->write(8, 1, 'Niog Road, Bacoor Cavite', $addr_header_7);

$worksheet6->write(8,  3, 'Billing Info', $label);
$worksheet6->write(9,  3, 'Bill Date', $label);
$worksheet6->write(10, 3, 'Billing Period', $label);
$worksheet6->write(11, 3, 'Due Date', $label);

$worksheet6->write(9, 4, $tmp_array[0]['bill_date'], $label_value_n);
$worksheet6->write(10, 4, $tmp_array[0]['billing_period'], $label_value_n);
$worksheet6->write(11, 4, $tmp_array[0]['due_date'], $label_value_n);

$worksheet6->write(13, 0, 'Billing Summary', $label);
$worksheet6->write(14, 1, 'Total Payroll for the Period', $label);
$worksheet6->write(15, 1, 'Total 13th Month Pay for the Period', $label);
$worksheet6->write(16, 1, 'Service Fee (For the Period)', $label);
$worksheet6->write(17, 1, 'Mandated ER Share-SSS, PHIC, HDMF', $label);
$worksheet6->write(18, 1, '(Mo. Premium)', $label);

$worksheet6->write(14, 2, 'Php', $label_value_n);

$worksheet6->write(14, 3, $tmp_array[0]['total_payroll'], $label_value_n);
$worksheet6->write(15, 3, $tmp_array[0]['pay_13th_mon'], $label_value_n);
$worksheet6->write(16, 3, $tmp_array[0]['service_fee'], $label_value_n);
$worksheet6->write(17, 3, $tmp_array[0]['mandated_sss'], $label_value_n);


$worksheet6->write(19, 0, ' *** See Attached Detailed Report ***', $label_value_n);
$worksheet6->write(21, 2, 'Php', $label_value_n);
$worksheet6->write_formula(21,3, '=SUM(D15:D18)',$label_value_n);

$worksheet6->write(23, 0, "Thank you for your payment. We value your continued patronage.", $label_value_n);


?>