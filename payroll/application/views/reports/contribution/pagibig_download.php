<?php
$file = BASE_PATH . 'application/views/reports/contribution/excel_templates/pagibig_source.xlsx';

//$objPHPExcel = new G_Excel_Writer();
//$new_sheet = $objPHPExcel->getActiveSheet();
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($file);
$sheet = $objPHPExcel->getActiveSheet();

$employee_counter = 1;
$sheet_start_row = 0;
$record_per_page = 40;

$total_employees = count($employees);
$total_forms = ceil(count($employees) / $record_per_page);
$last_key = end(array_keys($employees));
$form_number = 1;
$ee_total = 0;
$er_total = 0;
$grand_total = 0;
foreach ($employees as $key => $e) {
	$ee_amount = 0;
	$er_amount = 0;	
	$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
	$ph = new G_Payslip_Helper($p);
		
	$employee_start_row = (15 + $employee_counter + $sheet_start_row);
	$sheet->setCellValue('C' . ($employee_start_row), $e->getLastname());
	$sheet->setCellValue('D' . ($employee_start_row), $e->getFirstname());
	$sheet->setCellValue('G' . ($employee_start_row), $e->getMiddlename());
	$sheet->setCellValue('A' . ($employee_start_row), $e->getPagibigNumber());
	$ee_amount = (float) $ph->getValue('pagibig');
	$er_amount = (float) $ph->getValue('pagibig_er');
	$ee_total += $ee_amount;
	$er_total += $er_amount;
	$grand_total += ($ee_amount + $er_amount);
	$sheet->setCellValue('I' . ($employee_start_row), $ph->getValue('pagibig'));
	$sheet->setCellValue('K' . ($employee_start_row), $ph->getValue('pagibig_er'));
	$sheet->setCellValue('M' . ($employee_start_row), '=SUM(I'.$employee_start_row.'+K'.$employee_start_row.')');	
		
	if ((($employee_counter % $record_per_page) == 0) || $key == $last_key) { // if reaches record per page]
		$sheet->setCellValue('C'. (57 + $sheet_start_row), $employee_counter); // total number of employees
		$sheet->setCellValue('N'. (66 + $sheet_start_row), $total_forms); // total number of forms
		$sheet->setCellValue('M'. (66 + $sheet_start_row), $form_number); // current form number
		$sheet->setCellValue('M'. (64 + $sheet_start_row), $submission_date); // date of submission
		$sheet->setCellValue('A'. (7 + $sheet_start_row), $month_covered);
		$sheet->setCellValue('D'. (7 + $sheet_start_row), $year_covered);
		
		if ($form_number == $total_forms) {			
			$sheet->setCellValue('E'. (59 + $sheet_start_row), $total_employees);
			$sheet->setCellValue('I'. (59 + $sheet_start_row), $ee_total);
			$sheet->setCellValue('K'. (59 + $sheet_start_row), $er_total);
			$sheet->setCellValue('M'. (59 + $sheet_start_row), $grand_total);
		}
		
		$sheet_start_row += 71;	
		$employee_counter = 1;
		$form_number++;
	} else {
		$employee_counter++;
	}
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="MCRF.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>