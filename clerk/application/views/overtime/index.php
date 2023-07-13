<script>
$(function() {
	datatable_loader(<?php echo $sidebar; ?>);	
	$('.overtime_action_link').hide();
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
<div class="break-bottom inner_top_option">	
	<?php if($sidebar == 1){ ?>
        <div class="datatable_withselect display-inline-block right-space">
            <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:change_overtime_request_status(this.value);">
                <option value="">With Selected:</option>                  
                <option value="Archive">Send to Archive</option>                    
            </select>
        </div>
	<?php }elseif($sidebar == 4){ ?>
        <div class="datatable_withselect display-inline-block right-space">
            <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:change_overtime_request_status(this.value);">
                <option value="">With Selected:</option>           
                <option value="Restore Archive">Restore Archived</option>                    
            </select>
        </div>
    <?php } ?>    
    <div class="select_dept display-inline-block right-space">
        <strong>Select Department:</strong> <select id="department_id" name="department_id" onchange="javascript:datatable_loader(<?php echo $sidebar; ?>);">
            <option value="" selected="selected">All</option>
            <?php foreach($departments as $d){ ?>
                <option value="<?php echo Utilities::encrypt($d->getId()); ?>"><?php echo $d->getTitle(); ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="clear"></div>
</div>
<form name="withSelectedAction" id="withSelectedAction">
	<div id="overtime_list_dt" class="dtContainer"></div>   
</form>
