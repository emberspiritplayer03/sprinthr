<script>
$(function() {
	datatable_loader(<?php echo $sidebar; ?>);	
});

function chkUnchk()
{
	var ckstatus = $('#check_uncheck').attr('checked');
	if(ckstatus == "checked") {
		var status = 1;
		$('#check_uncheck').attr('title', 'Uncheck All');									
		$("#chkAction").removeAttr('disabled');
	} else {
		var status = false;
		$('#check_uncheck').attr('title', 'Check All');									
		$("#chkAction").attr('disabled',true);
	}
	
	$('.dtCk').attr("checked",status);
	
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            theForm.elements[i].checked = status;
    }
}

function uncheckCheckAll() {
	$('#check_uncheck').attr('checked',false);	
	var a = getConcatCkvalue();
	if(a) {
		$("#chkAction").removeAttr('disabled');
	} else {
		$("#chkAction").attr('disabled',true);	
	}
}

function getConcatCkvalue() {
	var ck_value = '';
	var e		   = document.withSelectedAction.elements.length;
	var ckarr    = [];  
	var cnt = 0;	
	for(cnt=0;cnt<e;cnt++) {
		if(document.withSelectedAction.elements[cnt].name=='dtChk[]'){
			if(document.withSelectedAction.elements[cnt].checked) { ckarr[ckarr.length] = document.withSelectedAction.elements[cnt].value; }
		}
	}
	for (var i=0; i<ckarr.length; i++) {
	if(i == (ckarr.length - 1)){ ck_value = ck_value + ckarr[i];		
	} else{ ck_value = ck_value + ckarr[i] + ','; }}
	return ck_value;  
}


</script>
<form name="withSelectedAction" id="withSelectedAction">
<input type="hidden" id="from_period" name="from_period" value="<?php echo $from_period; ?>" />
<input type="hidden" id="to_period" name="to_period" value="<?php echo $to_period; ?>" />
<?php include('includes/_wrappers.php'); ?>
<div id="form_default">
<div class="break-bottom inner_top_option">
	<div class="detailscontainer_blue details_highlights" id="detailscontainer">
        <div class="earnings_period_selected">
            <div class="overtime_title_period"><?php echo $period_selected; ?></div>
        </div>
    </div>	
    	<div class="select_dept display-inline-block right-space">
            <strong>Select Department:</strong> <select class="select_option_sched" id="department_id" name="department_id" onchange="javascript:datatable_loader(<?php echo $sidebar; ?>);">
                <option value="" selected="selected">All</option>
                <?php foreach($departments as $d){ ?>
                    <option value="<?php echo Utilities::encrypt($d->getId()); ?>"><?php echo $d->getTitle(); ?></option>
                <?php } ?>
            </select>
        </div> 
    <?php if($download_url){ ?>
        <a id="import_undertime" class="gray_button vertical-middle" href="<?php echo $download_url; ?>"><i class="icon-excel icon-custom"></i> <b>Download Undertime</b></a>
    <?php } ?>
    <?php if($is_period_lock == G_Cutoff_Period::YES){ ?>
        <div class="pull-right">        	
            <span class="label label-important"><i class="icon-lock disabled"></i> Selected period is lock for changes</span>
        </div>
    <?php } ?>
    <div class="clear"></div>
</div>    
<div class="break-bottom">
	<div class="datatable_withselect display-inline-block right-space">
	<?php if($sidebar == 1){ ?>
		<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
            <select class="select_option_sched" disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:undertimeWithSelectedAction(this.value,1);">
                <option value="">With Selected:</option>
                <option value="approve">Approve</option>                
                <option value="archive">Send to Archive</option>                    
            </select>
        <?php } ?>
    <?php }elseif($sidebar == 2){ ?>
			<select class="select_option_sched" disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:undertimeWithSelectedAction(this.value,2);">
                <option value="">With Selected:</option>
                <option value="disapprove">Disapprove</option>                
                <option value="archive">Send to Archive</option>                    
            </select>
	<?php }elseif($sidebar == 3){ ?>
    	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
            <select class="select_option_sched" disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:undertimeWithSelectedAction(this.value,3);">
                <option value="">With Selected:</option>           
                <option value="restore">Restore Archived</option>                    
            </select>
        <?php } ?>
    <?php } ?>
    </div>    
    <div class="clear"></div>
</div>

    <div id="undertime_list_dt_wrapper" class="dtContainer"></div>  
</div>
</form>
