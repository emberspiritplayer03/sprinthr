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
		
		<?php
			//Employees		
			if($employees) {
				$emp_ids = explode(",",$employees);			
				foreach($emp_ids as $e){
					$emp = G_Employee_Finder::findById($e);
					if($emp){
		?>
			pos_emp_dept.add('Entry','<?php echo 'emp-' . Utilities::encrypt($emp->getId()); ?>', '<?php echo $emp->getFirstname(). ' '. $emp->getLastname(); ?>');
		<?php
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
			pos_emp_dept.add('Entry','<?php echo 'pos-' . Utilities::encrypt($pos->getId()); ?>', '<?php echo $pos->getTitle(); ?>');
		<?php
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
		  	pos_emp_dept.add('Entry','<?php echo 'dept-' . Utilities::encrypt($dep->getId()); ?>', '<?php echo $dep->getTitle(); ?>');
		  <?php
						
					}		
				}				
			}				
		 ?>
		 
	$('ul.textboxlist-bits').attr("title","Type position,employee or department name to see suggestions.");
	$('ul.textboxlist-bits').tipsy({gravity: 's'});	

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
               Requestor(s) 
            </td>
             <td>
             <div id="txt_departments">
               <input class="validate[required] text-input text" type="text" name="pos_emp_dept" id="pos_emp_dept" />
             </div>             
            </td>
          </tr>
          <tr>
          	<td></td>
            <td>
               <label class="checkbox">
               		<input type="checkbox" <?php echo($departments == Settings_Request::APPLY_TO_ALL ? 'checked="checked"' : ''); ?> name="apply_to_all_departments" id="apply_to_all_departments" />Apply to all Departments
               </label>
                
               <label class="checkbox">
               		<input type="checkbox" <?php echo($positions == Settings_Request::APPLY_TO_ALL ? 'checked="checked"' : ''); ?> name="apply_to_all_positions" id="apply_to_all_positions" />Apply to all Positions
               </label>
              
               <label class="checkbox">
               		<input type="checkbox" <?php echo($employees == Settings_Request::APPLY_TO_ALL ? 'checked="checked"' : ''); ?> name="apply_to_all_employees" id="apply_to_all_employees" />Apply to all Employees
               </label>
               
               <label class="checkbox">
               		<input type="checkbox" name="copy_approvers_settings" id="copy_approvers_settings" />Copy Approvers Settings
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
