<h2 class="field_title"><?php echo $title; ?></h2>
<script>
$(document).ready(function() {
$("#date_time_event").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true, maxDate: '+3M +10D'});

	$("#applicant_interview_add_form").validationEngine({scroll:false});

	$('#applicant_interview_add_form').ajaxForm({
		success:function(o) {
				if(o==1) {
					//dialogOkBox('Successfully Added',{ok_url: 'recruitment/profile?rid=<?php echo Utilities::encrypt($details->id) ?>&hash=<?php echo $details->getHash();?>&status=1#application_history'});	
					$("#application_history").val("");
					dialogOkBox('Successfully Added',{ok_url: 'recruitment/profile?rid=<?php echo Utilities::encrypt($details->id) ?>&hash=<?php echo $details->getHash();?>&status=<?php echo INTERVIEW; ?>#application_history'});	
					//location.href=base_url+ 'recruitment/profile?rid=<?php echo Utilities::encrypt($details->id) ?>&hash=<?php echo $details->getHash();?>&status=<?php echo INTERVIEW; ?>#application_history'; 
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

<form id="applicant_interview_add_form"  action="<?php echo url('recruitment/_update_applicant_event'); ?>" method="post"  name="applicant_examination_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<input type="hidden" id="applicant_id" name="applicant_id" value="<?php echo Utilities::encrypt($details->id); ?>"  />
<input type="hidden" id="application_status" name="application_status" value="<?php echo INTERVIEW; ?>"  />
<div id="form_main" class="employee_form"> 
    <div id="form_default">
      <table width="100%">
        <tr>
          <td class="field_label">Date:</td>
          <td><input type="text" class="text-input" name="date_time_event" id="date_time_event" /></td>
        </tr>
        <tr>
          <td class="field_label">Time:</td>
          <td>
          	<select class="validate[required] select_option" name="time" id="time" >
              <option value="">-- Select Time --</option>
             
               <?php foreach($time as $key=>$value) { ?>
             	 <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php } ?>
      
              </select>
          </td>
        </tr>
        <tr>
          <td class="field_label">Interviewer:</td>
          <td>
          <div id="position_dropdown_wrapper">
            <input type="text" class="validate[required] text-input" name="hiring_manager_id" id="hiring_manager_id" />
          </div>
          </td>
        </tr>
        <tr>
          <td class="field_label">&nbsp;</td>
          <td>
          <div id="status_dropdown_wrapper">
            <textarea name="notes" id="notes" cols="45" rows="5"></textarea>
          </div>
          </td>
        </tr>
      </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input type="submit" value="Save Schedule" class="curve blue_button" /></td>
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

