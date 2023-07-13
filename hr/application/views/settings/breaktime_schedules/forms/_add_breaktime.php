<script>
$(function(){  
  $('#add_breaktime_schedule_form').validationEngine('attach',{scroll:false});   
  $('.append-breaktime-picker').timepicker({
    'minTime': '8:00 am',
    'maxTime': '7:30 am',
    'timeFormat': 'g:i a'
  });  

  $(".remove-append-breaktime-schedule-<?php echo $append_level; ?>").click(function(){    
    var dataIndexValue = "breaktime-schedule-<?php echo $append_level; ?>";   
     $("div#" + dataIndexValue).remove();
  }); 
  
});
</script>
<div class="append-breaktime-schedule-list">
  <table width="100%" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <td style="width:15%" align="left" valign="middle">Break Time</td>
      <td style="width:52%" align="left" valign="middle">
          <input type="text" style="width:75px;" name="breaktime[<?php echo $append_level; ?>][break_in]" id="append-breaktime-in-<?php echo $append_level; ?>" class="append-breaktime-picker validate[required] text-input" placeholder="Time In" />
          to 
          <input type="text" style="width:75px;" name="breaktime[<?php echo $append_level; ?>][break_out]" id="append-breaktime-out-<?php echo $append_level; ?>"  class="append-breaktime-picker validate[required] text-input" placeholder="Time Out" /><a title="Remove" href="javascript:void(0);" class="remove-append-breaktime-schedule-<?php echo $append_level; ?> btn btn-small remove-btn"><i class="icon icon-remove-circle"></i></a><br />          
          <div class="day-type-options-container"> 
            <p><b>Options : </b></p>           
            <ul class="day-type-options-list">
              <?php foreach( $day_type_options as $key => $value ){ ?>
                <li><label class="checkbox chk-<?php echo $key; ?>"><input type="checkbox" name="breaktime[<?php echo $append_level; ?>][applied_to_day_type][<?php echo $key; ?>]" value="1" /><?php echo $value; ?></label></li>
              <?php } ?>
                <li><label class="checkbox chk-deduct-working-hrs"><input type="checkbox" name="breaktime[<?php echo $append_level; ?>][is_deducted]" value="1" />Deduct from working hrs</label></li>

                <li><label class="checkbox chk-rquired-logs"><input type="checkbox" name="breaktime[<?php echo $append_level; ?>][is_required_logs]" value="1"/>Required Logs</label></li>
            </ul>
          </div>          
      </td>
    </tr>         
  </table>
</div>
