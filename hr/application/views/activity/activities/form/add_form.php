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
		$('#add_activity_form').validationEngine({
			scroll: false
		});

		$('#add_activity_form').ajaxForm({
			success: function (o) {
				if (o.is_saved) {
					load_activities_list_dt();
					hide_add_activity_form();
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					dialogOkBox(o.message, {});
				} else {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					dialogOkBox(o.message, {});

					$('#add_activity_form #token').val(o.token);
				}
			},
			dataType: 'json',
			beforeSubmit: function () {
				showLoadingDialog('Saving...');
			}
		});
	});
</script>
<div id="formcontainer">
	<form id="add_activity_form" name="add_activity_form"
		action="<?php echo url('activity/_save_activity'); ?>" method="post">
		<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
		<div id="formwrap">
			<h3 class="form_sectiontitle">Activity</h3>
			<div id="form_main">

				<div id="form_default">
					<table>
						<tr>
							<td class="field_label">Name:</td>
							<td>
								<input class="validate[required] text-input" type="text" name="activity_skills_name"
									id="activity_skills_name" value="" />
							</td>
						</tr>
						<tr>
							<td class="field_label">Desicription:</td>
							<td>
								<textarea class="input-large" rows="3" id="activity_skills_description"
									name="activity_skills_description"></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div id="form_default" class="form_action_section">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td class="field_label">&nbsp;</td>
							<td>
								<input type="submit" value="Save" class="curve blue_button" />
								<a href="javascript:void(0)" onclick="javascript:hide_add_activity_form();">Cancel</a>
							</td>
						</tr>
					</table>
				</div>
			</div><!-- #form_main -->
		</div>
	</form>
</div>