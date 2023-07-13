<div id="formcontainer">
<div class="mtshad"></div>
<form id="create_schedule_form" method="post" action="<?php echo $action;?>">
<div id="formwrap">	
	<h3 class="form_sectiontitle">Create Schedule</h3>
    <div id="form_main">
<div id="form_default">      
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="no_border">
    <tr>
        <td align="left" valign="top" class="field_label">*Schedule Name:</td>
        <td align="left" valign="top"><input class="text-input" type="text" name="schedule_name" id="schedule_name" value="Morning Shift" /></td>
    </tr>
    <tr>
        <td align="left" valign="top" class="field_label">*Working Days:</td>
        <td align="left" valign="top">
        	<label>&nbsp;&nbsp;<input type="checkbox" name="working_days[]" value="sun" id="maxcheck1" />Sun</label>
            <label>&nbsp;&nbsp;<input type="checkbox" name="working_days[]" value="mon" id="maxcheck2" checked="checked" />Mon</label>
            <label>&nbsp;&nbsp;<input type="checkbox" name="working_days[]" value="tue" id="maxcheck3" checked="checked" />Tue</label>
            <label>&nbsp;&nbsp;<input type="checkbox" name="working_days[]" value="wed" id="maxcheck4" checked="checked" />Wed</label>
            <label>&nbsp;&nbsp;<input type="checkbox" name="working_days[]" value="thu" id="maxcheck5" checked="checked" />Thu</label>
            <label>&nbsp;&nbsp;<input type="checkbox" name="working_days[]" value="fri" id="maxcheck6" checked="checked" />Fri</label>
            <label>&nbsp;&nbsp;<input type="checkbox" name="working_days[]" value="sat" id="maxcheck7" />Sat</label>
        </td>
    </tr>
    <tr>
        <td align="left" valign="top" class="field_label">Working Hours:</td>
        <td align="left" valign="top">
            <table class="no_border" style="width:330px;" border="0" cellpadding="5" cellspacing="0">
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
                    <select name="time_in[hh]" class="select_option_sched">
                        <?php for ($i = 1; $i <= 12; $i++):?>
                        <?php if ($i == 8):?>
                            <option selected="selected"><?php echo $i;?></option>
                        <?php else:?>
                            <option><?php echo $i;?></option>
                        <?php endif;?>
                        <?php endfor;?>
                    </select>
                </td>
                <td width="3">:</td>
                <td width="18"><!--<input name="time_in[mm]" type="text" class="text-mm" id="start_mm" value="<?php echo $time_in_minutes;?>" size="3" maxlength="2" />-->
                    <select name="time_in[mm]" class="select_option_sched">
                        <?php for ($i = 0; $i <= 59; $i+=5):?>
                        <option><?php echo $i;?></option>
                        <?php endfor;?>
                    </select>            
                </td>
                <td width="108"><select name="time_in[am]" id="select" class="select_option_sched">
                  <option <?php echo ($time_in_am == 'AM') ? 'selected="selected"' : '' ;?> value="am">AM</option>
                  <option <?php echo ($time_in_am == 'PM') ? 'selected="selected"' : '' ;?> value="pm">PM</option>
                  </select></td>
              </tr>
              <tr>
                <td class="field_label" style="padding-left:0; width:70px;">End Time:</td>
                <td><!--<input name="time_out[hh]" type="text" class="text-hh" id="end_hh" value="<?php echo $time_out_hours;?>" size="3" maxlength="2" />-->
                    <select name="time_out[hh]" class="select_option_sched">
                        <?php for ($i = 1; $i <= 12; $i++):?>
                        <?php if ($i == 5):?>
                            <option selected="selected"><?php echo $i;?></option>
                        <?php else:?>
                            <option><?php echo $i;?></option>
                        <?php endif;?>
                        <?php endfor;?>
                    </select>            
                </td>
                <td>:</td>
                <td><!--<input name="time_out[mm]" type="text" class="text-mm" id="end_mm" value="<?php echo $time_out_minutes;?>" size="3" maxlength="2" />-->
                    <select name="time_out[mm]" class="select_option_sched">
                        <?php for ($i = 0; $i <= 59; $i+=5):?>
                        <option><?php echo $i;?></option>
                        <?php endfor;?>
                    </select>              
                </td>
                <td><select name="time_out[am]" id="select2" class="select_option_sched">
                  <option <?php echo ($time_out_am == 'AM') ? 'selected="selected"' : '' ;?> value="am">AM</option>
                  <option selected="selected" <?php echo ($time_out_am == 'PM') ? 'selected="selected"' : '' ;?> value="pm">PM</option>
                </select></td>
              </tr>
            </table>
        </td>
    </tr>    
</table>
</div>         
<div id="form_default" class="form_action_section">
    <table class="no_border" width="100%">
        <tr>
            <td class="field_label">&nbsp;</td>
            <td>
                <input value="Create Schedule" id="create_schedule_submit" class="curve blue_button" type="submit">
                <a href="javascript:void(0);" onclick="cancelCreateSchedule()">Cancel</a>
            </td>
        </tr>
    </table>            
</div>
</div><!-- #form_main -->
</div><!-- #formwrap -->
</form>

</div>