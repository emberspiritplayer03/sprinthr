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
<form name="withSelectedAction" id="withSelectedAction">
<div class="break-bottom inner_top_option">
	<div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:pendingLeaveWithSelectedAction(this.value);">
            <option value="">With Selected:</option>          
            <option value="archive">Send to Archive</option>                    
        </select>
    </div>
	<div class="select_dept display-inline-block right-space">
        <strong>Select Department:</strong> <select id="cmb_dept_id" onchange="javascript:load_leave_list_dt(this.value);">
            <option value="" selected="selected">All</option>
            <?php foreach($departments as $d){ ?>
                <option value="<?php echo $d->getId(); ?>"><?php echo $d->getTitle(); ?></option>
            <?php } ?>
        </select>
	</div>    
    <div class="clear"></div>
</div>
    <div id="leave_list_dt_wrapper" class="dtContainer"></div>    
</form>
<div id="import_leave_wrapper" style="display:none">
<?php include 'form/import_leave.php'; ?>
</div>
<script>
	$(function() { load_leave_list_dt(0); });
</script>
