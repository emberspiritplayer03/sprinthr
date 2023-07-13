<style>
table { font-size:11px; font-family:Verdana, Geneva, sans-serif; }
</style>
<?php ob_start();?>
<?php 
	include('includes/header.php');
	include('includes/body.php');
	include('includes/footer.php');
?>

<?php
header("Content-type: application/x-msexcel; charset=UTF-16LE"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

?>
