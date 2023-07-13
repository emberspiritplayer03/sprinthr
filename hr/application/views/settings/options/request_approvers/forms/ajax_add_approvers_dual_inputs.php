<script>
function disableTextBox(obj_txtboxlist_id,obj_default_id,checkbox_id){	
	if($("#" + checkbox_id).is(':checked')){		
		$("#" + obj_txtboxlist_id).hide();
		$("#" + obj_default_id).show();
	}else{		
		$("#" + obj_txtboxlist_id).show();
		$("#" + obj_default_id).hide();
	}
}
$(function() {	
	var pos = new $.TextboxList('#positions', {unique: true,plugins: {
			autocomplete: {
				minLength: 1,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'settings/ajax_get_positions_autocomplete'}
			
			}
		}});
	
	var emp = new $.TextboxList('#employees', {unique: true,plugins: {
			autocomplete: {
				minLength: 1,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'settings/ajax_get_employees_autocomplete'}
			
			}
		}});
});
</script>
<div id="form_main" class="inner_form popup_form">
<form method="post" id="add_request_approvers" name="add_request_approvers" action="<?php echo url($action);?>">
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="request_id" name="request_id" value="<?php echo Utilities::encrypt($gsr->getId()); ?>" />

    <div id="form_default">
        <table width="100%">           
          <tr>
            <td>
               Positions
            </td>
             <td>
             <div id="txt_positions">
               <input class="validate[required] text-input text" type="text" name="positions" id="positions" />
             </div>                
              <div id="txt_positions_default" style="display:none;">
              	<input type="text" value="Apply to all positions" readonly="readonly" />
              </div>
               <br />
               <input type="checkbox" onclick="javascript:disableTextBox('txt_positions','txt_positions_default',this.id);" name="apply_to_all_positions" id="apply_to_all_positions" />Apply to all
            </td>
          </tr>
          <tr>
            <td>
               Employees
            </td>
             <td>
             <div id="txt_employees">
               <input class="validate[required] text-input text" type="text" name="employees" id="employees" />
             </div> 
             <div id="txt_employees_default" style="display:none;">
             	<input type="text" value="Apply to all employees" readonly="readonly" />
             </div>
               <br />
               <input type="checkbox" onclick="javascript:disableTextBox('txt_employees','txt_employees_default',this.id);" name="apply_to_all_employees" id="apply_to_all_employees" />Apply to all
            </td>
          </tr>                   
        </table>        
	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog();">Cancel</a></td>
            </tr>
		</table>
    </div>
</form>
</div><!-- #form_main.inner_form -->   
