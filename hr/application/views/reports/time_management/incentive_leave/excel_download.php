<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>
<div style="width:80%">   
	<?php include('_excel_download_header.php'); ?>
    <?php include('_excel_download_body_detailed.php'); ?>
    <?php include('_excel_download_footer.php'); ?>    
</div>
<?php
header("Content-type: application/x-msexcel;charset=UTF-8");
header("Content-Disposition: attachment; filename=" . $filename);
header("Pragma: no-cache");
header("Expires: 0");
?>