<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>
<div style="width:80%">   
	<?php include('_excel_download_header.php'); ?>
    <?php 
		switch($report_body_type)
		{
			case DETAILED:
				include('_excel_download_body_detailed.php'); 
				break;
			case SUMMARIZED:
				include('_excel_download_body_summarized.php'); 
				break;
			case INCOMPLETE_BREAK_LOGS:
				include('_excel_download_body_incomplete_break_logs.php'); 
				break;
			case NO_BREAK_LOGS:
				include('_excel_download_body_no_break_logs.php'); 
				break;
			case EARLY_BREAK_OUT:
				include('_excel_download_body_early_break_out.php'); 
				break;
			case LATE_BREAK_IN:
				include('_excel_download_body_late_break_in.php'); 
				break;
			default;
				include('_excel_download_body_summarized.php'); 
				break;
		}
	?>
    <?php include('_excel_download_footer.php'); ?>    
</div>
<?php
header("Content-type: application/x-msexcel;charset=utf-8'");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>