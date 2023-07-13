<script>
$("#issued_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#expiry_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#license_edit_form").validationEngine({scroll:false});
$('#license_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#license_wrapper").html('');
			loadPage("#license");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="license_edit_form" name="form1" method="post" action="<?php echo url('recruitment/_update_license'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="applicant_id" value="<?php echo Utilities::encrypt($details->applicant_id); ?>" />
<div id="form_default">
  <table>
    <tr>
       <td class="field_label">License Type:</td>
       <td><input type="text" class="validate[required] text-input" name="license_type" id="license_type" value="<?php echo $details->license_type; ?>" /></td>
    </tr>
     <tr>
       <td class="field_label">License Number:</td>
       <td><input type="text" class="validate[required] text-input" name="license_number" id="license_number" value="<?php echo $details->license_number; ?>" /></td>
     </tr>
     <tr>
       <td class="field_label">Issued Date:</td>
       <td><input type="text" class="validate[required] text-input" name="issued_date" id="issued_date" value="<?php echo $details->issued_date; ?>" /></td>
     </tr>
     <tr>
       <td class="field_label">Expiry Date:</td>
       <td><input type="text" class="validate[required] text-input" name="expiry_date" id="expiry_date" value="<?php echo $details->expiry_date; ?>" /></td>
     </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadLicenseDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete License</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadLicenseTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
