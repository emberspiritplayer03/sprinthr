<script>
var jqAction = jQuery.noConflict();
$(function() {
	$('#withSelectedAction').validationEngine({scroll:false});	
	load_loan_list_dt();	
	$("#chkAction").change(function(){
		var selected_action = $(this).val();		
		loanWithSelectedAction(selected_action);
	});
	jqAction('.dropdown-toggle').dropdown();	
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
<form name="withSelectedAction" id="withSelectedAction">
<div class="break-bottom inner_top_option">    
    <div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkAction" id="chkAction">
            <option value="">With Selected:</option> 
            <option value="view_details">View Details</option>              
            <option value="view_payment_history">View Payment Schedule</option>   
            <option value="loan_archive">Send to Archive</option>                     
        </select>
    </div>    
    <div class="clear"></div>
</div>
    <div id="loan_list_dt_wrapper" class="dtContainer"></div>    
</form>
