<?php
$worksheet2->write(0, 0, "WABY ENTERPRISE11", $header);
$worksheet2->write(1, 0, "NET PAYROLL TABULATION", $header);
$worksheet2->write(2, 0, date('F j, Y'), $header);

$worksheet2->set_column(4, 41, 10);
$worksheet2->set_column(0, 0, 10);
$worksheet2->set_column(1, 3, 15);

$worksheet2->write(4, 0, 'ID NO', $field_name);
$worksheet2->write(4, 1, 'Surname', $field_name);
$worksheet2->write(4, 2, 'First Name', $field_name);
$worksheet2->write(4, 3, 'Position', $field_name);

for ($i = 5; $i <= 10; $i++):
	$worksheet2->write($i, 0, 'G-1002', $data);
	$worksheet2->write($i, 1, 'Reyes', $data); 
	$worksheet2->write($i, 2, 'Efren', $data);
	$worksheet2->write($i, 3, 'Manager', $data);
endfor;
?>