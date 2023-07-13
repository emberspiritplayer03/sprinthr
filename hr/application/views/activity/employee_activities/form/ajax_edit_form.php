<style>
	.leave-header {
		padding: 4px;
		background-color: #198cc9;
		color: #ffffff;
		margin-top: 9px;
		line-height: 27px;
	}
</style>
<script>
	$(document).ready(function () {
		$('#edit_employee_activity_form').validationEngine({
			scroll: false
		});

		$("#activity_date").datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			showOtherMonths: true
		});

		$('#time_in').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a',
		});

		$('#time_out').timepicker({
			'minTime': '8:00 am',
			'maxTime': '7:30 am',
			'timeFormat': 'g:i a'
		});
	});

	function checkForm() {
		if ($('#edit_employee_activity_form').validationEngine({
				returnIsValid: true
			})) {
			$('#edit_employee_activity_form').ajaxForm({
				success: function (o) {
					if (o.is_success == 1) {
						load_employee_activities_list_dt();
						closeDialog('#' + DIALOG_CONTENT_HANDLER);
						dialogOkBox(o.message, {});
					} else {
						closeDialog('#' + DIALOG_CONTENT_HANDLER);

						var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
						var message = '<br><div class="confirmation-alert"><div>' + o.message;

						blockPopUp();
						$(dialog_id).html(message);
						var $dialog = $(dialog_id);
						$dialog.dialog({
							title: 'Message',
							resizable: false,
							width: 'auto',
							height: 'auto',
							modal: true,
							close: function () {
								$dialog.dialog("destroy");
								$dialog.hide();
								disablePopUp();
							},
							buttons: {
								'Ok': function () {
									$dialog.dialog("close");
									disablePopUp();
									editEmployeeActivity(o.id);
								}
							}
						}).show();

					}
				},
				dataType: 'json',
				beforeSubmit: function () {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					showLoadingDialog('Saving...');
				}
			});
			return true;
		} else {
			return false;
		}
	}
</script>
<div id="form_main" class="inner_form popup_form wider">
	<form id="edit_employee_activity_form" name="edit_employee_activity_form" onsubmit="javascript:checkForm();"
		action="<?php echo url('activity/_update_employee_activity'); ?>" method="post">
		<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
		<input type="hidden" id="employee_activity_id" name="employee_activity_id"
			value="<?php echo Utilities::encrypt($employee_activity->getId()); ?>" />
		<input type="hidden" name="employee_id" value="<?php echo $eid; ?>" />
		<div id="form_default">
			<table>
				<tr>
					<td class="field_label">Type Employee Name:</td>
					<td>
						<input class="validate[required] input-large" type="text" name="employee_id" id="employee_id"
							value="<?php echo $employee_name; ?>" readonly="readonly" />
					</td>
				</tr>
				<tr>
					<td class="field_label">Designation:</td>
					<td>
						<div id="category_dropdown_wrapper">
							<select class="validate[required] select_option" name="category_id" id="category_id">
								<option value="" selected="selected">-- Select Designation --</option>
								<?php foreach($activity_categories as $key=>$value) { ?>
								<option value="<?php echo $value->id; ?>"
									<?php echo $value->id == $employee_activity->getActivityCategoryId() ? 'selected' : '' ?>>
									<?php echo $value->activity_category_name; ?></option>
								<?php } ?>
								<option value="add">Add Designation...</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td class="field_label">Activity:</td>
					<td>
						<div id="activity_dropdown_wrapper">
							<select class="validate[required] select_option" name="activity_id" id="activity_id">
								<option value="" selected="selected">-- Select Activity --</option>
								<?php foreach($activity_skills as $key=>$value) { ?>
								<option value="<?php echo $value->id; ?>"
									<?php echo $value->id == $employee_activity->getActivitySkillsId() ? 'selected' : '' ?>>
									<?php echo $value->activity_skills_name; ?></option>
								<?php } ?>
								<option value="add">Add Activity...</option>
							</select>
						</div>
					</td>
				</tr>

				<tr>
		          <td class="field_label">Project Site:</td>
		          <td> 
		              <select name="project_site_id" id="project_site_id">
		                <option selected="selected" value="">-select project site -</option>
		                <?php foreach($project_sites as $key => $project_site) { ?>

		                	<option value="<?php echo $project_site->getId(); ?>"
									<?php echo $project_site->getId() == $employee_activity->getProjectSiteId() ? 'selected' : '' ?>>
									<?php echo $project_site->getName(); ?></option>

		                <?php } ?>
		              </select>                    
		          </td>
		        </tr> 

			</table>
			<h3 class="leave-header">Activity Details</h3>
			<table>
				<tr>
					<td class="field_label">Date:</td>
					<td>
						<input class="validate[required] input-small" type="text" name="activity_date"
							id="activity_date" value="<?php echo $employee_activity->getDate(); ?>" />
					</td>
				</tr>
				<tr>
					<td class="field_label">Time:</td>
					<td>
						<input class="validate[required] input-small" type="text" name="time_in" id="time_in"
							value="<?php echo $employee_activity->getTimeIn(); ?>" placeholder="Starts on" />
						<input class="validate[required] input-small" type="text" name="time_out" id="time_out"
							value="<?php echo $employee_activity->getTimeOut(); ?>" placeholder="Ends on" />
					</td>
				</tr>
				<tr>
					<td class="field_label">Reason:</td>
					<td>
						<textarea class="input-large" rows="3" id="reason"
							name="reason"><?php echo $employee_activity->getReason(); ?></textarea>
					</td>
				</tr>
			</table>
		</div>
		<div id="form_default" class="form_action_section">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="field_label">&nbsp;</td>
					<td>
						<input type="submit" value="Update" class="curve blue_button" />
						<a href="javascript:void(0)"
							onclick="javascript:closeDialogBox('#_dialog-box_','#edit_employee_activity_form');">Cancel</a>
					</td>
				</tr>
			</table>
		</div>
	</form>
</div><!-- #form_main -->