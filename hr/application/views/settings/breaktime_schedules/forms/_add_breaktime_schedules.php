<?php for($start = 1; $start <= $ini_breaktime_schedule; $start++){ ?>
<table width="100%" border="0" cellspacing="1" cellpadding="2">
<tr>
  <td style="width:15%" align="left" valign="middle">Break Time</td>
  <td style="width:52%" align="left" valign="middle">
      <input type="text" style="width:75px;" name="breaktime[0][break_in]" id="breaktime-in" class="breaktime-picker validate[required] text-input" placeholder="Time In" />
      to 
      <input type="text" style="width:75px;" name="breaktime[0][break_out]" id="breaktime-out" class="breaktime-picker validate[required] text-input" placeholder="Time Out" /><br />      
      <div class="day-type-options-container"> 
        <p><b>Options : </b></p>           
        <ul class="day-type-options-list">
          <?php foreach( $day_type_options as $key => $value ){ ?>
            <li><label class="checkbox chk-<?php echo $key; ?>"><input type="checkbox" name="breaktime[0][applied_to_day_type][<?php echo $key; ?>]" value="1" /><?php echo $value; ?></label></li>
          <?php } ?>
            <li><label class="checkbox chk-deduct-working-hrs"><input type="checkbox" name="breaktime[0][is_deducted]" value="1" />Deduct from working hrs</label></li>

            <li><label class="checkbox chk-required-logs"><input type="checkbox" name="breaktime[0][is_required_logs]" value="1"/>Required Logs</label></li>
        </ul>
      </div>   
  </td>
</tr>     
</table>
<?php } ?>
