<script>
	$(document).ready(function () {
		$('#edit_activity_form').validationEngine({
			scroll: false
		});
	});

	function checkForm() {
		if ($('#edit_activity_form').validationEngine({
				returnIsValid: true
			})) {
			$('#edit_activity_form').ajaxForm({
				success: function (o) {
					if (o.is_success == 1) {
						load_activities_list_dt();
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
									editActivity(o.id);
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
	<form id="edit_activity_form" name="edit_activity_form" onsubmit="javascript:checkForm();"
		action="<?php echo url('activity/_update_activity'); ?>" method="post">
		<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
		<input type="hidden" id="activity_id" name="activity_id" value="<?php echo Utilities::encrypt($activity->getId()); ?>" />
		<div id="form_default">
			<table>
				<tr>
					<td class="field_label">Name:</td>
					<td>
						<input class="validate[required] text-input" type="text" name="activity_skills_name" id="activity_skills_name" value="<?php echo $activity->getActivitySkillsName(); ?>" />
					</td>
				</tr>
				<tr>
					<td class="field_label">Desicription:</td>
					<td>
						<textarea class="input-large" rows="3" id="activity_skills_description" name="activity_skills_description"><?php echo $activity->getActivitySkillsDescription(); ?></textarea>
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
							onclick="javascript:closeDialogBox('#_dialog-box_','#edit_activity_form');">Cancel</a>
					</td>
				</tr>
			</table>
		</div>
	</form>
</div><!-- #form_main -->