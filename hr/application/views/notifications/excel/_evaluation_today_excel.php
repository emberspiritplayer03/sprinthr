<?php include_once('header_excel.php'); ?>
<div style="width:80%">   
	<br/>
	<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:536pt; line-height:12pt;">
		<tr>
			<td align="center" valign="top" style="border-bottom:none;"><strong>Employee Code</span></strong></td>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
	          <td align="center" valign="top" style="border-bottom:none;"><strong>Branch Name</span></strong></td>
	          	          <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
	         <td align="center" valign="top" style="border-bottom:none;"><strong>Evaluation Date</span></strong></td>
	    </tr>
		<?php 
			foreach($data as $key => $value){
		?>
	    	<tr>
	    		<td align="left" valign="top" style="border-bottom:none;"><?php echo $value['employee_id']; ?></td>
	            <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['employee_name']; ?></td>
	            <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['department']; ?></td>
	              <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['branch_name']; ?></td>
	               <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['position']; ?></td>
	             <td align="left" valign="top" style="border-bottom:none;"><?php echo $value['evaluation_date']; ?></td>
	        </tr>
	    <?php } ?>

	</table>
</div>
<?php include_once('footer_excel.php'); ?>