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
	var dep = new $.TextboxList('#departments', {unique: true,plugins: {
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
		
		<?php 
			//Employees		
			if($employees) { 
				$emp_ids = explode(",",$employees);
				foreach($emp_ids as $e){
					$emp = G_Employee_Finder::findById($e);
					if($emp){
				
		 ?>
						emp.add('Entry','<?php echo Utilities::encrypt($emp->getId()); ?>', '<?php echo $emp->getFirstname(). ' '. $emp->getLastname(); ?>');
		 <?	
					}
				}
			}
		 ?>
		 
		 <?php 
			//Positions		
			if($positions) { 
				$pos_ids = explode(",",$positions);
				foreach($pos_ids as $p){
					$pos = G_Job_Finder::findById($p);
					if($pos){
				
		 ?>
						pos.add('Entry','<?php echo Utilities::encrypt($pos->getId()); ?>', '<?php echo $pos->getTitle(); ?>');
		 <?	
					}
				}
			}
		 ?>
		 
		  <?php 
			//Departments		
			if($departments) { 
				$dep_ids = explode(",",$departments);
				foreach($dep_ids as $d){
					$dep = G_Company_Structure_Finder::findById($d);
					if($dep){
				
		 ?>
						dep.add('Entry','<?php echo Utilities::encrypt($dep->getId()); ?>', '<?php echo $dep->getTitle(); ?>');
		 <?	
					}
				}
			}
		 ?>

});
</script>
<form method="post" id="copy_request_settings" name="copy_request_settings" action="<?php echo url($action);?>">
<input type="hidden" id="org_request_id" name="org_request_id" value="<?php echo $gsr->getId(); ?>" />
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%">
          <tr>
            <td>
               Title
            </td>
             <td>
               <input class="validate[required] text-input text" type="text" value="" name="title" id="title" />
            </td>
          </tr>
          <tr>
            <td>
               Type
            </td>
             <td>
               <select id="type" name="type">
               	<option <?php echo($gsr->getType() == Settings_Request::OT ? 'selected="selected"' : ''); ?> value="<?php echo Settings_Request::OT ?>"><?php echo Settings_Request::OT ?></option>
                <option <?php echo($gsr->getType() == Settings_Request::LEAVE ? 'selected="selected"' : ''); ?> value="<?php echo Settings_Request::LEAVE ?>"><?php echo Settings_Request::LEAVE ?></option>
                <option <?php echo($gsr->getType() == Settings_Request::RESTDAY ? 'selected="selected"' : ''); ?> value="<?php echo Settings_Request::RESTDAY ?>"><?php echo Settings_Request::RESTDAY ?></option>
                <option <?php echo($gsr->getType() == Settings_Request::CHANGED_SCHEDULE ? 'selected="selected"' : ''); ?> value="<?php echo Settings_Request::CHANGED_SCHEDULE ?>"><?php echo Settings_Request::CHANGED_SCHEDULE ?></option>
                <option <?php echo($gsr->getType() == Settings_Request::GENERIC ? 'selected="selected"' : ''); ?> value="<?php echo Settings_Request::GENERIC ?>"><?php echo Settings_Request::GENERIC ?></option>                
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
               <input type="checkbox" <?php echo($departments == Settings_Request::APPLY_TO_ALL ? 'checked="checked"' : ''); ?> onclick="javascript:disableTextBox('txt_departments','txt_departments_default',this.id);" name="apply_to_all_departments" id="apply_to_all_departments" />Apply to all
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
               <input type="checkbox" <?php echo($positions == Settings_Request::APPLY_TO_ALL ? 'checked="checked"' : ''); ?> onclick="javascript:disableTextBox('txt_positions','txt_positions_default',this.id);" name="apply_to_all_positions" id="apply_to_all_positions" />Apply to all
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
               <input type="checkbox" <?php echo($employees == Settings_Request::APPLY_TO_ALL ? 'checked="checked"' : ''); ?> onclick="javascript:disableTextBox('txt_employees','txt_employees_default',this.id);" name="apply_to_all_employees" id="apply_to_all_employees" />Apply to all
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
           <tr>
            <td>
               Copy Approvers Settings
            </td>
             <td>             
               <input type="checkbox" name="copy_approvers_settings" id="copy_approvers_settings" />Copy Approvers Settings
            </td>
          </tr>
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
<script>
disableTextBox('txt_positions','txt_positions_default','apply_to_all_positions');
disableTextBox('txt_departments','txt_departments_default','apply_to_all_departments');
disableTextBox('txt_employees','txt_employees_default','apply_to_all_employees');
</script>