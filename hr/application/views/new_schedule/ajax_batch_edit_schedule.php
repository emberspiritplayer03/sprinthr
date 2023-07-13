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
	action="<?php echo url("new_schedule/_batch_update_new_schedule"); ?>">
	<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
	<center>
	<h4>Change Schedule</h4>
	<?php
			foreach($schedule as $schedule_name){?>
			
				<input type="radio" id="module[contact_details]" name="schedule" value="<?php echo $schedule_name->getId();?>" class="field_label" <?php echo ($schedule_name->getId() == 1) ? "checked" : ""?> /> 
				<?php
					if($schedule_name->getName() == 'default staggered'){
						echo "Staggered";
					}else if($schedule_name->getName() == 'default compress'){
						echo "Compress";
					}else if($schedule_name->getName() == 'default shift'){
						echo "Shift";
					}else{
						echo $schedule_name->getName();
					}
				?>
				
			<?php }
		?>
	</center>
	<br>
	<div id="form_main" class="inner_form popup_form" style="display:flex;flex-wrap: wrap;">
		
		<?php foreach ($employee_logs as $key => $employee_log) { ?>
			<div id="form_default" class="employee-log-wrapper">
				<input type="hidden" name="v2_employee_attendance_id" id="token" value="<?php echo $employee_log->getId(); ?>" />
				<input type="hidden" name="ids[]" id="ids" value="<?php echo $employee_log->getEmployeeId(); ?>" />
				<table>
					<tr>
						<td class="field_label">Name:</td>
						<td class="field_label"><?php 
						$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($employee_log->getEmployeeId());
						echo $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename; ?></td>
					</tr>
					<tr>
						<td class="field_label">Time In:</td>
						<td>
							<input disabled type="text" class="validate[required] a_time" name="a_time_in[]" id="a_time"
								value="<?php echo $employee_log->getTimeIn(); ?>" />
						</td>
					</tr>
					<tr>
						<td class="field_label">Time Out:</td>
						<td>
							<input disabled type="text" class="validate[required] a_time" name="a_time_out[]" id="a_time"
								value="<?php echo $employee_log->getTimeOut(); ?>" />
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