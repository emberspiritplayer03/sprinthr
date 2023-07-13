<div id="form_main" class="inner_form popup_form">
<form id="edit_holiday_form" method="post" action="<?php echo $action;?>">
<input type="hidden" name="holiday_id" value="<?php echo $holiday_id;?>" />
<input type="hidden" name="old_branch_ids" value="<?php echo $selected_branch_ids;?>" />
<input type="hidden" id="token" name="token" value="<?php echo $token;?>" />
    <div id="form_default">
      <table>
        <tr>
          <td class="field_label">Holiday Name: *</td>
          <td><input class="validate[required] text-input" type="text" name="holiday_name_" id="holiday_name_" value="<?php echo $holiday->getTitle();?>" /> </td>
      </tr>
      <tr>
        <td class="field_label">Month &amp; Day: *</td>
        <td><select class="select_option_sched" name="month_">
          <?php for ($i = 1; $i <= 12; $i++):?>
          <option <?php echo ($holiday->getMonth() == $i) ? 'selected="selected"' : '' ;?> value="<?php echo $i;?>"><?php echo date('F', strtotime("2012-{$i}-1"));?></option>
          <?php endfor;?>
        </select>
          <select class="select_option_sched" name="day_">
            <?php for ($i = 1; $i <= 31; $i++):?>
            <option <?php echo ($holiday->getDay() == $i) ? 'selected="selected"' : '' ;?> value="<?php echo $i;?>"><?php echo $i;?></option>
            <?php endfor;?>
        </select></td>
      </tr>
      <tr>
        <td class="field_label">Holiday Type: *</td>
        <td><select class="select_option_sched" name="holiday_type_">
          <option <?php echo ($holiday->getType() == G_Holiday::LEGAL) ? 'selected="selected"' : '' ;?> value="1">Legal</option>
          <option <?php echo ($holiday->getType() == G_Holiday::SPECIAL) ? 'selected="selected"' : '' ;?> value="2">Special</option>
        </select></td>
      </tr>
      <tr>
        <td class="field_label">Affected Branches: *</td>
        <td>
        	<label><input <?php echo ($is_select_all) ? 'checked="checked"' : '' ;?> class="validate[minCheckbox[1]]" onchange="checkAllBranches('#edit_holiday_form')" name="branches_[]" type="checkbox" value="all" id="_checkbox-all" />All</label><br />
        	<?php foreach ($branches as $branch_id => $branch_name):?>        	
        	<label><input <?php echo (in_array($branch_name, $selected_branches)) ? 'checked="checked"' : '' ;?> class="validate[minCheckbox[1]] _branch_checkbox" onchange="checkBranch('#edit_holiday_form')" name="branches_[]" type="checkbox" value="<?php echo $branch_id;?>" id="_checkbox-<?php echo $branch_id;?>" /><?php echo $branch_name;?></label><br />
            <?php endforeach;?>
        </td>
      </tr>
    </table>
    </div><!-- #form_default -->
    <div class="form_action_section" id="form_default">
        <table>
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_holiday_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeEditHolidayDialog()">Cancel</a></td>
            </tr>
        </table>
    </div><!-- #form_default.form_action_section -->
</form>
</div>