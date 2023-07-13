<script>
$("#supervisor_edit_form").validationEngine({scroll:false});
$('#supervisor_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#supervisor_wrapper").html('');
			loadPage("#supervisor");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="supervisor_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_supervisor'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<div id="form_default">
	<table>
  	 <tr>
  	   <td class="field_label">Supervisor:</td>
  	   <td><?php echo $subordinate->firstname . " " . $subordinate->lastname; ?></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="red_button" type="button" name="button" id="button" onclick="javascript:loadSupervisorDeleteDialog('<?php echo $details->id; ?>')" value="Delete Supervisor" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadSupervisorTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
