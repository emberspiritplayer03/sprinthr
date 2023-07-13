<?php 

echo $error;
header('Content-Type: application/msword;charset=utf-8');
header("Content-Disposition: attachment; filename=error_".date("Ymd").".doc");
?>