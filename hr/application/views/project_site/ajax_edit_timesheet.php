<?php
list($night_shift_hours_hh, $night_shift_hours_mm) = explode(':', Tools::convertHourToTime($timesheet->getNightshiftHours()));
list($overtime_hours_hh, $overtime_hours_mm) = explode(':', Tools::convertHourToTime($timesheet->getOvertimeHours()));
list($late_hours_hh, $late_hours_mm) = explode(':', Tools::convertHourToTime($timesheet->getLateHours()));
list($undertime_hours_hh, $undertime_hours_mm) = explode(':', Tools::convertHourToTime($timesheet->getUndertimeHours()));
list($night_shift_special_hh, $night_shift_special_mm) = explode(':', Tools::convertHourToTime($timesheet->getNightShiftHoursSpecial()));
list($night_shift_legal_hh, $night_shift_legal_mm) = explode(':', Tools::convertHourToTime($timesheet->getNightShiftHoursLegal()));
list($holiday_special_hh, $holiday_special_mm) = explode(':', Tools::convertHourToTime($timesheet->getHolidayHoursSpecial()));
list($holiday_legal_hh, $holiday_legal_mm) = explode(':', Tools::convertHourToTime($timesheet->getHolidayHoursLegal()));


?>

<form method="post" id="edit_timesheet" action="<?php echo $action;?>">
<input type="hidden" name="date" value="<?php echo $date;?>" />
<input type="hidden" name="employee_id" value="<?php echo $employee_id;?>" />
<div id="form_main" class="inner_form popup_form wider3">
	<div id="form_default">
    <table width="100%">
      <tr>
        <td><strong>Overtime</strong></td>
        <td align="center">hh</td>
        <td align="center">mm</td>
      </tr>
      <tr>
        <td class="field_label">Overtime Hours: </td>
        <td><input name="overtime_hours[hh]" type="text" class="text text-hh input-mini" id="name3" value="<?php echo $overtime_hours_hh;?>" size="3" maxlength="2" /></td>
        <td><input name="overtime_hours[mm]" type="text" class="text text-mm input-mini" id="name32" value="<?php echo $overtime_hours_mm;?>" size="3" maxlength="2" /></td>
      </tr>
    </table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
    <table>
      <tr>
        <td colspan="3"><strong>Delinquency Hours</strong></td>
      </tr>
      <tr>
        <td class="field_label">Late Hours </td>
        <td><input name="late_hours[hh]" type="text" class="text text-hh input-mini" id="name4" value="<?php echo $late_hours_hh;?>" size="3" maxlength="2" /></td>
        <td><input name="late_hours[mm]" type="text" class="text text-mm input-mini" id="name5" value="<?php echo $late_hours_mm;?>" size="3" maxlength="2" /></td>
      </tr>
      <tr>
        <td class="field_label">Undertime Hours: </td>
        <td><input name="undertime_hours[hh]" type="text" class="text text-hh input-mini" id="name" value="<?php echo $undertime_hours_hh;?>" size="3" maxlength="2" /></td>
        <td><input name="undertime_hours[mm]" type="text" class="text text-mm input-mini" id="name3" value="<?php echo $undertime_hours_mm;?>" size="3" maxlength="2" /></td>
      </tr>
    </table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
    <table>
      <tr>
        <td colspan="3"><strong style="padding:5px 0 4px 0; display:block;">Night Shift Hours</strong></td>
      </tr>
      
      <tr>
        <td class="field_label">Regular:</td>
        <td><input name="night_hours[hh]" type="text" class="text text-hh input-mini" id="name2" value="<?php echo $night_shift_hours_hh;?>" size="3" maxlength="2" /></td>
        <td><input name="night_hours[mm]" type="text" class="text text-mm input-mini" id="night_hours[mm]" value="<?php echo $night_shift_hours_mm;?>" size="3" maxlength="2" /></td>
      </tr>
      <tr>
        <td class="field_label">Special:</td>
        <td><input name="special_night_hours[hh]" type="text" class="text text-hh input-mini" id="name2" value="<?php echo $night_shift_special_hh;?>" size="3" maxlength="2" /></td>
        <td><input name="special_night_hours[mm]" type="text" class="text text-mm input-mini" id="night_hours[mm]" value="<?php echo $night_shift_special_mm;?>" size="3" maxlength="2" /></td>
      </tr>
      <tr>
        <td class="field_label">Legal:</td>
        <td><input name="legal_night_hours[hh]" type="text" class="text text-hh input-mini" id="name2" value="<?php echo $night_shift_legal_hh;?>" size="3" maxlength="2" /></td>
        <td><input name="legal_night_hours[mm]" type="text" class="text text-mm input-mini" id="night_hours[mm]" value="<?php echo $night_shift_legal_mm;?>" size="3" maxlength="2" /></td>
      </tr>
    </table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
    <table>
      <tr>
        <td colspan="3"><strong style="padding:5px 0 4px 0; display:block;">Holiday Hours</strong></td>
      </tr>
      <tr>
        <td class="field_label">Special:</td>
        <td><input name="special_hours[hh]" type="text" class="text text-hh input-mini" id="special_hours[hh]" value="<?php echo $holiday_special_hh;?>" size="3" maxlength="2" /></td>
        <td><input name="special_hours[mm]" type="text" class="text text-mm input-mini" id="special_hours[mm]" value="<?php echo $holiday_special_mm;?>" size="3" maxlength="2" /></td>
      </tr>
      <tr>
        <td class="field_label">Legal:</td>
        <td><input name="legal_hours[hh]" type="text" class="text text-hh input-mini" id="legal_hours[hh]" value="<?php echo $holiday_legal_hh;?>" size="3" maxlength="2" /></td>
        <td><input name="legal_hours[mm]" type="text" class="text text-mm input-mini" id="legal_hours[mm]" value="<?php echo $holiday_legal_mm;?>" size="3" maxlength="2" /></td>
      </tr>
      <!--      <tr>
        <td style="border-top:1px solid #cccccc"><strong>Make-up </strong></td>
        <td style="border-top:1px solid #cccccc">&nbsp;</td>
        <td style="border-top:1px solid #cccccc">&nbsp;</td>
      </tr>
      <tr>
        <td>Make-up Hours </td>
        <td><input name="makeup_hours[hh]" type="text" class="text ui-widget-content ui-corner-all text-hh" id="name3" value="<?php echo $makeup_hours_hh;?>" size="3" maxlength="2" /></td>
        <td><input name="makeup_hours[mm]" type="text" class="text ui-widget-content ui-corner-all text-mm" id="name32" value="<?php echo $makeup_hours_mm;?>" size="3" maxlength="2" /></td>
      </tr>-->
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
    	<table width="100%">
			<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
				<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
                    <input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a>
                <?php } ?>
                </td>
            </tr>
        </table>
    </div>    
</div><!-- #form_main.inner_form popup_form -->
</form>

<script language="javascript">
$("input[type=text]").focus(function() {
	$(this).select();
});
$("input[type=text]").css({color:'#000000'});
//$(".text-hh, .text-mm").numeric(',');

$(".text-hh, .text-mm").keypress(function (e) 
{
  //if the letter is not digit then display error and don't type anything
	  if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	  {
		//display error message
		
		return false;
	 }
});
</script>