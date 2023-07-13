<script>
	$(document).ready(function () {
		$(".a_date").datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			showOtherMonths: true
		});

		$('.a_time').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a'
		});
	});
</script>
<form method="post" id="batch_edit_attendance_log" name="batch_edit_attendance_log"
	action="<?php echo url("attendance/_batch_update_attendance_log"); ?>">
	<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />

	<div id="form_main" class="inner_form popup_form" style="display:flex;flex-wrap: wrap;">
		<?php foreach ($employee_logs as $key => $employee_log) { ?>
			<div id="form_default" class="employee-log-wrapper">
				<input type="hidden" name="ids[]" id="ids" value="<?php echo Utilities::encrypt($employee_log->getId()); ?>" />
				<input type="hidden" name="previous_type[]" id="previous_type" value="<?php echo $employee_log->getType(); ?>" />

				<table>
					<tr>
						<td class="field_label">Name:</td>
						<td class="field_label"><?php echo $employee_log->getEmployeeName(); ?></td>
					</tr>
					<tr>
						<td class="field_label">Date:</td>
						<td>
							<input disabled type="text" class="validate[required] a_date" name="a_date[]" id="a_date"
								value="<?php echo Tools::convertDateFormat($employee_log->getDate()); ?>" />
						</td>
					</tr>
					<tr>
						<td class="field_label">Time:</td>
						<td>
							<input type="text" class="validate[required] a_time" name="a_time[]" id="a_time"
								value="<?php echo $employee_log->getTime(); ?>" />
						</td>
					</tr>
					<tr>
						<td class="field_label">Type:</td>
						<td>
							<select name="a_type[]" id="a_type" class="a_type">
								<?php foreach($log_types as $key=>$log_type) { ?>
								<option value="<?php echo $log_type; ?>"
									<?php echo(strtoupper($employee_log->getType()) == strtoupper($log_type) ? 'selected="selected"' : ""); ?>>
									<?php echo strtoupper($log_type); ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
				</table>
				<br />

			</div><!-- #form_default -->
		<?php } ?>

		<div id="form_default" class="form_action_section" style="width: 100%;">
			<table width="100%">
				<tr>
					<td style="text-align: center;"><input value="Save" id="batch_edit_logs" class="curve blue_button" type="submit">&nbsp;<a
							href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
				</tr>
			</table>
		</div><!-- #form_default -->
	</div><!-- #form_main.inner_form -->
</form>