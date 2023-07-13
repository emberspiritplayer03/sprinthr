<div id="form_main" class="employee_form">
	<div id="form_default">
    <h2>Loan Details</h2>
      <table width="100%">
      	<?php
			$lt  = G_Loan_Type_Finder::findById($gel->getTypeOfLoanId());
			$ldt = G_Loan_Deduction_Type_Finder::findById($gel->getTypeOfDeductionId());
			$e   = G_Employee_Finder::findById($gel->getEmployeeId());
			
			$start_date	   			 = $gel->getStartDate();
			$n_installment 			 = $gel->getNoOfInstallment();	
			$balance_with_interest   = G_Employee_Loan_Helper::computeTotalAmountWithInterest($gel);
			$interest_paid			 = G_Employee_Loan_Helper::computeInterestPaid($gel);				
		?>
        <tr>
          <td class="field_label">Employee Name:</td>
          <td><input class="text-input" readonly="readonly" type="text" value="<?php echo($e ? $e->getLastname() . ', ' . $e->getFirstname() : ''); ?>" name="e_name" id="e_name" /></td>
          <td class="field_label">Deduction Type:</td>
          <td><input class="text-input" readonly="readonly" type="text" value="<?php echo($lt ? $lt->getLoanType() : ''); ?>" name="loan_type" id="loan_type" /></td>
        </tr>       
        <tr>
          <td class="field_label">Balance:</td>
          <td><input style="color:#F00;" class="text-input" readonly="readonly" value="<?php echo number_format($gel->getBalance(),2,".",","); ?>" type="text" name="balance" id="balance" /></td>
           <td class="field_label">Total Loan:</td>
          <td><input class="text-input" readonly="readonly" value="<?php echo number_format($gel->getLoanAmount(),2,".",","); ?>" type="text" name="total_loan" id="total_loan" /></td>
        </tr>       
        <tr>
          <td class="field_label">Deduction Period:</td>
          <td><input class="text-input" readonly="readonly" type="text" value="<?php echo($ldt ? $ldt->getDeductionType() : ''); ?>" name="deduction_type" id="deduction_type" /></td>
           <td class="field_label">No. of Installment:</td>
          <td><input class="text-input" readonly="readonly" type="text" value="<?php echo $gel->getNoOfInstallment(); ?>" name="no_of_installment" id="no_of_installment" /></td>
        </tr>
        <tr>
          <td class="field_label"></b> Interest:</td>
          <td>
                <div class="input-append">
                	<input style="width:173px;height:18px;" class="text-input" readonly="readonly" type="text" value="<?php echo $gel->getInterestRate(); ?>" name="total_with_interest" id="total_with_interest" />  
                    <span class="add-on">%</span>
                </div>
                  
          </td> 
          <td class="field_label"></b> Total Payment:</td>
          <td><input class="text-input" readonly="readonly" type="text" value="<?php echo 'Php' . number_format($balance_with_interest,2,".",",") . ' - with interest'; ?>" name="total_with_interest" id="total_with_interest" />          
          </td>         
        </tr>  
        <tr>
        	
           <td class="field_label">Total Interest Paid:</td>
           <td>           
          	<input class="text-input" readonly="readonly" type="text" value="<?php echo 'Php' . number_format($interest_paid,2,".",","); ?>" name="total_interest_paid" id="total_interest_paid" />
            </td>
          <td class="field_label">Start Date:</td>
          <td><input class="text-input" readonly="readonly" type="text" value="<?php echo date("F d, o",strtotime($gel->getStartDate())); ?>" name="start_date" id="start_date" /></td>     
        </tr>  
                  
      </table>
	</div><!-- #form_default -->    
</div>