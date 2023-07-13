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
	var pos_emp_dept = new $.TextboxList('#pos_emp_dept', {unique: true,plugins: {
			autocomplete: {
				minLength: 1,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'settings/ajax_get_positions_departments_employees_autocomplete'}
			
			}
		}});		
	
});
$(function(){
	$('ul.textboxlist-bits').attr("title","Type position,employee or department name to see suggestions.");
	$('ul.textboxlist-bits').tipsy({gravity: 's'});	
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
                <option value="<?php echo Settings_Request::UNDERTIME ?>"><?php echo Settings_Request::UNDERTIME ?></option>
                <option value="<?php echo Settings_Request::MAKE_UP ?>"><?php echo Settings_Request::MAKE_UP ?></option>
                <option value="<?php echo Settings_Request::OB ?>"><?php echo Settings_Request::OB ?></option>
                <option value="<?php echo Settings_Request::AC; ?>"><?php echo Settings_Request::AC; ?></option>
                <option value="<?php echo Settings_Request::GENERIC ?>"><?php echo Settings_Request::GENERIC ?></option>
               </select>
            </td>
          </tr>
           <tr>
            <td>
               Requestor(s) 
            </td>
             <td>
             <div id="txt_departments">
               <input title="Type position, employee or department namen to see suggestions." class="validate[required] text-input text" type="text" name="pos_emp_dept" id="pos_emp_dept" />
             </div>             
            </td>
          </tr>
          <tr>
          	<td></td>
            <td>
            	<label class="checkbox">            	
               	<input type="checkbox" name="apply_to_all_departments" id="apply_to_all_departments" />Apply to all Departments
                </label>
               
               <label class="checkbox">   
               <input type="checkbox" name="apply_to_all_positions" id="apply_to_all_positions" />Apply to all Positions
               </label>
                
               <label class="checkbox">
               <input type="checkbox" name="apply_to_all_employees" id="apply_to_all_employees" />Apply to all Employees
               </label>
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