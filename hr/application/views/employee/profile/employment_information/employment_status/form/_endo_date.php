<?php $style = ($employee_status_id != G_Settings_Employee_Status::ENDO ? 'display:none;' : ''); ?>
<tr id="endo_date_wrapper" style="<?php echo $style; ?>">
  <td class="field_label">End of Contract Date</td>
   <?php $endo_date = ($d['endo_date']=='0000-00-00')? '': $d['endo_date']; ?>
  <td><input class="validate[required] text-input" type="text" name="endo_date" id="endo_date" value="<?php echo $endo_date; ?>" /></td>
</tr>