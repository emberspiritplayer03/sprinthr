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
<div id="form_main" class="inner_form popup_form details_form">
    <div id="form_default">
        <table width="100%">
          <tr>
            <td><strong>Overtime</strong></td>
            <td align="center">hh:mm</td>
          </tr>
          <tr>
            <td class="field_label">Overtime Hours: </td>
            <td align="center"><?php echo $overtime_hours_hh;?>:<?php echo $overtime_hours_mm;?></td>
          </tr>
          <tr>
            <td style="border-top:1px solid #cccccc"><strong style="padding:5px 0 0; display:block;">Delinquency Hours</strong></td>
            <td align="center" style="border-top:1px solid #cccccc">&nbsp;</td>
          </tr>
          <tr>
            <td class="field_label">Late Hours: </td>
            <td align="center"><?php echo $late_hours_hh;?>:<?php echo $late_hours_mm;?></td>
          </tr>
          <tr>
            <td class="field_label">Undertime Hours: </td>
            <td align="center"><?php echo $undertime_hours_hh;?>:<?php echo $undertime_hours_mm;?></td>
          </tr>
          <tr>
            <td width="155" style="border-top:1px solid #cccccc"><strong style="padding:5px 0 0; display:block;">Night Shift Hours</strong></td>
            <td width="18" align="center" style="border-top:1px solid #cccccc">&nbsp;</td>
          </tr>
          
          <tr>
            <td class="field_label">Regular:</td>
            <td align="center"><?php echo $night_shift_hours_hh;?>:<?php echo $night_shift_hours_mm;?></td>
          </tr>
          <tr>
            <td class="field_label">Special:</td>
            <td align="center"><?php echo $night_shift_special_hh;?>:<?php echo $night_shift_special_mm;?></td>
          </tr>
          <tr>
            <td class="field_label">Legal:</td>
            <td align="center"><?php echo $night_shift_legal_hh;?>:<?php echo $night_shift_legal_mm;?></td>
          </tr>
          <tr>
            <td style="border-top:1px solid #cccccc"><strong style="padding:5px 0 0; display:block;">Holiday Hours</strong></td>
            <td align="center" style="border-top:1px solid #cccccc"></td>
          </tr>
          <tr>
            <td class="field_label">Special:</td>
            <td align="center"><?php echo $holiday_special_hh;?>:<?php echo $holiday_special_mm;?></td>
          </tr>
          <tr>
            <td class="field_label">Legal:</td>
            <td align="center"><?php echo $holiday_legal_hh;?>:<?php echo $holiday_legal_mm;?></td>
          </tr>
        </table>      
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><a style="color:white; text-decoration:none" class="curve blue_button" href="javascript:void(0);" onclick="closeTheDialog()">Close</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->  


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