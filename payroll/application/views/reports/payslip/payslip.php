<?php
$file = BASE_PATH . 'application/views/reports/payslip/payslip_source.xlsx';
//$objPHPexcel = PHPExcel_IOFactory::load($file);
//$objWorksheet = $objPHPexcel->getActiveSheet();
//$style = $objWorksheet->getStyle('A1:N17');

//$objWorksheet->duplicateStyle($style, 'A18:N35');

//$objWorksheet->getCell('B5')->setValue('200');

//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="Payslip.xlsx"');
//header('Cache-Control: max-age=0');
//
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel2007');
//$objWriter->save('php://output');
//exit;

$objPHPExcel = new PHPExcel();
$new_sheet =  $objPHPExcel->getActiveSheet();
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader = $objReader->load($file);
$read_sheet = $objReader->getActiveSheet();
//$style = $read_sheet->getStyle('A1:N17');

$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 

foreach ($read_sheet->getRowIterator() as $row) {
   $cellIterator = $row->getCellIterator();
   foreach ($cellIterator as $cell) {
      if (($cell->getValue() != "")) {
         $coord = $cell->getCoordinate();
         //echo "Cell:$coord - " . $cell->getValue() ."<BR>";		 
		 //echo "Cell:". $cell->columnIndexFromString($cell->getColumn()) ." - " . $cell->getValue() ."<BR>";		 
		 $cells[$cell->getColumn()][$cell->getRow()] = $cell->getValue();	 
      }
   }
}

//echo '<pre>';
//print_r($cells);
//echo '</pre>';

$temp_cutoff_period = G_Cutoff_Period_Finder::findAll();
$temp_end = $temp_cutoff_period[0];
$cutoff_end_date = $temp_end->getPayoutDate();
$temp_start = end($temp_cutoff_period);
$cutoff_start_date = $temp_start->getPayoutDate();
$cutoff_period = array_reverse($temp_cutoff_period);

$header = array(
	'borders' => array(
		'top' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '00000000'),
		),
		'bottom' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '00000000'),
		),		
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	),
	'font' => array(
		'bold' => true
	)
);

$align_right = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	)
);

$align_center = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER	,
	)
);

$bold = array(
	'font' => array(
		'bold' => true
	)
);

$border_top = array(
	'borders' => array(
		'top' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '00000000'),
		)
	)
);

$border_bottom_double = array(
	'borders' => array(
		'bottom' => array(
			'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
			'color' => array('argb' => '00000000'),
		)
	)
);

$number = array(
	'numberformat' => array(
		'code' => '#,##0.00'
	),
);

$new_sheet->getColumnDimension('D')->setWidth(1);
$new_sheet->getColumnDimension('G')->setWidth(1);
$new_sheet->getColumnDimension('J')->setWidth(1);
$new_sheet->getColumnDimension('M')->setWidth(1);
$new_sheet->getColumnDimension('H')->setWidth(15);
$new_sheet->getColumnDimension('L')->setWidth(12);
$new_sheet->getColumnDimension('N')->setWidth(18);
$new_sheet->getColumnDimension('F')->setWidth(14);
$new_sheet->getColumnDimension('I')->setWidth(14);
$new_sheet->getColumnDimension('E')->setWidth(13);

$new_sheet->getPageMargins()->setLeft(0.5);
$new_sheet->getPageMargins()->setRight(0.5);

$start_row = 0;
foreach ($employees as $e) {
	$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
	$ph = new G_Payslip_Helper($p);
	$position = G_Employee_Job_History_Finder::findCurrentJob($e)->getName();
	$salary_type = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e)->getType();
	if ($salary_type == 'daily_rate') {
		$rate = $rate_daily = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e)->getBasicSalary();
		$employee[$e->getId()]['rate_hourly'] = $rate_daily / 8;
	} else if ($salary_type == 'hourly_rate') {
		$employee[$e->getId()]['rate'] = $rate_hourly = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e)->getBasicSalary();
		$employee[$e->getId()]['rate_hourly'] = $rate_hourly;	
	}	
	
	$new_sheet->setCellValue('C' . (3 + $start_row), strtoupper($e->getEmployeeCode()));
	$new_sheet->setCellValue('E' . (3 + $start_row), strtoupper($e->getName()));
	$new_sheet->setCellValue('E' . (3 + $start_row), strtoupper($e->getName()));
	$new_sheet->setCellValue('H' . (1 + $start_row), $period);
	$new_sheet->setCellValue('L' . (1 + $start_row), $payout);
	$new_sheet->setCellValue('I' . (2 + $start_row), $position);
	$new_sheet->setCellValue('B' . (5 + $start_row), $rate);
	$new_sheet->setCellValue('B' . (6 + $start_row), $ph->getValue('days_worked'));
	$new_sheet->setCellValue('C' . (7 + $start_row), ($ph->getValue('meal allowance')) ? $ph->getValue('meal allowance') : '                  -' );
	$new_sheet->setCellValue('C' . (6 + $start_row), ($ph->getValue('days_worked') * $rate));
	$new_sheet->setCellValue('C' . (8 + $start_row), ($ph->getValue('overtime') > 0) ? $ph->getValue('overtime') : '                  -' );
	$other_pay = (float) $ph->getValue('others') + (float) $ph->getValue('holiday') + (float) $ph->getValue('nightshift');
	$new_sheet->setCellValue('C' . (9 + $start_row), ($other_pay > 0) ? $other_pay : '                  -' );
	$ac = abs((float) $ph->getValue('accounts receivable'));
	$new_sheet->setCellValue('C' . (10 + $start_row), ($ac > 0) ? $ac : '                  -' );
	$new_sheet->setCellValue('C' . (16 + $start_row), $p->getGrossPay());
	$new_sheet->setCellValue('F' . (5 + $start_row), $p->getGrossPay());
	$new_sheet->setCellValue('F' . (16 + $start_row), $p->getGrossPay());
	$new_sheet->setCellValue('I' . (5 + $start_row), ($ph->getValue('sss') > 0) ? $ph->getValue('sss') : '                                -');	
	$new_sheet->setCellValue('I' . (6 + $start_row), ($ph->getValue('philhealth') > 0) ? $ph->getValue('philhealth') : '                                -');
	$new_sheet->setCellValue('I' . (7 + $start_row), ($ph->getValue('pagibig') > 0) ? $ph->getValue('pagibig') : '                                -');	
	$late_ut_amount = number_format((float) $ph->getValue('late_amount') + (float) $ph->getValue('undertime_amount'), 3);
	$new_sheet->setCellValue('I' . (8 + $start_row), ($late_ut_amount > 0) ? $late_ut_amount : '                                -');	
	$new_sheet->setCellValue('I' . (9 + $start_row), ($ph->getValue('id card') > 0) ? $ph->getValue('id card') : '                                -');	
	$new_sheet->setCellValue('I' . (10 + $start_row), ($ph->getValue('uniform') > 0) ? $ph->getValue('uniform') : '                                -');
	$new_sheet->setCellValue('I' . (11 + $start_row), ($ph->getValue('medical') > 0) ? $ph->getValue('medical') : '                                -');
	$new_sheet->setCellValue('I' . (12 + $start_row), ($ph->getValue('cash bond') > 0) ? $ph->getValue('cash bond') : '                                -');
	$new_sheet->setCellValue('I' . (13 + $start_row), ($ph->getValue('cash advance') > 0) ? $ph->getValue('cash advance') : '                                -');	
	$new_sheet->setCellValue('I' . (14 + $start_row), ($ph->getValue('items/goods') > 0) ? $ph->getValue('items/goods') : '                                -');
	$new_sheet->setCellValue('I' . (15 + $start_row), ($ph->getValue('placement fee') > 0) ? $ph->getValue('placement fee') : '                                -');
	$new_sheet->setCellValue('I' . (16 + $start_row), $ph->computeTotalDeductions());
	$new_sheet->setCellValue('L' . (8 + $start_row), $p->getNetPay());
	
	foreach ($cells as $column => $values) {
		foreach ($values as $row => $value) {
			$new_sheet->setCellValue($column . ($row + $start_row), $value);
		}
	}
	
	$cell_coordinate = 'A'. (1 + $start_row) .':N'. (17 + $start_row);
	$cell_coordinates[] = array('start'=>'A'. (1 + $start_row), 'end'=>'N'. (17 + $start_row));
	$new_sheet->getStyle('L'. (8 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (16 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (15 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (14 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (13 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (12 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (11 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (10 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (9 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (8 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (7 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (6 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('I'. (5 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('F'. (16 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('F'. (5 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('C'. (16 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('C'. (10 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('C'. (9 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('C'. (8 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('C'. (6 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('B'. (5 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('C'. (7 + $start_row))->applyFromArray($number);
	$new_sheet->getStyle('C'. (3 + $start_row))->applyFromArray($bold);
	$new_sheet->getStyle('A'. (16 + $start_row))->applyFromArray($bold);
	$new_sheet->getStyle('E'. (16 + $start_row))->applyFromArray($bold);
	$new_sheet->getStyle('F'. (16 + $start_row))->applyFromArray($bold);
	$new_sheet->getStyle('H'. (16 + $start_row))->applyFromArray($bold);
	$new_sheet->getStyle('I'. (16 + $start_row))->applyFromArray($bold);
	$new_sheet->getStyle('K'. (16 + $start_row))->applyFromArray($bold);
	$new_sheet->getStyle('N'. (16 + $start_row))->applyFromArray($bold);
	$new_sheet->getStyle('E'. (3 + $start_row))->applyFromArray($bold);
	$new_sheet->getStyle('K'. (8 + $start_row))->applyFromArray($bold);
	$new_sheet->getStyle('C'. (3 + $start_row))->applyFromArray($align_right);
	$new_sheet->getStyle('N'. (16 + $start_row))->applyFromArray($align_center);
	
	$new_sheet->mergeCells('A'. (4 + $start_row) .':C'. (4 + $start_row));
	$new_sheet->getStyle('A'. (4 + $start_row) .':C'. (4 + $start_row))->applyFromArray($header);
	$new_sheet->mergeCells('E'. (4 + $start_row) .':F'. (4 + $start_row));
	$new_sheet->getStyle('E'. (4 + $start_row) .':F'. (4 + $start_row))->applyFromArray($header);
	$new_sheet->mergeCells('H'. (4 + $start_row) .':I'. (4 + $start_row));
	$new_sheet->getStyle('H'. (4 + $start_row) .':I'. (4 + $start_row))->applyFromArray($header);	
	$new_sheet->mergeCells('K'. (4 + $start_row) .':N'. (4 + $start_row));
	$new_sheet->getStyle('K'. (4 + $start_row) .':N'. (4 + $start_row))->applyFromArray($header);
	$new_sheet->mergeCells('K'. (16 + $start_row) .':L'. (16 + $start_row));
	$new_sheet->getStyle('K'. (16 + $start_row) .':L'. (16 + $start_row))->applyFromArray($border_top);
	
	
	$new_sheet->getStyle('L'. (8 + $start_row))->applyFromArray($border_bottom_double);
	$new_sheet->getStyle('L'. (8 + $start_row))->applyFromArray($border_top);
	$new_sheet->getStyle('C'. (16 + $start_row))->applyFromArray($border_top);
	$new_sheet->getStyle('I'. (16 + $start_row))->applyFromArray($border_top);
	$new_sheet->getStyle('F'. (16 + $start_row))->applyFromArray($border_top);
	$new_sheet->getStyle('N'. (16 + $start_row))->applyFromArray($border_top);
	
	$new_sheet->getStyle($cell_coordinate)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF');
	$new_sheet->getStyle($cell_coordinate)->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$start_row += 18;	
}

//echo 9 % 3;
$total_array = count($cell_coordinates) - 1;
foreach ($cell_coordinates as $key => $values) {
	$counter = $key + 1;
	if ($start == '') {
		$start = $values['start'];
	}
	if (($counter % 3) == 0) {
		$end = $values['end'];
		$print_areas[] = $start .':'. $end;
		$start = '';
	}
	if ($key == $total_array) {
		$end = $values['end'];
		$print_areas[] = $start .':'. $end;
	}
}

$print_area = implode(',', $print_areas);
$new_sheet->getPageSetup()->setPrintArea($print_area);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Payslip_'. $from .'_to_'. $to .'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>