<?php include_once('header_excel.php'); ?>
<div style="width:80%">   
	<br/>
	<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:536pt; line-height:12pt;">
		<tr>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Leave Type</span></strong></td>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Credits Added</span></strong></td>
	    </tr>
		<?php 
			foreach($data as $key => $value){
		?>
	    	<tr>
	            <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['employee_name']; ?></td>
	            <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['leave_type']; ?></td>
	            <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['credits_added']; ?></td>
	        </tr>
	    <?php } ?>

	</table>
</div>
<?php include_once('footer_excel.php'); ?>