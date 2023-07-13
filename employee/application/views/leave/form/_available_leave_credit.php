<?php if($leave_credit) { ?>
	<?php if($leave_credit->getNoOfDaysAvailable() > 0) { ?>
		<select class="validate[required] select_option_sched" name="is_paid" id="is_paid" style="width:219px;">
            <option value="<?php echo G_Employee_Leave_Request::YES; ?>"><?php echo G_Employee_Leave_Request::YES; ?></option>
            <option value="<?php echo G_Employee_Leave_Request::NO; ?>"><?php echo G_Employee_Leave_Request::NO; ?></option>
          </select>
	<?php }else{ ?>
		<input name="is_paid" type="text" id="is_paid" readonly="readonly" value="No" />
	<?php } ?>
<?php }else{ ?>
	<input name="is_paid" type="text" id="is_paid" readonly="readonly" value="No" />
<?php } ?>