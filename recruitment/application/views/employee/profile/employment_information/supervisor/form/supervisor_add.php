<script>
$("#supervisor_add_form").validationEngine({scroll:false});
$('#supervisor_add_form').ajaxForm({
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
<form id="supervisor_add_form" name="form1" method="post" action="<?php echo url('employee/_update_supervisor'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
<table>
  	 <tr>
      <td class="field_label">Supervisor / Subordinates:</td>
      <td><select name="select" id="select" class="select_option">
        <option value="1">Your Subordinate</option>
        <option value="0">Your Supervisor</option>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Employee:</td>
      <td><input class="text-input" type="text" name="e_id" id="e_id" value="" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadSupervisorTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
<script>
$('#e_id').textboxlist({unique: true,max:1, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
}}});
</script>
