<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">	
	<tr>
    	<td style="border:none; font-size:16pt;" align="left"><strong>DEDUCTION</strong></td>
    	<td style="border:none;">&nbsp;</td>
    </tr>
    <tr>
    	<td style="border:none; font-size:14pt;" align="left"><strong>Date Printed</strong></td>
    	<td style="border:none;"><?php echo date('Y-m-d'); ?></td>
    </tr>
</table>	
<br /><br />
<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">
  <tr>
  	<td style="border-bottom:none;"><strong>Employee Name</strong></td>
    <td style="border-bottom:none;"><strong><?php echo($e ? $e->getLastname() . ', ' . $e->getFirstname() : ''); ?></strong></td>    
  </tr>
  <tr>
  	<td style="border-bottom:none;"><strong>Type of Deduction</strong></td>
    <td style="border-bottom:none;"><strong>
    	<?php 
			$glt = G_Loan_Type_Finder::findById($gel->getTypeOfLoanId());
			echo($glt ? $glt->getLoanType() : '');
		?>
    </strong></td>    
  </tr>
  <tr>
  	<td style="border-bottom:none;"><strong>Interest Rate</strong></td>
    <td style="border-bottom:none;"><strong><?php echo($gel ? $gel->getInterestRate() . "%" : ''); ?></strong></td>    
  </tr>
  <tr>
  	<td style="border-bottom:none;"><strong>Loan Amount</strong></td>
    <td style="border-bottom:none;mso-number-format:'\@';"><strong><?php echo($gel ? number_format($gel->getLoanAmount(),2,".",",") : ''); ?></strong></td>    
  </tr>
  <tr>
  	<td style="border-bottom:none;"><strong>Balance</strong></td>
    <td style="border-bottom:none;color:#F00;mso-number-format:'\@';"><strong><?php echo($gel ? number_format($gel->getBalance(),2,".",",") : ''); ?></strong></td>    
  </tr>
  <tr>
  	<td style="border-bottom:none;"><strong>Deduction Period</strong></td>
    <td style="border-bottom:none;"><strong>
    	<?php 
			$dt = G_Loan_Deduction_Type_Finder::findById($gel->getTypeOfDeductionId());
			echo($dt ? $dt->getDeductionType() : '');
		?>
    </strong></td>    
  </tr> 
  <tr>
  	<td style="border-bottom:none;"><strong>No. of Installment</strong></td>
    <td style="border-bottom:none;mso-number-format:'\@';"><strong><?php echo($gel ? $gel->getNoOfInstallment() : ''); ?></strong></td>    
  </tr>
   <tr>
  	<td style="border-bottom:none;"><strong>Start Date</strong></td>
    <td style="border-bottom:none;"><strong><?php echo($gel ? $gel->getStartDate() : ''); ?></strong></td>    
  </tr> 
  <tr>
  	<td style="border-bottom:none;"><strong>End Date</strong></td>
    <td style="border-bottom:none;"><strong><?php echo($gel ? $gel->getEndDate() : ''); ?></strong></td>    
  </tr>
  <tr>
  	<td style="border-bottom:none;"><strong>Status</strong></td>
    <td style="border-bottom:none;"><strong><?php echo($gel ? $gel->getStatus() : ''); ?></strong></td>    
  </tr> 
</table>