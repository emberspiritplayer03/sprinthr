<?php if($employee_status_id != G_Settings_Employee_Status::ACTIVE) { ?>
<?php $style = ($employee_status_id == G_Settings_Employee_Status::ACTIVE ? 'display:none;' : ''); ?>
<?php $active_date = !empty($current_employee_active_date) ? $current_employee_active_date : $hired_date; ?>
<?php if(!empty($active_date) || $active_date != "") { ?>
		<tr id="active_date_wrapper" style="display: none;">
		  <td class="field_label">Active Date</td>
		  <td><input class="validate[required] text-input" type="text" name="active_date" id="active_date" value="<?php echo $current_employee_active_date; ?>" /></td>
		</tr>
<?php } else { ?>
		<tr id="active_date_wrapper" style="display: none;">
		  <td class="field_label">Active Date</td>
		  <td><input class="validate[required] text-input" type="text" name="active_date" id="active_date" value="<?php echo $current_employee_active_date; ?>" /></td>
		</tr>
<?php } ?>
<?php } ?>
