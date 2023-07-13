<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Date of Payment</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Amount Due</span></strong></td>
        <td align="center" valign="top"><strong>Amount Paid</span></strong></td>
        <td align="center" valign="middle" style="width:74pt; vertical-align:middle;"><strong>Remarks</strong></td>
    </tr>    
    <?php
		$counter = 1; 			
		foreach($details as $d){ 				
	?>
    <tr>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $d->getDateOfPayment(); ?></strong></td>
        <td align="center" valign="top" style="border-bottom:none;mso-number-format:'\@';"><strong><?php echo number_format($d->getAmount(),2,".",","); ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;mso-number-format:'\@';"><strong><?php echo number_format($d->getAmountPaid(),2,".",","); ?></span></strong></td>        
        <td align="center" valign="middle" style="width:74pt; vertical-align:middle;"><strong><?php echo $d->getRemarks(); ?></strong></td>
    </tr>  
    
    <?php 
		$counter++;
		} 
	?>
</table>