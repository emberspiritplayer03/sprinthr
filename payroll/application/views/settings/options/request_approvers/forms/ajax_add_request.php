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
	var dept = new $.TextboxList('#departments', {unique: true,plugins: {
			autocomplete: {
				minLength: 1,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'settings/ajax_get_departments_autocomplete'}
			
			}
		}});
		
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
	/*t.addEvent('blur',function(o) {
			load_show_specific_schedule();
		});*/

});

</script>

<form method="post" id="add_request" name="add_request" action="<?php echo url($action);?>">
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%">
          <tr>
            <td>
               Title
            </td>
             <td>
               <input class="validate[required] text-input text" type="text" name="title" id="title" />
            </td>
          </tr>
          <tr>
            <td>
               Type
            </td>
             <td>
               <select id="type" name="type">
               	<option value="<?php echo Settings_Request::OT ?>"><?php echo Settings_Request::OT ?></option>
                <option value="<?php echo Settings_Request::LEAVE ?>"><?php echo Settings_Request::LEAVE ?></option>
                <option value="<?php echo Settings_Request::RESTDAY ?>"><?php echo Settings_Request::RESTDAY ?></option>
                <option value="<?php echo Settings_Request::CHANGED_SCHEDULE ?>"><?php echo Settings_Request::CHANGED_SCHEDULE ?></option>
                <option value="<?php echo Settings_Request::GENERIC ?>"><?php echo Settings_Request::GENERIC ?></option>                
               </select>
            </td>
          </tr>
           <tr>
            <td>
               Apply to Departments
            </td>
             <td>
             <div id="txt_departments">
               <input class="validate[required] text-input text" type="text" name="departments" id="departments" />
             </div>
             <div id="txt_departments_default" style="display:none;">
             	<input type="text" value="Apply to all departments" readonly="readonly" />
             </div>
               <br />
               <input type="checkbox" onclick="javascript:disableTextBox('txt_departments','txt_departments_default',this.id);" name="apply_to_all_departments" id="apply_to_all_departments" />Apply to all
            </td>
          </tr>
          <tr>
            <td>
               Apply to Positions
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
               Apply to Employees
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
          <!--<tr>
            <td>
               Set as active
            </td>
             <td>
                <ul class="is_featured">
                    <li>
                        <input type="radio" name="is_active" id="is_active_yes" value="<?php //echo Settings_Request::YES; ?>" /><?php //echo Settings_Request::YES; ?>
                    </li>
                    <li>
                        <input type="radio" name="is_active" id="is_active_no" checked="checked" value="<?php //echo Settings_Request::NO; ?>" /><?php //echo Settings_Request::NO; ?>
                    </li>
                    <div class="clear"></div>
                </ul>   
            </td>
          </tr>          -->
        </table>        
	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>