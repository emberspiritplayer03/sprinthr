<!--<div id="formcontainer">
<div class="mtshad"></div>-->
<form id="add_holiday_form" method="post" action="<?php echo $action;?>">
<input type="hidden" id="token" name="token" value="<?php echo $token;?>" />
<div class="formwrap inner_form">	
	<h3 class="form_sectiontitle">Add Holiday</h3>
    <div id="form_main">
        <div id="form_default">
        <p>Note : If holiday date is within cutoff period, system will reprocess employees attendance for the holiday to take effect.</p>
        <table width="100%">          
          <tr>
            <td class="field_label">Holiday Name: *</td>
            <td><input type="text" class="validate[required] text-input" name="holiday_name" id="_holiday_name" onchange="holidayNameChanged()" /> <small>(e.g. Rizal Day)</small></td>
          </tr>
          <tr>
            <td class="field_label">Month, Day &amp; Year: *</td>
            <td><select class="select_option_sched" name="month">
              <?php for ($i = 1; $i <= 12; $i++):?>
              <option value="<?php echo $i;?>"><?php echo date('F', strtotime("2012-{$i}-1"));?></option>
              <?php endfor;?>
            </select>
            <select class="select_option_sched" name="day">
                <?php for ($i = 1; $i <= 31; $i++):?>
                <option><?php echo $i;?></option>
                <?php endfor;?>
            </select>
            <select class="select_option_sched" name="year">
                <?php foreach ($years as $year):?>
                    <option <?php echo ($year == $current_year) ? 'selected="selected"' : '' ;?> value="<?php echo $year;?>"><?php echo $year;?></option>
                <?php endforeach;?>
            </select>
            </td>
          </tr>
          <tr>
            <td class="field_label">Holiday Type: *</td>
            <td><select class="select_option_sched" name="holiday_type">
              <option value="1">Legal</option>
              <option value="2">Special</option>
            </select></td>
          </tr>                    
          </table>
        </div>
        <div id="form_default" class="form_action_section">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="field_label">&nbsp;</td>
                    <td>
                    <input value="Add Holiday" id="add_holiday_submit" class="blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeAddHoliday()">Cancel</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</form>
<!--</div>-->