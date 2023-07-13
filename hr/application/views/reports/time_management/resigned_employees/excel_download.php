<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>
<div style="width:80%">   
	<?php include('_excel_download_header.php'); ?>
    <?php include('_excel_download_body.php'); ?>
    <?php include('_excel_download_footer.php'); ?>    
</div>
<?php
header("Content-type: application/x-msexcel;charset=utf-8'"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>