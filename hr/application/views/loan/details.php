<script>
$(document).ready(function() {	
	$('#withSelectedAction').validationEngine({scroll:false});	
});

function countChecked()
{		
	var inputs     = document.withSelectedAction.elements['dtChk[]'];
	var is_checked = false;
	var cnt        = 0;
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            is_checked = theForm.elements[i].checked;
			if(is_checked){								 
			 	cnt++;
			}
    }
	
	return cnt;

}

function chkUnchk()
{
	var check_uncheck = document.withSelectedAction.elements['check_uncheck'];
	if(check_uncheck.checked == 1) {	
		$('#check_uncheck').attr('title', 'Uncheck All');									
		$("#chkAction").removeAttr('disabled');
		var status = 1; 
	} else { 
		$('#check_uncheck').attr('title', 'Check All');									
		$("#chkAction").attr('disabled',true);
		var status = 0;
	}
	
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            theForm.elements[i].checked = status;
    }
}

</script>
<?php include('includes/_wrappers.php'); ?>
<?php include('includes/_loan_details.php');?>

<br /><br />
<!--<div id="form_main" class="inner_form">-->
<!--<div class="break-bottom inner_top_option">-->
<h2 class="section_title" style="margin:0;">Loan Schedule&nbsp;&nbsp;<a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:addLoanPaymentForm('<?php echo Utilities::encrypt($gel->getId()); ?>');"><strong>+</strong><b>Add Payment</b></a></h2>
<!--</div>-->
<form name="withSelectedAction" id="withSelectedAction">
<input type="hidden" name="loan_id" id="loan_id" value="<?php echo Utilities::encrypt($gel->getId()); ?>" />    
    <!--<div class="datatable_withselect display-inline-block right-space" style="float:right;">
        <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:loanPaymentWithSelectedAction(this.value);">
            <option value="">With Selected:</option>            
            <option value="delete_loan_payment">Delete</option>                    
        </select>
    </div>    
    <div class="clear"></div>      
<div class="clear"></div>-->
   <div id="loan_payment_list_dt_wrapper" class="dtContainer"></div>       
</form>
<!--</div>-->
<script>
	$(function() { load_loan_details_list_dt('<?php echo Utilities::encrypt($gel->getId());?>'); });
</script>
