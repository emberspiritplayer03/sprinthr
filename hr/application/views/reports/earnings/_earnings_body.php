<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:1pt; vertical-align:middle;"></td>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Applied To</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Title</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Status</span></strong></td>
        <td align="center" valign="top"><strong>Remarks</span></strong></td>        
        <td align="center" valign="middle" style="width:74pt; vertical-align:middle;"><strong>Taxable</strong></td>
        <td align="center" valign="middle" style="width:74pt; vertical-align:middle;"><strong>Amount</strong></td>
    </tr>    
    <?php
		$counter = 1; 			
		foreach($earnings as $ea){ 	

         $total_amount += $ea->getDescription();		
	?>
    <tr>
        <td align="center" valign="middle" style="width:5px; vertical-align:middle;text-align:left;"><?php echo $counter; ?>.</td>
    	<td align="center" valign="middle" style="width:150px; vertical-align:middle;text-align:left;"><?php echo $ea->getObjectDescription(); ?></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $ea->getTitle(); ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $ea->getStatus(); ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $ea->getRemarks(); ?></span></strong></td>                
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $ea->getIsTaxable(); ?></span></strong></td>
        <td align="center" valign="top" style="width:150px;border-bottom:none;mso-number-format:'\@';"><strong><?php echo $ea->getDescription(); ?></span></strong></td>        
    </tr>      
    <?php $counter++; } ?>    

    <tr>
        <td colspan="6" style="text-align: left"><b>TOTAL:</b></td>
        <td align="center" valign="top" style="width:150px;border-bottom:none;mso-number-format:'\@';">
            <b><?php echo number_format($total_amount,2); ?> </b></td>
    </tr>
</table>