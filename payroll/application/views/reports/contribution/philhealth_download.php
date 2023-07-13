<?php
//$file = BASE_PATH . 'application/views/reports/contribution/payslip_source.xlsx';
$file = BASE_PATH . 'application/views/reports/contribution/excel_templates/philhealth_source.xlsx';
//$file = BASE_PATH . 'application/views/reports/contribution/test.xlsx';

//$objPHPExcel = new G_Excel_Writer();
//$new_sheet = $objPHPExcel->getActiveSheet();
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($file);
$sheet = $objPHPExcel->getActiveSheet();

$employee_counter = 1;
$sheet_start_row = 0;
$record_per_page = 23;

$total_forms = ceil(count($employees) / $record_per_page);
$last_key = end(array_keys($employees));
$form_number = 1;
foreach ($employees as $key => $e) {
	$position = G_Employee_Job_History_Finder::findCurrentJob($e)->getName();
	$middle_initial = '';
	$middle_name = $e->getMiddlename();
	if ($middle_name) {
		$middle_initial = $middle_name[0] .'.';
	}
	$birthdate = $e->getBirthdate();
	if ($birthdate != '0000-00-00' && $birthdate != '') {
		$birthdate = date('m-d-Y', strtotime($birthdate));	
	} else {
		$birthdate = '';	
	}
	$hired_date = $e->getHiredDate();
	if ($hired_date != '0000-00-00' && $hired_date != '') {
		$hired_date = date('m-d-Y', strtotime($hired_date));	
	} else {
		$hired_date = '';	
	}	
		
	$employee_start_row = (10 + $employee_counter + $sheet_start_row);
	$sheet->setCellValue('E' . ($employee_start_row), $e->getLastname());
	$sheet->setCellValue('F' . ($employee_start_row), $e->getFirstname());
	$sheet->setCellValue('G' . ($employee_start_row), $middle_initial);
	$sheet->setCellValue('A' . ($employee_start_row), $e->getSssNumber());
	$sheet->setCellValue('K' . ($employee_start_row), $hired_date);
	$sheet->setCellValue('H' . ($employee_start_row), $position);	
		
	if ((($employee_counter % $record_per_page) == 0) || $key == $last_key) { // if reaches record per page]
		$sheet->setCellValue('A'. (35 + $sheet_start_row), $employee_counter); // total number of employees
		//$sheet->setCellValue('D'. (34 + $sheet_start_row), $total_forms); // total number of forms
		//$sheet->setCellValue('B'. (34 + $sheet_start_row), $form_number); // current form number
		$sheet->setCellValue('I'. (36 + $sheet_start_row), "PAGE   {$form_number}  OF  {$total_forms}  SHEETS");
		
		
		$sheet_start_row += 38;	
		$employee_counter = 1;
		$form_number++;
	} else {
		$employee_counter++;
	}
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Er2.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>