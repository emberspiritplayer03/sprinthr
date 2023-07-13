<h2 class="field_title"><?php echo $title; ?></h2>
<script>
$(document).ready(function() {
$("#date_time_event").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true, maxDate: '+3M +10D'});

	$("#applicant_offer_job_add_form").validationEngine({scroll:false});

	$('#applicant_offer_job_add_form').ajaxForm({
		success:function(o) {
				if(o==1) {
					dialogOkBox('Successfully Added',{ok_url: 'recruitment/profile?rid=<?php echo Utilities::encrypt($details->id) ?>&hash=<?php echo $details->getHash();?>&status=<?php echo JOB_OFFERED; ?>#application_history'});	
					//location.href=base_url+ 'recruitment/profile?rid=<?php echo Utilities::encrypt($details->id) ?>&hash=<?php echo $details->getHash();?>&status=<?php echo JOB_OFFERED; ?>#application_history'; 
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
<form id="applicant_offer_job_add_form"  action="<?php echo url('recruitment/_update_applicant_event'); ?>" method="post"  name="applicant_examination_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<input type="hidden" id="applicant_id" name="applicant_id" value="<?php echo Utilities::encrypt($details->id); ?>"  />
<input type="hidden" id="application_status" name="application_status" value="<?php echo JOB_OFFERED; ?>"  />
<div id="form_main" class="employee_form"> 
    <div id="form_default">
      <table width="100%">
        <tr>
          <td class="field_label">&nbsp;</td>
          <td><div><strong>Confirm Job Offer to above applicant.</strong></div>
            <div><em>The applicant will have to be contacted and offered the job.</em></div></td>
        </tr>
        <tr>
          <td class="field_label">Offered By:</td>
          <td><input type="text" class="validate[required] text-input" name="hiring_manager_id" id="hiring_manager_id" /></td>
        </tr>
        <tr>
          <td class="field_label">Notes:</td>
          <td>
            <div id="status_dropdown_wrapper">
              <textarea class="validate[required]" name="notes" id="notes" cols="45" rows="5"></textarea>
              </div>
            </td>
        </tr>
      </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input type="submit" value="Offer a Job" class="curve blue_button" /></td>
          </tr>
        </table>       
    </div>
</div>

</form>
</div>


<script>


$('#hiring_manager_id').textboxlist({unique: true,max:1, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'recruitment/_autocomplete_load_scheduled_by'}
}}});
</script>

