<script type="text/javascript">
$(function() {
	$("#leaveArchives").tabs();
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


<input type="hidden" id="from_period" name="from_period" value="<?php echo $from_period; ?>"/>
<input type="hidden" id="to_period" name="to_period" value="<?php echo $to_period; ?>" />
<div id="leaveArchives">
	<ul>
		<li><a href="#tabs-leave-request" onclick="javascript:load_leave_list_archives_dt(0);">Leave Request</a></li>
		<li><a href="#tabs-leave-type" onclick="javascript:load_leave_type_archives_dt();">Leave Type</a></li>		
	</ul>
	<div id="tabs-leave-request"><?php include('_leave_request_archives.php'); ?></div>
    <div id="tabs-leave-type"><?php include('_leave_type_archives.php'); ?></div>
</div>
<script>
	$(function() { load_leave_list_archives_dt(0); });
</script>


