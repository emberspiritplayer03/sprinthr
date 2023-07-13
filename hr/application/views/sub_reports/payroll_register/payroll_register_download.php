<?php
$fname = tempnam("/tmp", "simple.xls");
//include 'application/views/report/payroll_register/_functions.php';

$workbook = &new writeexcel_workbook($fname);
$header =& $workbook->addformat();
$header->set_bold();

$field_name =& $workbook->addformat();
$field_name->set_bold();
$field_name->set_text_wrap();

$data =& $workbook->addformat();
$data->set_align('left');
$data->set_size(8);

$text =& $workbook->addformat(array(size => 8, bold => 0, align => 'left'));
$number =& $workbook->addformat(array(num_format => '#,##0.00', size => 8, bold => 0, align => 'right'));

$worksheet = &$workbook->addworksheet('Gross Tabulation');
include 'application/views/sub_reports/payroll_register/sheet1.php';

$worksheet2 = &$workbook->addworksheet('Net Tabulation');
include 'application/views/sub_reports/payroll_register/sheet2.php';

$worksheet2 = &$workbook->addworksheet('Cash Advances');
include 'application/views/sub_reports/payroll_register/sheet3.php';

$worksheet4 = &$workbook->addworksheet('Service Fee Tabulation');
include 'application/views/sub_reports/payroll_register/sheet4.php';

$worksheet5 = &$workbook->addworksheet('E.R. Share');
include 'application/views/sub_reports/payroll_register/sheet5.php';

$worksheet6 = &$workbook->addworksheet('Payslip');
include 'application/views/sub_reports/payroll_register/sheet6.php';

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"Payroll_Register_". $from ."_to_". $to .".xls\"");
header("Content-Disposition: inline; filename=\"Payroll_Register_". $from ."_to_". $to .".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);	
?>

