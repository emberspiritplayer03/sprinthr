<script>
$(document).ready(function() {
$("#date_submitted").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true,maxDate: '+10D'});

	$("#applicant_reject_add_form").validationEngine({scroll:false});

	$('#applicant_reject_add_form').ajaxForm({
		success:function(o) {
			if(o==1) {
				dialogOkBox("Successfully Updated",{});	
				$("#application_history_wrapper").html('');
				loadPage('#application_history');
			}else {
				dialogOkBox(o,{});	
			}
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
});

</script>
<div>

<form id="applicant_reject_add_form"  action="<?php echo url('recruitment/_update_application_submission_date'); ?>" method="post"  name="applicant_examination_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="application_history_id" name="application_history_id" value="<?php echo $history->getId(); ?>" />

<div id="form_main" class="employee_form"> 
	<h3 class="section_title"><?php echo $title; ?></h3>
    <div id="form_default">
      <table width="100%">
        <tr>
          <td class="field_label">Date Submitted:</td>
          <td><input name="date_submitted" type="text" class="validate[required]" id="date_submitted" value="<?php echo $history->getDateTimeEvent(); ?>" /></td>
        </tr>
        <tr>
          <td class="field_label">Notes:</td>
          <td><textarea name="notes" id="notes"><?php echo $history->getNotes();  ?></textarea></td>
        </tr>
      </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td>
            <a class="delete_link red float-right" onclick="javascript:loadDeleteApplicationHistoryDialog('<?php echo Utilities::encrypt($history->getId()); ?>')" href="javascript:void(0);"><span class="delete"></span>Delete Application History
</a>
            <input type="submit" value="Update" class="curve blue_button" />
            <a href="javascript:void(0);" onclick="javascript:loadApplicationHistoryTable();">Cancel</a></td>
          </tr>
        </table>
        
    </div>
</div>
</form>
</div>
