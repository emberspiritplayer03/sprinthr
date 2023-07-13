
<script type="text/javascript">
$(function() {
var t = new $.TextboxList('#gp_employee_id', {plugins: {
			autocomplete: {
				minLength: 3,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'overtime/ajax_get_employees_autocomplete'}
			
			}
		}});

});
</script>




<div id="form_main" class="inner_form popup_form">
	<form name="addGPExemptedEmployees" id="addGPExemptedEmployees" method="post" action="<?php echo url('settings/add_gp_exempted_employees'); ?>">   
    <div id="form_default">
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
        <tr>
          <td valign="top" class="field_label">Select Employee names:</td>
      	</tr>
      	<tr>
          <td>
        	 <input class="validate[required] text-input" type="text" name="gp_employee_id" id="gp_employee_id" value="" style="width:100%" />
          </td>
        </tr>  
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addGPExemptedEmployees');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>