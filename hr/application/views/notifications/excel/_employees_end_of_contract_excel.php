<?php include_once('header_excel.php'); ?>
<div style="width:80%"> 
	<br/>  
	<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:536pt; line-height:12pt;">
		<tr>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Code</span></strong></td>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Date Hired</span></strong></td>
	        <td align="center" valign="top" style="border-bottom:none;"><strong>Date End of Contract</span></strong></td>
	    </tr>
		<?php foreach($data as $endo_employee) { ?>	
        <tr>
            <td><?=$endo_employee['employee_code']?></td>
            <td><?=$endo_employee['employee_name']?></td>
            <td><?=$endo_employee['hired_date']?></td>
            <td><?=$endo_employee['end_date']?></td>
        </tr>
       <?php } ?>

	</table>
</div>
<?php include_once('footer_excel.php'); ?>