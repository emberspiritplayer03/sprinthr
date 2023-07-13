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
<div class="earnings-dt-container">
	<form name="withSelectedAction" id="withSelectedAction">
	<input type="hidden" name="eid" id="eid" value="<?php echo $eid; ?>" />
	<div class="break-bottom inner_top_option">
		<div class="detailscontainer_blue details_highlights" id="detailscontainer">
	        <div class="earnings_period_selected">
	            <div class="overtime_title_period"><?php echo $period_selected; ?>
	                <?php echo G_Cutoff_Period_Helper::showPeriodNavigation($cutoff_id, $location);?>
	            </div>
	        </div>
		</div>	
	    <div class="pull-right earnings-total total bold">Total:&nbsp;<span class="float-right"><span class="label label-info" id="total_pending"></span></span></div>
	    <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	    
	        <div class="datatable_withselect display-inline-block right-space">
	            <select class="vertical-middle" disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:withSelectedPendings(this.value);">
	                <option value="">With Selected:</option>            
	                <option value="earning_approve">Approve</option>      
	                <option value="earning_archive">Send to Archive</option>                     
	            </select>
	        </div> 
	    <?php }else{ ?>        
	        <div class="pull-right">        	
	            <span class="label label-important"><i class="icon-lock disabled"></i> Selected period is lock for changes</span>
	        </div>
	    <?php } ?>
	    	<?php if($download_url){ ?>
	            <a id="import_undertime" class="gray_button vertical-middle" href="<?php echo $download_url; ?>"><i class="icon-excel icon-custom vertical-middle"></i> <b>Download Earnings</b></a>
	        <?php } ?>
	    <div class="clear"></div>
	</div>
	    <div id="earnings_list_dt_wrapper" class="dtContainer"></div>    
	</form>
</div>
<script>
	$(function() {
		 load_earnings_list_dt("<?php echo $eid; ?>");
		 load_sum_pending_earnings("<?php echo $eid; ?>");
	});
</script>
