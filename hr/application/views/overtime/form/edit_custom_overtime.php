<form id="edit_custom_overtime_form" method="post" action="<?php echo url('overtime/_update_custom_overtime'); ?>">
	<input type="hidden" id="token" name="token" value="<?php echo $token; ?>">
	<input type="hidden" id="eid" name="eid" value="<?php echo Utilities::encrypt($co->getId());?>" >
	<div id="form_main" class="inner_form popup_form wider">
	    <div id="form_default">
		    <table class="no_border" width="100%">
		    	<tbody>
			    	<tr>
			        	<td width="24%" class="field_label">Employee Name</td>
			            <td width="76%"><input class="" readonly="readonly" type="text" name="employee_name" id="employee_name" value="<?php echo $employee_name;?>"></td>
			        </tr>      
			        <tr>
						<td align="right"  class="field_label">Date</td>
						<td><input type="text" readonly="readonly" value="<?php echo $co->getDate(); ?>"></td>
			        </tr>
			        <tr>
						<td align="right"  class="field_label">Day Type</td>
						<td><input type="text" readonly="readonly" value="<?php echo $co->getDayType(); ?>"></td>
			        </tr>			        
			        <tr>
			            <td class="field_label">Time</td>
			            <td>
			                <input type="text" style="width:70px;" id="custom_overtime_start_time" name="custom_overtime_start_time" class="" placeholder="Starts on" value="<?php echo Tools::convert24To12Hour($co->getStartTime()); ?>" />
			                <input type="text" style="width:70px;" id="custom_overtime_end_time" name="custom_overtime_end_time" class="" placeholder="Ends on" value="<?php echo Tools::convert24To12Hour($co->getEndTime()); ?>" />
			            </td>
			        </tr> 
		        </tbody>
		    </table>
	    </div>

	    <span id="schedule_message"></span>
	    <div id="form_default" class="form_action_section">
	        <table class="no_border" width="100%">
	            <tbody>
		            <tr>
		                <td class="field_label">&nbsp;</td>
		                <td>
		                    <input value="Save" id="add_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a>
		                </td>
		            </tr>
	        	</tbody>
	        </table>            
	    </div>
	</div>
</form>