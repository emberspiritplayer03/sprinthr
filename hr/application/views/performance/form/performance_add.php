<script>

$(document).ready(function() {
$("#add_period_from").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true});
$("#add_period_to").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true});
$("#due_date").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true});


	$("#employee_performance_form").validationEngine({scroll:false});

	$('#employee_performance_form').ajaxForm({
		success:function(o) {
			if(o==0){
				 dialogOkBox('Please Fill Up the Form Completely',{}) 
			}else {
				/*employee_id = o;
				$.post(base_url+"employee/_load_employee_hash",{employee_id:employee_id},
				function(o){
					$("#employee_hash").val(o);
					load_add_employee_confirmation(employee_id);
				});	*/
				
				window.location = "performance";
			}
			
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
	var t = new $.TextboxList('#reviewer_id', {plugins: {
	autocomplete: {
		minLength: 3,
		onlyFromValues: true,
		queryRemote: true,
		remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
	
	}
}});

	var t = new $.TextboxList('#employee_id', {plugins: {
	autocomplete: {
		minLength: 3,
		onlyFromValues: true,
		queryRemote: true,
		remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
	
	}
}});
	
	
});


</script>

<div id="formcontainer">
<div class="mtshad"></div>

<form id="employee_performance_form"  action="<?php echo url('performance/_insert_employee_performance'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add Employee Performance</h3>
<div id="form_main">
    <h3 class="section_title"><span>Employment Information</span></h3>
    <div id="form_default">      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" class="field_label">Performance Title:</td>
          <td align="left" valign="top">
          <div id="branch_dropdown_wrapper">
          <select class="validate[required] select_option" name="performance_id" id="performance_id" >
            <option value="" selected="selected">-- Select Performance --</option>
				<?php foreach($performance as $key=>$value) { ?>
                    <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
                <?php } ?>
          
          </select>
         </div> 
         </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Employee:</td>
          <td align="left" valign="top">
          <div id="department_dropdown_wrapper">
            <input type="text" class="text-input" name="employee_id" id="employee_id">
          </div> 
         </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Reviewer:</td>
          <td align="left" valign="top">
          <div id="position_dropdown_wrapper">
            <input type="text" class="text-input" name="reviewer_id" id="reviewer_id">
          </div>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Period From:</td>
          <td align="left" valign="top">
          <div id="status_dropdown_wrapper">
            <input type="text" class="text-input" name="period_from" id="add_period_from" />
          </div>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Period To:</td>
          <td align="left" valign="top"><input type="text" class="text-input" name="period_to" id="add_period_to" /></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Due Date:</td>
          <td align="left" valign="top"><input type="text" class="text-input" name="due_date" id="due_date" /></td>
        </tr>
      </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td>
                    <input type="submit" value="Add New Employee Performance" class="curve blue_button" />
                    <a href="javascript:void(0)" onclick="javascript:cancel_add_employee_performance_form();">Cancel</a>
                </td>
            </tr>
        </table>            
    </div>
</div><!-- #form_main -->
</div><!-- #formwrap -->
</form>

</div>
<div id="error_message"></div>
