<?php ob_start();?>
<div style="width:80%">   
    <?php 
    	include('_excel_download_body.php'); 	
	?>
</div>
<?php
header("Content-type: application/x-msexcel;charset=UTF-8");
header("Content-Disposition: attachment;filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>