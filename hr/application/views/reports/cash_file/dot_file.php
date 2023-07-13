<?php ob_clean();
foreach( $data as $d){
	echo $d['employee_code'] . "," . $d['[employee_name'] . "," . $d['account'] . "," . $d['net_pay'] . $d['account'] . "\r\n";
}
header("Content-type: text/plain"); 
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
