<div id="form_main" class="employee_form">
	<div id="form_default">
    <h2>Loan Details</h2>
      <table width="100%">
      	<?php
			$lt  = G_Loan_Type_Finder::findById($gel->getTypeOfLoanId());
			$ldt = G_Loan_Deduction_Type_Finder::findById($gel->getTypeOfDeductionId());
			$e   = G_Employee_Finder::findById($gel->getEmployeeId());
		?>
        <tr>
          <td class="field_label">Employee Name:</td>
          <td><input class="text-input" readonly="readonly" type="text" value="<?php echo($e ? $e->getLastname() . ', ' . $e->getFirstname() : ''); ?>" name="e_name" id="e_name" /></td>
          <td class="field_label">Loan Type:</td>
          <td><input class="text-input" readonly="readonly" type="text" value="<?php echo($lt ? $lt->getLoanType() : ''); ?>" name="loan_type" id="loan_type" /></td>
        </tr>       
        <tr>
          <td class="field_label">Balance:</td>
          <td><input style="color:#F00;" class="text-input" readonly="readonly" value="<?php echo number_format($gel->getBalance(),2,".",","); ?>" type="text" name="balance" id="balance" /></td>
           <td class="field_label">Total Loan:</td>
          <td><input class="text-input" readonly="readonly" value="<?php echo number_format($gel->getLoanAmount(),2,".",","); ?>" type="text" name="total_loan" id="total_loan" /></td>
        </tr>       
        <tr>
          <td class="field_label">Deduction Type:</td>
          <td><input class="text-input" readonly="readonly" type="text" value="<?php echo($ldt ? $ldt->getDeductionType() : ''); ?>" name="deduction_type" id="deduction_type" /></td>
           <td class="field_label">No. of Installment:</td>
          <td><input class="text-input" readonly="readonly" type="text" value="<?php echo $gel->getNoOfInstallment(); ?>" name="no_of_installment" id="no_of_installment" /></td>
        </tr>                    
      </table>
	</div><!-- #form_default -->    
</div>