<?php $style = ($employee_status_id != G_Settings_Employee_Status::TERMINATED ? 'display:none;' : ''); ?>
<tr id="termination_date_wrapper" style="<?php echo $style; ?>">
  <td class="field_label">Termination Date</td>
   <?php $terminated_date = ($d['terminated_date']=='0000-00-00')? '': $d['terminated_date']; ?>
  <td><input class="validate[required] text-input" type="text" name="terminated_date" id="terminated_date" value="<?php echo $terminated_date; ?>" /></td>
</tr>