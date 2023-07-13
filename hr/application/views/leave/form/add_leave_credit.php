<div id="form_main" class="inner_form popup_form wider">
<form id="request_leave_form" name="request_leave_form"  action="<?php echo url('leave/_insert_employee_leave_credit'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" name="employee_id" id="employee_id" value="<?php echo Utilities::encrypt($e->getId()); ?>" />  
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Employee</td>
               <td><input class="validate[required] text-input" type="text" name="employee_name" id="employee_name" value="<?php echo $e->getFullname(); ?>" /></td>
             </tr>
             <tr>
              <td class="field_label">Leave Type:</td>
              <td>
              <select class="validate[required] select_option_sched" name="leave_id" id="leave_id" onchange="load_get_employee_leave_credits(this.value,'<?php echo Utilities::encrypt($e->getId()); ?>')">              
                <option value="">-- select --</option>
					<?php foreach($leaves as $l) { ?>
	                    <option value="<?php echo Utilities::encrypt($l->getId()); ?>"><?php echo $l->getName(); ?></option>
                    <?php } ?>
               </select>
              </td>
            </tr>
            <tr>
              <td class="field_label">Leave Allotted:</td>
              <td>
                 <input class="validate[required] text-input" type="text" name="leave_alloted" id="leave_alloted" value="" readonly="readonly" />         
              </td>
            </tr>            
            <tr>
              <td class="field_label">Leave Available:</td>
              <td>
                 <input class="validate[required] text-input" type="text" name="leave_available" id="leave_available" value="" readonly="readonly" />         
              </td>
            </tr>                
            <tr>
              <td class="field_label">Number to add</td>
              <td>
                 <input class="validate[required,custom[integer],min[1]]" type="text" name="leave_credit" id="leave_credit" value="" /><br />
                 <i><small>Will load set default leave credit(s).</small></i>
              </td>
            </tr>  
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#request_leave_form');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</form>

