<?php $style = ($employee_status_id != G_Settings_Employee_Status::INACTIVE ? 'display:none;' : ''); ?>
<tr id="inactive_date_wrapper" style="<?php echo $style; ?>">
  <td class="field_label">Inactive Date</td>
   <?php $inactive_date = ($d['inactive_date']=='0000-00-00')? '': $d['inactive_date']; ?>
  <td><input class="validate[required] text-input" type="text" name="inactive_date" id="inactive_date" value="<?php echo $inactive_date; ?>" /></td>
</tr>