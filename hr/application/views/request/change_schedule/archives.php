<script>
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
<br />
<form name="withSelectedAction" id="withSelectedAction">
    <div align="left" style="float:left;">
    	<select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:changeScheduleWithSelectedAction(this.value);">
            <option value="">With Selected:</option>                  
            <option value="restore">Restore Archived</option>                    
        </select>
    </div>   
    <div class="clear"></div> 
    <div id="change_schedule_list_dt_wrapper" class="dtContainer"></div>   
</form>
<script>
$(function() { load_archive_change_schedule_list_dt(); });
</script>