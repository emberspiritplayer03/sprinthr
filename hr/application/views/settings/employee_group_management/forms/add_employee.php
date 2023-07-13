<script>
	$(function() {
		var h_company_structure = $('#company_structure_id_employee_add').val();
		var t = new $.TextboxList('#h_employee_id', {max:100,plugins: {
			autocomplete: {
				minLength: 3,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'settings/ajax_get_employees_togroup?h_company_structure='+h_company_structure}
			
			}
		}});
	});
</script>

<form id="add_employee_form" name="add_employee_form" autocomplete="off" method="POST" action="<?php echo url('settings/_load_insert_employee_togroup'); ?>">
<input type="hidden" id="company_structure_id_employee_add" name="company_structure_id_employee_add" class="company_structure_wrapper" value="<?php echo $h_company_structure; ?>" />
<input type="hidden" id="token_add" class="token_wrapper" name="token_add" value="<?php echo $token; ?>" />

<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
               <td style="width:15%" align="left" valign="middle">Employee:</td>
               <td style="width:15%" align="left" valign="middle"><input class="validate[required] text-input" type="text" name="h_employee_id" id="h_employee_id" value="" /></td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeDialogBox('#add_employee_form_modal_wrapper','#add_employee_form')">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>
