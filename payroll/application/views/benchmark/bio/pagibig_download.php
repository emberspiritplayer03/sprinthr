<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>
<div style="width:80%">   
	<?php include('_pagibig_header.php'); ?>
    <?php include('_pagibig_body.php'); ?>
    <?php include('_pagibig_footer.php'); ?>    
</div>
<?php
header("Content-type: application/x-msexcel;charset=UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>