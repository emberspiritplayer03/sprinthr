<form method="post" id="edit_time_in_out" action="<?php echo $action;?>">
<input type="hidden" name="date" value="<?php echo $date;?>" />
<input type="hidden" name="employee_id" value="<?php echo $employee_id;?>" />
<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table>
            <tr>
              <td colspan="5"><strong>Schedule</strong></td>
            </tr>
            <tr>
                <td class="field_label">Start Time:</td>
                <td>
                  <select name="scheduled_time_in[hh]" class="select_option_sched">
                    <?php for ($i = 1; $i <= 12; $i++):?>
                    <option <?php echo ($scheduled_time_in_hh == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
                    <?php endfor;?>
                  </select></td>
                <td>:</td>
                <td>
                  <select name="scheduled_time_in[mm]" class="select_option_sched">
                    <?php for ($i = 0; $i <= 59; $i++):?>
                    <option <?php echo ($scheduled_time_in_mm == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
                    <?php endfor;?>
                  </select></td>
                <td><select name="scheduled_time_in[am]" id="select" class="select_option_sched">
                  <option <?php echo ($scheduled_time_in_am == 'am') ? 'selected="selected"' : '' ;?> value="am">AM</option>
                  <option <?php echo ($scheduled_time_in_am == 'pm') ? 'selected="selected"' : '' ;?> value="pm">PM</option>
                </select></td>
              </tr>
              <tr>
                <td class="field_label">End Time:</td>
                <td>
                  <select name="scheduled_time_out[hh]" class="select_option_sched">
                    <?php for ($i = 1; $i <= 12; $i++):?>
                    <option <?php echo ($scheduled_time_out_hh == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
                    <?php endfor;?>
                  </select></td>
                <td>:</td>
                <td><!--<input name="time_out[mm]" type="text" class="text-mm" id="end_mm" value="<?php echo $time_out_minutes;?>" size="3" maxlength="2" />-->
                  <select name="scheduled_time_out[mm]" class="select_option_sched">
                    <?php for ($i = 0; $i <= 59; $i++):?>
                    <option <?php echo ($scheduled_time_out_mm == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
                    <?php endfor;?>
                  </select></td>
                <td><select name="scheduled_time_out[am]" id="select2" class="select_option_sched">
                  <option <?php echo ($scheduled_time_out_am == 'am') ? 'selected="selected"' : '' ;?> value="am">AM</option>
                  <option <?php echo ($scheduled_time_out_am == 'pm') ? 'selected="selected"' : '' ;?> value="pm">PM</option>
                </select></td>
              </tr>
          </table>
     </div>
     <div class="form_separator"></div>
     <div id="form_default">
        <table border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td colspan="5"><strong>Actual</strong></td>
          </tr>
          <tr>
            <td class="field_label">Start Time:</td>
            <td><select name="actual_time_in[hh]" class="select_option_sched">
              <?php for ($i = 1; $i <= 12; $i++):?>
              <option <?php echo ($actual_time_in_hh == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
              <?php endfor;?>
              </select></td>
            <td>:</td>
            <td><select name="actual_time_in[mm]" class="select_option_sched">
              <?php for ($i = 0; $i <= 59; $i++):?>
              <option <?php echo ($actual_time_in_mm == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
              <?php endfor;?>
              </select></td>
            <td><select name="actual_time_in[am]" id="actual_time_in[am]" class="select_option_sched">
              <option <?php echo ($actual_time_in_am == 'am') ? 'selected="selected"' : '' ;?> value="am">AM</option>
              <option <?php echo ($actual_time_in_am == 'pm') ? 'selected="selected"' : '' ;?> value="pm">PM</option>
              </select></td>
          </tr>
          <tr>
            <td class="field_label">End Time:</td>
            <td><select name="actual_time_out[hh]" class="select_option_sched">
              <?php for ($i = 1; $i <= 12; $i++):?>
              <option <?php echo ($actual_time_out_hh == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
              <?php endfor;?>
            </select></td>
            <td>:</td>
            <td><!--<input name="time_out[mm]" type="text" class="text-mm" id="end_mm" value="<?php echo $time_out_minutes;?>" size="3" maxlength="2" />-->
              <select name="actual_time_out[mm]" class="select_option_sched">
                <?php for ($i = 0; $i <= 59; $i++):?>
                <option <?php echo ($actual_time_out_mm == $i) ? 'selected="selected"' : '' ;?>><?php echo $i;?></option>
                <?php endfor;?>
              </select></td>
            <td><select name="actual_time_out[am]" id="actual_time_out[am]" class="select_option_sched">
              <option <?php echo ($actual_time_out_am == 'am') ? 'selected="selected"' : '' ;?> value="am">AM</option>
              <option <?php echo ($actual_time_out_am == 'pm') ? 'selected="selected"' : '' ;?> value="pm">PM</option>
            </select></td>
          </tr>
        </table>
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