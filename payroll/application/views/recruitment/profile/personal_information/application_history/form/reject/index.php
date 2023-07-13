<h2 class="field_title"><?php echo $title; ?></h2>
<script>
$(document).ready(function() {
$("#date_time_event").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true,maxDate: '+1M +10D'});

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
<form id="applicant_reject_add_form"  action="<?php echo url('recruitment/_update_application_rejected'); ?>" method="post"  name="applicant_examination_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="application_history_id" name="application_history_id" value="<?php echo $history->getId(); ?>" />

<div id="form_main" class="employee_form"> 
    <div id="form_default">
      <table width="100%">
        <tr>
          <td class="field_label">&nbsp;</td>
          <td><div><strong>Confirm Reject of above application</strong></div>
            <div><em>This will send an email to the applicant informing of the rejection</em></div></td>
        </tr>
        <tr>
          <td class="field_label">Disapproved By:</td>
          <td><input type="text" class="validate[required] text-input" name="hiring_manager_id" id="hiring_manager_id" /></td>
        </tr>
        <tr>
          <td class="field_label">Date:</td>
          <td><input name="date_time_event" type="text" id="date_time_event" value="<?php echo $history->getDateTimeEvent(); ?>" /></td>
        </tr>
        <tr>
          <td class="field_label">Reason:</td>
          <td>
            <div id="status_dropdown_wrapper">
              <textarea name="notes" id="notes" cols="45" rows="5"><?php echo $history->notes; ?></textarea>
              </div>
            </td>
        </tr>
      </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="27%" class="field_label">&nbsp;</td>
            <td width="73%"><input type="submit" value="Rejected / Failed" class="curve blue_button" /> <a href="javascript:void(0);" onclick="javascript:loadApplicationHistoryTable();">Cancel</a></td>
          </tr>
        </table>
        
    </div>
</div>
</form>
</div>


<script>
var t = new $.TextboxList('#hiring_manager_id', {max:1,plugins: {
	autocomplete: {
		minLength: 3,
		onlyFromValues: true,
		queryRemote: true,
		remote: {url: base_url + 'recruitment/_autocomplete_load_scheduled_by'}
	}
}});

</script>
<?php if($a) { ?>
<script>
t.add('Entry',<?php echo $a->id ?>, '<?php echo $a->lastname. ', '. $a->firstname; ?>');
</script>
<?php 
}?>

