<?php
$tmp_array = array(
				array(
					"bill_date" 	 => $bill_date,
					"billing_period" => $period,
					"due_date" 		 => $due_date,
					"total_payroll"  => $gross_payroll,
					"pay_13th_mon"   => $month_13th,
					"service_fee"    => $total_service_fee,
					"mandated_sss"   => $total_contribution
					)
		);	
		
$align_right = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	)
);

$center = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);

$center_bold = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	),
	'font' => array(
		'bold' => true
	)
);

$dark_background = array(
	'fill' => array(
		'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('argb'=>'00000000')
	),
	'font' => array(
		'color' => array('argb'=>'FFFFFFFF')
	)
);

$border = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '00000000'),
		),
	),
);

$pe->getActiveSheet()->getStyle('D9:E12')->applyFromArray($border);
$pe->getActiveSheet()->getStyle('A1:F26')->applyFromArray($border);
$pe->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$pe->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$pe->getActiveSheet()->getColumnDimension('C')->setWidth(9);
$pe->getActiveSheet()->getColumnDimension('D')->setWidth(12);
$pe->getActiveSheet()->getColumnDimension('E')->setWidth(22);
$pe->getActiveSheet()->getColumnDimension('F')->setWidth(1);
$pe->getActiveSheet()->mergeCells('A5:E5');
$pe->getActiveSheet()->mergeCells('A14:E14');
$pe->getActiveSheet()->mergeCells('A24:E24');
$pe->getActiveSheet()->mergeCells('A20:E20');
$pe->getActiveSheet()->mergeCells('D9:E9');
$pe->getActiveSheet()->mergeCells('A26:E26');


$pe->write(0, 4, $address, $align_right);
$pe->write(1, 4, $street, $align_right);
$pe->write(2, 4, $city, $align_right);

//$pe->setWidth(0, 0, 30);
//$pe->setWidth(0, 1, 30);
//$pe->setWidth(0, 3, 15);
//$pe->setWidth(0, 4, 15);

$pe->write(4, 0, 'SEMI-MONTHLY STATEMENT OF ACCOUNT', $center_bold);
$pe->write(6, 1, $company_code);
$pe->write(7, 1, $company_name);
$pe->write(8, 1, $company_location);

$pe->write(8,  3, 'Billing Info', $dark_background);
$pe->write(9,  3, 'Bill Date');
$pe->write(10, 3, 'Billing Period');
$pe->write(11, 3, 'Due Date');

$pe->write(9, 4, $tmp_array[0]['bill_date'], $label_value_n);
$pe->write(10, 4, $tmp_array[0]['billing_period'], $label_value_n);
$pe->write(11, 4, $tmp_array[0]['due_date'], $label_value_n);

$pe->write(13, 0, 'Billing Summary', $dark_background);
$pe->write(14, 1, 'Total Payroll for the Period');
$pe->write(15, 1, 'Total 13th Month Pay for the Period');
$pe->write(16, 1, 'Service Fee (For the Period)');
$pe->write(17, 1, 'Mandated ER Share-SSS, PHIC, HDMF');
$pe->write(18, 1, '(Mo. Premium)');

$pe->write(14, 2, 'Php', $label_value_n);

$pe->write(14, 3, $tmp_array[0]['total_payroll'], $number);
$pe->write(15, 3, $tmp_array[0]['pay_13th_mon'], $number);
$pe->write(16, 3, $tmp_array[0]['service_fee'], $number);
$pe->write(17, 3, $tmp_array[0]['mandated_sss'], $number);


$pe->write(19, 0, ' *** See Attached Detailed Report ***', $center);
$pe->write(21, 2, 'Php', $center_bold);
$pe->write(21,3, '=SUM(D15:D18)',$total_format);

$pe->write(23, 0, "Thank you for your payment. We value your continued patronage.", $center);


?>