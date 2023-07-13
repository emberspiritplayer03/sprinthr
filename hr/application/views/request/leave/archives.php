<script>
$(document).ready(function() {	
	$('#withSelectedAction').validationEngine({scroll:false});	
});

function countChecked(form)
{		
	if(form == 1){		
		var theForm = document.leaveTypewithSelectedAction;
	}else{
		var theForm = document.leaveRequestWithSelectedAction;
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
	if(form ==1){
		var theForm = document.leaveTypewithSelectedAction;
		var check_uncheck = theForm.elements['check_uncheck'];	
	}else{
		var theForm       = document.leaveRequestWithSelectedAction;
		var check_uncheck = theForm.elements['check_uncheck_sub'];	
	}
	

	if(check_uncheck.checked == 1) {
		if(form == 1){	
			$('#check_uncheck').attr('title', 'Uncheck All');					
			$("#chkAction").removeAttr('disabled');
		}
		else{
			$('#check_uncheck_sub').attr('title', 'Uncheck All');			
			$("#chkActionSub").removeAttr('disabled');
		}		
		var status = 1; 
		
	} else { 	
		if(form == 1){	
			$('#check_uncheck').attr('title', 'Check All');					
			$("#chkAction").attr('disabled',true);
		}else{
			$('#check_uncheck_sub').attr('title', 'Check All');			
			$("#chkActionSub").attr('disabled',true);					
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
<form name="leaveRequestWithSelectedAction" id="leaveRequestWithSelectedAction">
<div class="break-bottom inner_top_option">
	<div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkActionSub" id="chkActionSub" onchange="javascript:archiveWithSelectedAction();">
            <option value="">With Selected:</option>                    
            <option value="restore_leave_request">Restore Archived</option>                    
        </select>
    </div>	
    <div class="clear"></div>
</div>
    <div id="leave_list_dt_wrapper" class="dtContainer"></div>    
</form>
<script>
	$(function() { load_leave_list_archives_dt(); });
</script>
