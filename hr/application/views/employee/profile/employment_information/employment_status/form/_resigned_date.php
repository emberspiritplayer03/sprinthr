<?php $style = ($employee_status_id != G_Settings_Employee_Status::RESIGNED ? 'display:none;' : ''); ?>
<tr id="resigned_date_wrapper" style="<?php echo $style; ?>">
  <td class="field_label">Resignation Date</td>
   <?php $resignation_date = ($d['resignation_date']=='0000-00-00')? '': $d['resignation_date']; ?>
  <td><input class="validate[required] text-input" type="text" name="resignation_date" id="resignation_date" value="<?php echo $resignation_date; ?>" /></td>
</tr>