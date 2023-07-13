<form id="edit_ip_address_form" method="post" action="<?php echo url('settings/save_ip_address'); ?>">
	<input type="hidden" id="token" name="token" value="<?php echo $token; ?>">
	<input type="hidden" id="eid" name="eid" value="<?php echo Utilities::encrypt($ai->getId());?>" >
	<div id="form_main" class="inner_form popup_form wider">
	    <div id="form_default">
		    <table class="no_border" width="100%">
		    	<tbody>
			    	<tr>
			        	<td width="24%" class="field_label">Employee Name</td>
			            <td width="76%"><input class="" readonly="readonly" type="text" name="employee_name" id="employee_name" value="<?php echo $employee_name;?>"></td>
			        </tr>      
			        <tr>
						<td align="right"  class="field_label">IP Address</td>
						<td><input class="validate[required]" type="text" name="ip_address" id="ip_address" value="<?php echo $ai->getIpAddress(); ?>"></td>
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