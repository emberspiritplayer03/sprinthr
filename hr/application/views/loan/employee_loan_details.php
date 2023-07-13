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
<!--<div id="formcontainer">-->
<div>
	<?php include('includes/_wrappers.php'); ?>
    <?php include('includes/_employee_details.php'); ?>
   <!-- <form name="withSelectedAction" id="withSelectedAction">
    <div class="break-bottom inner_top_option">    
        <div class="datatable_withselect display-inline-block right-space">
            <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:loanTypeWithSelectedAction(this.value);">
                <option value="">With Selected:</option>            
                <option value="print_loan">Print</option>                    
            </select>
        </div>    
        <div class="clear"></div>
    </div>
        <div id="employee_loan_list_dt_wrapper" class="dtContainer"></div> 
        <div id="employee_loan_details_list_dt_wrapper" class="dtContainer"></div> 
        
    </form>-->
    <div id="employee_loan_list_dt_wrapper" class="dtContainer"></div> 
    <div id="employee_loan_details_list_dt_wrapper" class="dtContainer"></div> 
</div>
<script>
	$(function() { load_employee_loan_list_dt('<?php echo Utilities::encrypt($employee_id); ?>'); });
</script>
