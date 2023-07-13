<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
    	<td align="center" valign="middle" rowspan="3" style="width:90pt; vertical-align:middle;"><strong>Pag-IBIG ID No.</strong></td>
        <td align="center" valign="top" colspan="7" style="border-bottom:none;"><strong>NAME OF EMPLOYEES</span></strong></td>
        <td align="center" valign="top" colspan="5"><strong>CONTRIBUTIONS</span></strong></td>
        <td align="center" valign="middle" rowspan="3" style="width:74pt; vertical-align:middle;"><strong>REMARKS</strong></td>
    </tr>
    <tr>
    	<td align="center" valign="top" rowspan="2" colspan="2" style="font-size:8pt; width:90pt; border-top:none; border-left:none; border-right:none;"><strong><i>Last Name</i></strong></td>
        <td align="center" valign="top" rowspan="2" style="font-size:8pt; width:95pt; border-top:none; border-left:none; border-right:none;"><strong><i>First Name</i></strong></td>
        <td align="center" valign="top" colspan="2" style="font-size:8pt; width:86pt; border:none; height:10pt;"><strong><i>Name Extension</i></strong></td>
        <td align="center" valign="top" colspan="2" rowspan="2" style="font-size:8pt; width:98pt;  border-top:none; border-left:none; border-right:none;"><strong><i>Middle Name</i></strong></td>
        <td align="center" valign="bottom" colspan="2" rowspan="2" style="width:98pt;"><strong>EMPLOYEE</strong></td>
        <td align="center" valign="bottom" colspan="2" rowspan="2" style="width:98pt;"><strong>EMPLOYER</strong></td>
        <td align="center" valign="bottom" rowspan="2" style="width:75pt;"><strong>TOTAL</strong></td>
    </tr>   
	<tr>
        <td align="center" valign="top" colspan="2" style="font-size:8pt; width:86pt; border-left:none; border-right:none; border-top:none;"><strong><i>(Jr., III, etc.)</i></strong></td>
    </tr>
    <?php
		$counter = 1; 	
		$x = 1;	
		foreach($employees as $e){ 		
		$p = G_Payslip_Finder::findByEmployeeAndDateRange($e, $from, $to);		
		$ph = new G_Payslip_Helper($p);
		$ee_amount = (float) $ph->getValue('pagibig');
		$er_amount = (float) $ph->getValue('pagibig_er');
		$e_total   = (float) $ee_amount + $er_amount; 
		$ee_gtotal += $ee_amount;
		$er_gtotal += $er_amount; 
		$bg_gray = "background:#ccc;";
		$bg_white = "background:#fff;";
		
		if($x==1){
			$bg = $bg_gray;	
			$x=2;
		}else{
			$bg = $bg_white;	
			$x=1;
		}
	?>
    <tr>
    	<td align="left" valign="top" style="mso-number-format:'\@';border-top:none; border-bottom:none; <?php echo $bg; ?>">
        	<strong><?php echo $e->getPagibigNumber();?></strong></span>
        </td>
        <td valign="top" align="left" style="width:20pt; border-top:none; border-bottom:none; border-right:none; <?php echo $bg; ?>">
        	<strong><?php echo $counter; ?><span>. </span></strong>
        </td>
        <td valign="top" style="border-top:none; border-bottom:none; border-right:none; border-left:none; <?php echo $bg; ?>">
        	<strong><?php echo $e->getLastname();?></strong>
        </td>
        <td valign="top" style="border-top:none; border-bottom:none; border-right:none; border-left:none; <?php echo $bg; ?>">
        	<strong><?php echo $e->getFirstname(); ?></strong>
        </td>
        <td valign="top" colspan="2" style="border-top:none; border-bottom:none; border-right:none; border-left:none; <?php echo $bg; ?>">
        	<strong><?php echo $e->getExtensionName(); ?></strong>
        </td>
        <td valign="top" colspan="2" style="border-top:none; border-bottom:none; border-left:none; <?php echo $bg; ?>">
        	<strong><?php echo $e->getMiddlename(); ?></strong>
        </td>
        <td align="center" valign="top" colspan="2" style="mso-number-format:'\@';border-top:none; border-bottom:none; <?php echo $bg; ?>">
        	<strong><?php echo number_format($ee_amount,2,".",","); ?></strong>
        </td>
        <td align="center" valign="top" colspan="2" style="mso-number-format:'\@';border-top:none; border-bottom:none; <?php echo $bg; ?>">
        	<strong><?php echo number_format($er_amount,2,".",","); ?></strong>
        </td>
         <td align="center" valign="top" style="mso-number-format:'\@';border-top:none; border-bottom:none; <?php echo $bg; ?>">
        	<strong><?php echo number_format($e_total,2,".",","); ?></strong>
        </td>   
        <td align="center" valign="top" style="border-top:none; border-bottom:none; <?php echo $bg; ?>">
        </td>                 
    </tr>
    
    <?php 
		$counter++;
		} 
	?>
</table>