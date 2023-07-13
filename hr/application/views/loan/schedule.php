<?php include('includes/_wrappers.php'); ?>
<?php include('includes/_loan_details.php');?>
<br /><br />
<div id="form_main" class="inner_form">
<form name="withSelectedAction" id="withSelectedAction">
<input type="hidden" name="loan_id" id="loan_id" value="<?php echo Utilities::encrypt($gel->getId()); ?>" />
   <?php   	
   	if($gel->getTypeOfDeductionId() == G_Employee_Loan::BI_MONTHLY){
		include('includes/_loan_schedule_bimonthly.php');
	}elseif($gel->getTypeOfDeductionId() == G_Employee_Loan::MONTHLY){
		include('includes/_loan_schedule_monthly.php');
	}elseif($gel->getTypeOfDeductionId() == G_Employee_Loan::QUARTERLY){
		include('includes/_loan_schedule_quarterly.php');
	}elseif($gel->getTypeOfDeductionId() == G_Employee_Loan::DAILY){
		include('includes/_loan_schedule_daily.php');
	}
   ?>   
</form>
</div>

