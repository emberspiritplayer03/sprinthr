<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>
<div style="width:80%">   
	<?php include('_excel_download_header.php'); ?>
	<?php if($r_type == 'general_leave') { ?>
    		<?php include('_excel_download_body_general.php'); ?>
   	<?php }elseif($r_type == 'general_incentive_leave') { ?>
    	  	<?php include('_excel_download_body_general_incentive.php'); ?>
    <?php }else{ ?>
    		<?php include('_excel_download_body_detailed.php'); ?>
    <?php } ?>
    <?php include('_excel_download_footer.php'); ?>    
</div>
<?php
header("Content-type: application/x-msexcel;charset=UTF-8");
header("Content-Disposition: attachment;filename=" . $filename);
header("Pragma: no-cache");
header("Expires: 0");
?>