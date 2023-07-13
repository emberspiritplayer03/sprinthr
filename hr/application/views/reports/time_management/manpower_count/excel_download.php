<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>
<div style="width:80%">   
	<?php 
		if($report_type == 'detailed') {

	        include('_excel_download_detailed_header.php'); 
	        include('_excel_download_body_detailed.php');
	        include('_excel_download_footer.php');	

		} else {

	        include('_excel_download_header.php'); 
	        include('_excel_download_body.php');
	        include('_excel_download_footer.php');			

		}

    ?>
</div>
<?php
header("Content-type: application/x-msexcel;charset=UTF-8");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>