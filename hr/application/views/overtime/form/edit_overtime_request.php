<div id="form_main" class="inner_form popup_form wider">
<form id="edit_request_overtime_form" name="edit_request_overtime_form" autocomplete="off" method="POST" action="<?php echo url('overtime/_load_update_overtime_request'); ?>">
<input type="hidden" id="hid" name="hid" value="<?php echo Utilities::encrypt($overtime_request->getId()); ?>" />
<input type="hidden" id="h_employee_id" name="h_employee_id" value="<?php echo Utilities::encrypt($overtime_request->getEmployeeId()); ?>" />

    <div id="form_default">      
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td class="field_label">Employee Name :</td>
            <td>
               	<input type="text" id="employee_name" name="employee_name" readonly="readonly" value="<?php echo $employee->getFullName(); ?>" />
            </td>
          </tr>
          <tr>
            <td class="field_label">Date:</td>
            <td>
                <input type="text" id="start_date_hideshow" name="start_date_edit" class="validate[required]" placeholder="From" value="<?php echo $overtime_request->getDateStart(); ?>" readonly="readonly" />
                <div id="_schedule_loading_wrapper" style="display:inline; margin-left:10px;"></div>
                <div id="show_specific_schedule_wrapper"></div>
                <!--<input type="text" style="width:150px;" id="end_date_edit" name="end_date_edit" class="validate[required]" placeholder="To" value="<?php echo $overtime_request->getDateEnd(); ?>" readonly="readonly" />-->
            </td>
          </tr>
          <tr>
            <td class="field_label">Time :</td>
            <td>
                <input type="text" style="width:70px;" id="start_time" name="start_time_edit" class="" placeholder="Starts on" value="<?php echo Tools::convert24To12Hour($overtime_request->getTimeIn()); ?>" />
                <input type="text" style="width:70px;" id="end_time" name="end_time_edit" class="" placeholder="Ends on" value="<?php echo Tools::convert24To12Hour($overtime_request->getTimeOut()); ?>" />
            </td>
          </tr>
          <tr>
            <td class="field_label">Reason:</td>
            <td><textarea id="reason" name="reason" style="height:75px; width:250px"><?php echo $overtime_request->getOvertimeComments(); ?></textarea></td>
          </tr>
          <tr>
            <td class="field_label">Status:</td>
            <td>
            	<select id="status" name="status" class="select_option_sched">
                	<option <?php echo ($overtime_request->getIsApproved() == G_Employee_Overtime_Request::PENDING ? 'selected="selected"' : ''); ?> value="Pending">Pending</option>
                    <option <?php echo ($overtime_request->getIsApproved() == G_Employee_Overtime_Request::APPROVED ? 'selected="selected"' : ''); ?> value="Approved">Approved</option>
                    <option <?php echo ($overtime_request->getIsApproved() == G_Employee_Overtime_Request::DISAPPROVED ? 'selected="selected"' : ''); ?> value="Disapproved">Disapproved</option>
                </select>
            </td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_request_overtime_form');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</form>
</div><!-- #form_main -->