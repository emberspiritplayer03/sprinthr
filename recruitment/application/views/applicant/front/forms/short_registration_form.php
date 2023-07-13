<script type="text/javascript">
$(function() {
	$("#date_applied").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
		changeYear: true});
	
	
	$("#add_candidate_form").validationEngine({scroll:true});
	$('#add_candidate_form').ajaxForm({
		success:function(o) {
			if(o.is_saved) {
				window.location = o.url + '?eid=' + o.eid + '&hid=' + o.hid;		
			} else {
				$("#token").val(o.token);
				dialogOkBox("Error : Application was not successful! Please try again",{ok_url: 'registration'});
			}
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		},
		dataType : 'json'
	});
	
});
</script>
<div id="formcontainer">
<div class="mtshad"></div>
<form action="<?php echo url('applicant/_save_applicant_info'); ?>" method="post" enctype="multipart/form-data" name="add_candidate_form" id="add_candidate_form" >
<input type="hidden" name="job_id" id="job_id" value="<?php echo Utilities::encrypt($j->getJobId()); ?>" />
<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
<input type="hidden" name="aeid" id="aeid" value="<?php echo Utilities::encrypt($a->getId()); ?>" />
<input type="hidden" name="firstname" id="firstname" value="<?php echo $a->getFirstName(); ?>" />
<input type="hidden" name="lastname" id="lastname" value="<?php echo $a->getLastName(); ?>" />
<div id="formwrap">
    <div id="form_main">
    	<h3 class="section_title"><span>Application Information</span></h3>
        <div id="form_default">            
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="field_label">Applying for Position: </td>
            <td>
            <em class="note label"><?php echo $j->getJobTitle(); ?></em></td>
          </tr>
          <tr>
            <td class="field_label">Date Applied: </td>
            <td><input type="text" class="validate[required] text-input text" value="<?php echo date('Y-m-d'); ?>" name="date_applied" id="date_applied" /></td>
          </tr>
          <tr>
            <td valign="top" class="field_label">Email Address:</td>
            <td><input type="text" disabled="disabled" class="validate[required,custom[email]]" name="email_address" id="email_address" value="<?php echo $hdr_email_address; ?>"></td>
          </tr>   
          </table>
        </div>
        <div class="form_separator"></div>
        <div id="form_default" class="form_action_section">
            <table>
            	<tr>
                	<td class="field_label">&nbsp;</td>
                    <td>
                    	<input type="submit" value="Send Application" class="curve blue_button" /> <a class="blue_button" href="<?php echo url('applicant/profile'); ?>"><i class="icon-pencil icon-white"></i> Edit Profile</a> | <a href="<?php echo main_url("job_vacancy"); ?>">Back to Job Vacancy List</a>                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</form>
</div>