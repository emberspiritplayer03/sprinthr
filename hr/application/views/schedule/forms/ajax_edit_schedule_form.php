<form id="edit_schedule_form" method="post" action="<?php echo $action;?>">
<input type="hidden" name="schedule_id" value="<?php echo $schedule_id;?>" />
<div id="form_main" class="inner_form popup_form wider">
    <div id="form_default">
    <table class="no_border" width="100%">
    	<tr>
        	<td class="field_label">*Schedule Name:</td>
            <td><input class="validate[required] text-input" type="text" name="name" id="name" value="<?php echo $schedule->getName();?>" /></td>
        </tr>
        <tr>
        	<td class="field_label">*Working Days:</td>
            <td>
				<?php 
                    $working_days = explode(',', $schedule->getWorkingDays());
                ?>
                <div id="working_days_error" class="red"></div>
                <label>&nbsp;<input class="validate[minCheckbox[1]]" type="checkbox" name="working_day[]" value="sun" id="maxcheck11" <?php echo (in_array('sun', $working_days)) ? 'checked="checked"' : '' ;?> />Sun</label>
                <label>&nbsp;<input class="validate[minCheckbox[1]]" type="checkbox" name="working_day[]" value="mon" id="maxcheck22" <?php echo (in_array('mon', $working_days)) ? 'checked="checked"' : '' ;?> />Mon</label>
                <label>&nbsp;<input class="validate[minCheckbox[1]]" type="checkbox" name="working_day[]" value="tue" id="maxcheck33" <?php echo (in_array('tue', $working_days)) ? 'checked="checked"' : '' ;?> />Tue</label>
                <label>&nbsp;<input class="validate[minCheckbox[1]]" type="checkbox" name="working_day[]" value="wed" id="maxcheck44" <?php echo (in_array('wed', $working_days)) ? 'checked="checked"' : '' ;?> />Wed</label>
                <label>&nbsp;<input class="validate[minCheckbox[1]]" type="checkbox" name="working_day[]" value="thu" id="maxcheck55" <?php echo (in_array('thu', $working_days)) ? 'checked="checked"' : '' ;?> />Thu</label>
                <label>&nbsp;<input class="validate[minCheckbox[1]]" type="checkbox" name="working_day[]" value="fri" id="maxcheck66" <?php echo (in_array('fri', $working_days)) ? 'checked="checked"' : '' ;?> />Fri</label>
                <label>&nbsp;<input class="validate[minCheckbox[1]]" type="checkbox" name="working_day[]" value="sat" id="maxcheck77" <?php echo (in_array('sat', $working_days)) ? 'checked="checked"' : '' ;?> />Sat</label></td>
        </tr>
        <tr>
        	<td class="field_label">Working Hours:</td>
            <td>
            	<table style="width:300px;" border="0" cellpadding="5" cellspacing="0">
                  <tr>
                    <td style="padding-top:0; padding-bottom:0;">&nbsp;</td>
                    <td style="padding-top:0; padding-bottom:0;" align="center"><small><em>Hours</em></small></td>
                    <td style="padding-top:0; padding-bottom:0;">&nbsp;</td>
                    <td style="padding-top:0; padding-bottom:0;" align="center"><small><em>Minutes</em></small></td>
                    <td style="padding-top:0; padding-bottom:0;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="field_label" style="padding-left:0; width:70px;">Start Time:</td>
                    <td width="18"><!--<input name="time_in[hh]" type="text" class="text-hh" id="start_hh" value="<?php echo $time_in_hours;?>" size="3" maxlength="2" />-->
                        <?php
							$time_in = Tools::timeFormat($schedule->getTimeIn());
							list($hour, $temp) = explode(':', $time_in);
							list($minutes, $time_in_am) = explode(' ', $temp);
						?>
						<select name="time_in[hh]" class="select_option_sched">
							<?php for ($i = 1; $i <= 12; $i++):?>
								<option <?php echo ($hour == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
							<?php endfor;?>
						</select>
                    </td>
                    <td width="3">:</td>
                    <td width="18"><!--<input name="time_in[mm]" type="text" class="text-mm" id="start_mm" value="<?php echo $time_in_minutes;?>" size="3" maxlength="2" />-->
                        <select name="time_in[mm]" class="select_option_sched">
							<?php for ($i = 0; $i <= 59; $i+=5):?>
                            <option <?php echo ($minutes == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
                            <?php endfor;?>
                        </select>           
                    </td>
                    <td width="108">
                        <select name="time_in[am]" id="select" class="select_option_sched">
                            <option <?php echo ($time_in_am == 'am') ? 'selected="selected"' : '' ;?> value="am">AM</option>
                            <option <?php echo ($time_in_am == 'pm') ? 'selected="selected"' : '' ;?> value="pm">PM</option>
                        </select>
              		</td>
                  </tr>
                  <tr>
                    <td class="field_label" style="padding-left:0; width:70px;">End Time:</td>
                    <td><!--<input name="time_out[hh]" type="text" class="text-hh" id="end_hh" value="<?php echo $time_out_hours;?>" size="3" maxlength="2" />-->
                        <?php
							$time_out = Tools::timeFormat($schedule->getTimeOut());
							list($hour, $temp) = explode(':', $time_out);
							list($minutes, $time_out_am) = explode(' ', $temp);
						?>            
						<select name="time_out[hh]" class="select_option_sched">
							<?php for ($i = 1; $i <= 12; $i++):?>
								<option <?php echo ($hour == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
							<?php endfor;?>
						</select>            
                    </td>
                    <td>:</td>
                    <td><!--<input name="time_out[mm]" type="text" class="text-mm" id="end_mm" value="<?php echo $time_out_minutes;?>" size="3" maxlength="2" />-->
                        <select name="time_out[mm]" class="select_option_sched">
							<?php for ($i = 0; $i <= 59; $i+=5):?>
                            <option <?php echo ($minutes == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
                            <?php endfor;?>
                        </select>             
                    </td>
                    <td>
                    	<select name="time_out[am]" id="select2" class="select_option_sched">
                          <option <?php echo ($time_out_am == 'am') ? 'selected="selected"' : '' ;?> value="am">AM</option>
                          <option <?php echo ($time_out_am == 'pm') ? 'selected="selected"' : '' ;?> value="pm">PM</option>
                        </select>
                    </td>
                  </tr>
                </table>            	
            </td>
        </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table class="no_border" width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td>
                    <input value="Update" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a>
                </td>
            </tr>
        </table>            
    </div><!-- #form_default.form_action_section -->
</div><!-- #form_main.popup_form -->
</form>