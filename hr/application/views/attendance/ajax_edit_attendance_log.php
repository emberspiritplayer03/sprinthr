<script>
$(document).ready(function() {
	/*$("#a_date").datepicker({
		dateFormat:'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			showOtherMonths:true			
	});*/

    $('#a_time').timepicker({
        'minTime': '8:00 am',
        'maxTime': '7:30 am',
        'timeFormat': 'g:i a'
    });
});

</script>
<form method="post" id="edit_attendance_log" name="edit_attendance_log" action="<?php echo url("attendance/_update_attendance_log"); ?>">
<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
<input type="hidden" name="eid" id="eid" value="<?php echo Utilities::encrypt($at->getId()); ?>" />
<input type="hidden" name="c_type" id="c_type" value="<?php echo Utilities::encrypt($at->getType()); ?>" />
<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table>          
          <tr>
            <td class="field_label">Date:</td>
            <td>
            	<input readonly type="text" class="validate[required] " name="a_date" id="a_date" value="<?php echo Tools::convertDateFormat($at->getDate()); ?>" />
            </td>            
          </tr>
          <tr>
            <td class="field_label">Time:</td>
            <td>
            	<input type="text" class="validate[required] " name="a_time" id="a_time" value="<?php echo $at->getTime(); ?>" />
            </td>            
          </tr>
          <tr>
            <td class="field_label">Type:</td>
            <td>
            	<select name="a_type" id="a_type">
                <?php foreach($log_types as $key=>$log_type) { ?>
                    <option value="<?php echo $log_type; ?>" <?php echo(strtoupper($at->getType()) == strtoupper($log_type) ? 'selected="selected"' : ""); ?>><?php echo strtoupper($log_type); ?></option>
                <?php } ?>
            	</select>
            </td>    
            <tr>
                <td class="field_label">Device Number</td>
                <td>
                    <select readonly name="remarks" id="remarks" >
                        <option value="" selected="selected">-- Select Device Number --</option>
                        <option value="Device No:--no device--" <?php echo(strtoupper('Device No:--no device--') == strtoupper($device->machine_no) ? 'selected="selected"' : ""); ?>>--no device--</option>
                        <?php foreach($devices as $key => $device) { ?>
                            <option value="<?php echo 'Device No:'.$device->machine_no; ?>" <?php echo(strtoupper((isset($remarks) && !empty($remarks) ? explode(':', str_replace(' ', '_', $remarks))[1] : null)) == strtoupper($device->machine_no) ? 'selected="selected"' : ""); ?>><?php  echo $device->machine_no .' - '. $device->device_name ?></option>
                        <?php } ?>
                    </select>
                </td>            
            </tr>         
          </tr>
        </table>
        <br />
        
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
		<table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
            </tr>
		</table>
	</div><!-- #form_default -->
</div><!-- #form_main.inner_form -->        
</form>
