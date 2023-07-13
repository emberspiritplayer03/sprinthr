<script>
$("#contact_details_form").validationEngine({scroll:false});
$('#contact_details_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#contact_details_wrapper").html('');
			loadPage("#contact_details");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="contact_details_form" name="form1" method="post" action="<?php echo url('recruitment/_update_contact_details'); ?>" style="display:none;">
<div id="form_main" class="employee_form">
<input type="hidden" name="applicant_id" value="<?php echo Utilities::encrypt($details->id); ?>" />
<div id="form_default">
  <h3 class="section_title"><?php echo $title; ?></h3>
  <table>
    <tr>
      <td class="field_label">Home Telephone:</td>
      <td><input class="text-input" type="text" name="home_telephone" id="home_telephone" value="<?php echo $details->home_telephone; ?>"  /></td>
    </tr>
    <tr>
      <td class="field_label">Mobile:</td>
      <td><input class="text-input" type="text" name="mobile" id="mobile" value="<?php echo $details->mobile; ?>"  /></td>
    </tr>
    <tr>
      <td class="field_label">Email Address:</td>
      <td><input type="text" class="validate[required,custom[email]] text-input" name="email_address" id="email_address" value="<?php echo $details->email_address; ?>"  /></td>
    </tr>
    <tr>
      <td class="field_label">Qualification:</td>
      <td><textarea name="qualification" id="qualification"><?php echo $details->qualification; ?></textarea></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadContactDetailsTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
