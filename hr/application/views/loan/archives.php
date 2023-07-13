<script type="text/javascript">
$(function() {
	$("#loanArchives").tabs();
});

function countChecked(form)
{
	if(form == 1){		
		var theForm = document.loanListWithSelectedAction;
	}else if(form == 2){
		var theForm = document.loanTypeWithSelectedAction;
	}else if(form == 3){
		var theForm = document.loanDeductionTypeWithSelectedAction;
	}
	
	var inputs     = theForm.elements['dtChk[]'];
	var is_checked = false;
	var cnt        = 0;
		
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            is_checked = theForm.elements[i].checked;
			if(is_checked){								 
			 	cnt++;
			}
    }
	
	return cnt;

}

function chkUnchk(form)
{
	if(form == 1){
		var theForm = document.loanListWithSelectedAction;
		var check_uncheck = theForm.elements['check_uncheck'];	
	}else if(form == 2){
		var theForm       = document.loanTypeWithSelectedAction;
		var check_uncheck = theForm.elements['loan_type_check_uncheck'];	
	}else if(form == 3){
		var theForm       = document.loanDeductionTypeWithSelectedAction;
		var check_uncheck = theForm.elements['loan_deduction_type_check_uncheck'];	
	}
	

	if(check_uncheck.checked == 1) {
		if(form == 1){	
			$('#check_uncheck').attr('title', 'Uncheck All');					
			$("#chkAction").removeAttr('disabled');
		}
		else if(form == 2){
			$('#loan_type_check_uncheck').attr('title', 'Uncheck All');			
			$("#chkActionLoanType").removeAttr('disabled');			
		}else if(form == 3){
			$('#loan_deduction_type_check_uncheck').attr('title', 'Uncheck All');			
			$("#chkActionLoanDeductionType").removeAttr('disabled');
		}
		var status = 1; 
		
	} else { 	
		if(form == 1){	
			$('#check_uncheck').attr('title', 'Check All');					
			$("#chkAction").attr('disabled',true);
		}else if(form == 2){
			$('#loan_type_check_uncheck').attr('title', 'Check All');			
			$("#chkActionLoanType").attr('disabled',true);					
		}else if(form == 3){
			$('#loan_deduction_type_check_uncheck').attr('title', 'Check All');			
			$("#chkActionLoanDeductionType").attr('disabled',true);					
		}
		
		var status = 0;
	}
	
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            theForm.elements[i].checked = status;
    }
}

</script>
<?php include('includes/_wrappers.php'); ?>
<div id="loanArchives">
	<ul>
		<li><a href="#tabs-loan-list" onclick="javascript:load_loan_archive_list_dt();">Loan List</a></li>
		<li><a href="#tabs-loan-type" onclick="javascript:load_loan_type_archive_list_dt();">Loan Type</a></li>
       <!-- <li><a href="#tabs-loan-deduction-type" onclick="javascript:load_loan_deduction_type_archive_list_dt();">Loan Deduction Type</a></li>	-->	
	</ul>
	<div id="tabs-loan-list"><?php include('includes/_loan_list_archives.php'); ?></div>
    <div id="tabs-loan-type"><?php include('includes/_loan_type_archives.php'); ?></div>
    <!--<div id="tabs-loan-deduction-type"><?php //include('includes/_loan_deduction_type_archives.php'); ?></div>-->
</div>
<script>
	load_loan_archive_list_dt();
</script>


