<script>
$(function(){
  $('.time_in').timepicker({
        'minTime': '8:00 am',
        'maxTime': '7:30 am',
        'timeFormat': 'g:i a',           
  });

  $('.time_out').timepicker({
        'minTime': '8:00 am',
        'maxTime': '7:30 am',
        'timeFormat': 'g:i a',           
  });
});
</script>

<div id="form_main" class="inner_form popup_form wider">
  <form name="editTimesheetInOut" id="editTimesheetInOut" method="post" action="<?php echo url('attendance/_update_timesheet_inout'); ?>">    
  <input type="hidden" name="eid" value="<?php echo $eid; ?>">
    <div id="form_default">
      <h3>Attendance Log : <b><?php echo $log_date; ?></b></h3>
      <table width="100%"> 
           <?php $log_counter = 1; foreach( $logs_in as $log){ ?>
            <tr>
                <td class="field_label">Log <?php echo $log_counter; ?></td>
                <td class="form-inline">: 
                  <input type="hidden" name="in[<?php echo $log['id']; ?>][date]" style="width:36%;" value="<?php echo $log['date']; ?>">
                  <input type="text" class="time_in" name="in[<?php echo $log['id']; ?>][time]" style="width:36%;" value="<?php echo $log['time']; ?>">
                  <select style="width:36%;height:27px;" name="in[<?php echo $log['id']; ?>][type]">
                    <option selected="selected" value="In">In</option>
                    <option value="Out">Out</option>
                  </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr /></td></tr>
           <?php $log_counter++; } ?>

           <?php foreach( $logs_out as $log){ ?>
            <tr>
                <td class="field_label">Log <?php echo $log_counter; ?></td>
                <td class="form-inline">:
                  <input type="hidden" name="out[<?php echo $log['id']; ?>][date]" style="width:36%;" value="<?php echo $log['date']; ?>"> 
                  <input type="text" class="time_out" name="out[<?php echo $log['id']; ?>][time]" style="width:36%;" value="<?php echo $log['time']; ?>">
                  <select style="width:36%;height:27px;" name="out[<?php echo $log['id']; ?>][type]">
                    <option value="In">In</option>
                    <option selected="selected"  value="Out">Out</option>
                  </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr /></td></tr>
           <?php $log_counter++; } ?>
      </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
      <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="#" onclick="javascript:closeDialog('#_dialog-box_','#editGracePeriod');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>