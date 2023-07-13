<script>
$(function(){  
  $('#edit_breaktime_schedule_form').validationEngine('attach',{scroll:false});   
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
    <?php if( !empty($details) ){ ?>
      <tr>
        <td style="width:15%" align="left" valign="middle">Break Time</td>
        <td style="width:52%" align="left" valign="middle">
            <input type="text" style="width:75px;" name="breaktime[<?php echo $append_level; ?>][break_in]" id="append-breaktime-in-<?php echo $append_level; ?>" class="append-breaktime-picker validate[required] text-input" placeholder="Time In" value="<?php echo $details['formatted_break_in']; ?>" />
            to 
            <input type="text" style="width:75px;" name="breaktime[<?php echo $append_level; ?>][break_out]" id="append-breaktime-out-<?php echo $append_level; ?>" class="append-breaktime-picker validate[required] text-input" placeholder="Time Out" value="<?php echo $details['formatted_break_out']; ?>" />
            <a title="Remove" href="javascript:void(0);" class="remove-append-breaktime-schedule-<?php echo $append_level; ?> btn btn-small remove-btn"><i class="icon icon-remove-circle"></i></a><br />
            <label class="checkbox chk-deduct-working-hrs">
              <div class="day-type-options-container"> 
                <p><b>Options : </b></p>           
                <ul class="day-type-options-list">
                  <?php 
                    foreach( $day_type_options as $key => $value ){ 
                      if( $key == 'regular_day' && $details['applied_to_regular_day'] == 1 ){
                        $is_checked = "checked='checked'";
                      }elseif( $key == 'rest_day' && $details['applied_to_restday'] == 1 ){
                        $is_checked = "checked='checked'";
                      }elseif( $key == 'legal_holiday' && $details['applied_to_legal_holiday'] == 1 ){
                        $is_checked = "checked='checked'";
                      }elseif( $key == 'special_holiday' && $details['applied_to_special_holiday'] == 1 ){
                        $is_checked = "checked='checked'";
                      }else{
                        $is_checked = "";
                      }
                  ?>
                    <li><label class="checkbox chk-<?php echo $key; ?>"><input type="checkbox" name="breaktime[<?php echo $append_level; ?>][applied_to_day_type][<?php echo $key; ?>]" value="1" <?php echo $is_checked; ?> /><?php echo $value; ?></label></li>
                  <?php } ?>
                    <li><label class="checkbox chk-deduct-working-hrs"><input type="checkbox" name="breaktime[<?php echo $append_level; ?>][is_deducted]" value="1" <?php echo($details['to_deduct'] == $to_deduct ? 'checked="checked"' : ''); ?> />Deduct from working hrs</label></li>
                     <li><label class="checkbox chk-required-logs"><input type="checkbox" name="breaktime[<?php echo $append_level; ?>][is_required_logs]" value="1" <?php echo($details['to_required_logs'] == $to_required_logs ? 'checked="checked"' : ''); ?> />Required Logs</label></li>
                </ul>
              </div> 
        </td>
      </tr>
    <?php }else{ ?>
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
                  <li><label class="checkbox chk-deduct-working-hrs"><input type="checkbox" name="breaktime[<?php echo $append_level; ?>][applied_to_day_type][<?php echo $key; ?>]" value="1" /><?php echo $value; ?></label></li>
                <?php } ?>
                  <li><label class="checkbox chk-deduct-working-hrs"><input type="checkbox" name="breaktime[<?php echo $append_level; ?>][is_deducted]" value="1" />Deduct from working hrs</label></li>

                  <li><label class="checkbox chk-required-logs"><input type="checkbox" name="breaktime[<?php echo $append_level; ?>][is_required_logs]" value="1"/>Required Logs</label></li>
              </ul>
            </div>   
        </td>
      </tr>
    <?php } ?>   
  </table>
</div>
