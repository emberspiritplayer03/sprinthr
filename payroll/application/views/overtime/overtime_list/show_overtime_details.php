<script>
$(document).ready(function() {
$("#date_applied").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true
});

$("#start_date_edit").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
});

	$("#overtime_request_edit_form").validationEngine({scroll:false});
	$('#overtime_request_edit_form').ajaxForm({
		dataType:'json',
		success:function(o) {
			if(o==1) {
				dialogOkBox(o.message,{});
				back_to_list();
				//load_overtime_list_dt();
				load_overtime_list_dt_withselectionfilter();
				
			}else {
				dialogOkBox(o.message,{});	
			}		
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});

});


</script>
<div id="request_overtime_form_wrapper" style="display:none;"><?php include_once('form/add_overtime_hideshow.php'); ?></div>
<div id="employee_view">
	<div class="employee_viewholder">
    	<div class="employee_view_photo"><img src="<?php echo $filename; ?>" alt="Employee Photo" ></div>
        	<div class="sectionarea">
                <div id="formwrap" class="employee_form_summary">
                    <div id="leave_available_table_wrapper">
                      <div id="form_main" class="inner_form">
                        <div id="form_default">
                          <h3 class="section_title">Employee Information</h3>
                          <table width="100%">
                            <tr>
                                <td class="field_label">Name:</td>                                
                                <td><strong><?php echo $employee['employee_name']; ?></strong></td>
                                <td class="field_label">Position:</td>
                                <td><?php echo $employee['position']; ?></td>
                              </tr>
                              <tr>
                                <td class="field_label">Employee Code:</td>
                                <td><?php echo $employee['employee_code']; ?></td>
                                <td class="field_label">Employment Status:</td>
                                <td><?php echo $employee['employment_status']; ?></td>
                              </tr>
                              <tr>
                                <td class="field_label">Department:</td>
                                <td><?php echo $employee['department']; ?></td>
                                <td class="field_label">Hired Date:</td>
                                <td><?php echo $employee['hired_date']; ?></td>
                              </tr>
                            </table>                          
                        </div><!-- #form_default -->
                      </div><!-- #form_main.inner-form -->
                    </div><!-- #leave_available_table_wrapper -->
                </div><!-- #formwrap.employee_form_summary -->
            </div><!-- .sectionarea -->
            <form id="overtime_request_edit_form" name="overtime_request_edit_form" method="post" action="<?php echo url('overtime/_load_update_overtime_request'); ?>" >  
            <div id="form_main" class="employee_form">
          	<h3 class="section_title">Form Title</h3>
            <div id="form_default">
            
            <input type="hidden" name="hid" value="<?php echo Utilities::encrypt($overtime->getId()); ?>" />
            <input type="hidden" id="h_employee_id_edit" name="h_employee_id_edit" value="<?php echo Utilities::encrypt($overtime->getEmployeeId()); ?>" />
              <table>
                 <tr>
                   <td class="field_label">Date Applied:</td>
                   <td><input class="validate[required] text-input" type="text" name="date_applied" id="date_applied" value="<?php echo $overtime->getDateApplied();  ?>" readonly="readonly" /></td>
                </tr>
                 <tr>
                   <td class="field_label">Date of Overtime:</td>
                   <td>
                   	<input type="text" onchange="javascript:load_show_specific_schedule_edit();" class="validate[required] text-input" name="start_date_edit" id="start_date_edit" value="<?php echo $overtime->getDateStart();  ?>" readonly="readonly" /><div id="_schedule_loading_wrapper_edit" style="display:inline; margin-left:10px;"></div>
                    <div id="show_specific_schedule_wrapper_edit"></div>
                   </td>
                </tr>
                <tr>
                <td class="field_label">Time :</td>
                <td>
                    <input type="text" style="width:70px;" id="start_time_edit" name="start_time_edit" class="" onchange="" placeholder="Starts on" value="<?php echo Tools::convert12To24Hour($overtime->getTimeIn()); ?>" />
                    <input type="text" style="width:70px;" id="end_time_edit" name="end_time_edit" class="" placeholder="Ends on" value="<?php echo Tools::convert12To24Hour($overtime->getTimeOut()); ?>" />
                </td>
              </tr>
                 <tr>
                   <td class="field_label">Reason:</td>
                   <td><textarea name="reason" id="reason"><?php echo $overtime->getOvertimeComments();  ?></textarea></td>
                </tr>
                 <tr>
                   <td class="field_label">&nbsp;</td>
                   <td>
                   	<select class="select_option" name="status" id="status">
                  
                        <option <?php echo ($overtime->getIsApproved() == G_Employee_Leave_Request::PENDING ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Leave_Request::PENDING; ?>"><?php echo G_Employee_Leave_Request::PENDING; ?></option>
                        <option <?php echo ($overtime->getIsApproved() == G_Employee_Leave_Request::APPROVED ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Leave_Request::APPROVED; ?>"><?php echo G_Employee_Leave_Request::APPROVED; ?></option>
                        <option <?php echo ($overtime->getIsApproved() == G_Employee_Leave_Request::DISAPPROVED ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Leave_Request::DISAPPROVED; ?>"><?php echo G_Employee_Leave_Request::DISAPPROVED; ?></option>
                    
                     </select></td>
                </tr>
              </table>
            </div><!-- #form_default -->
            <div class="form_action_section" id="form_default">
                <table>
                    <tr>
                        <td class="field_label">&nbsp;</td>
                        <td><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:back_to_list(); load_overtime_list_dt();">Cancel</a></td>
                    </tr>
                </table>
            </div><!-- #form_default.form_action_section -->
           
          </div>
          </form>
        <div class="clear"></div>
    </div>
</div><!-- #employee_view -->

<script>
function onStartTimeChanged() {
	var start_time_id = '#start_time_edit';
	var end_time_id = '#end_time_edit';
	var start_time = $('#start_time_edit').val();
	var split_time = start_time.split(':');
	var hour = parseFloat(split_time[0]) + 5;
	var split_minutes = split_time[1].split(' ');
	var minutes = split_minutes[0];
	var am = split_minutes[1];
	if (hour > 12) {
		hour = hour - 12;
	}
	
	if (am == 'pm') {
		am = 'am';	
	} else {
		am = 'pm';	
	}
	$(end_time_id).val(hour + ':' + minutes + ' ' + am);
	$(end_time_id).timepicker({
		'minTime': $(start_time_id).val(),
		'maxTime': $(start_time_id).val(),
		'timeFormat': 'g:i a',
		'showDuration': true
	});
}
	$('#start_time_edit').timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});
	$('#end_time_edit').timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});		
</script>
