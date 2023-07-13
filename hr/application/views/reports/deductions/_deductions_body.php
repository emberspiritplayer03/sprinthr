<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Applied To</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Title</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Status</span></strong></td>
        <td align="center" valign="top"><strong>Remarks</span></strong></td>        
        <td align="center" valign="middle" style="width:74pt; vertical-align:middle;"><strong>Taxable</strong></td>
        <td align="center" valign="middle" style="width:74pt; vertical-align:middle;"><strong>Amount</strong></td>
    </tr>    
    <?php
		$counter = 1; 			
		foreach($deductions as $ea){ 				
	?>
    <tr>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;text-align:left;">
        <?php 
				$eArray = Tools::convertStringToArray(",",unserialize($ea->getEmployeeId()));
				$counter = 1;
				foreach($eArray as $key => $value){
					if($value == 'All Employee'){
						echo "All Employee";
					}else{
						$e = G_Employee_Finder::findById($value);
						if($e){
							echo $counter . ". " . $e->getLastName() . ", " . $e->getFirstName() . '<br>';
							$counter++;
						}
					}
				}
			?>
        </td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $ea->getTitle(); ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $ea->getStatus(); ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $ea->getRemarks(); ?></span></strong></td>                
        <td align="center" valign="top" style="border-bottom:none;"><strong><?php echo $ea->getTaxable(); ?></span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;mso-number-format:'\@';"><strong><?php echo number_format($ea->getAmount(),2,".",","); ?></span></strong></td>        
    </tr>  
    
    <?php 
		$counter++;
		} 
	?>
    <tr>
    	<td align="center" valign="middle" style="width:90pt; vertical-align:middle;text-align:left;"><strong>Total</strong></td>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;mso-number-format:'\@';" colspan="5" style="text-align:right;"><strong><?php echo number_format($esum['gtotal'],2,".",","); ?></span></strong></td>
    </tr>
</table>