<script>
	function selectText() {
		$('#employee_code').select();
	}

	function refresh_list() {
		$.post(base_url + 'dtr/refresh', {}, function(data) {
			$('#record_handler').html(data);
		});
	}

	$(document).ready(function(e) {
		function refreshTime() {
			var currentdate = new Date();
			var h = currentdate.getHours();

			if (h > 12) {
				var hour = h - 12;
			} else if (h == 0) {
				var hour = 12;
			} else {
				var hour = h;
			}

			if (h >= 12) {
				var ampm = 'pm';
			} else {
				var ampm = 'am';
			}
			var minutes = (currentdate.getMinutes() < 10 ? '0' : '') + currentdate.getMinutes();
			var seconds = (currentdate.getSeconds() < 10 ? '0' : '') + currentdate.getSeconds();
			$('.time_view').html(hour + ':' + minutes + ':' + seconds + ' ' + ampm);
		}
		setInterval(refreshTime, 1000);
		refresh_list();
	});

	$('#dtr_form').ajaxForm({
		success: function(o) {
			if (o.has_error) {
				$('#message').html('<div class="ui-state-error ui-corner-all"><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span> Invalid Employee ID</div>');
				$('#message').show();
				$('#employee_code').select();
				$('.user_image_holder').html('<img src="<?php echo MAIN_FOLDER; ?>/images/profile_noimage.gif">');
			} else {
				$('#message').html('<div class="ui-state-highlight ui-corner-all"><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span> Successfully Saved!</div>');
				$('#message').show();
				$('#employee_code').val('');
				$('#employee_code').select();
				$('.user_image_holder').html('<img src="' + o.image + '">');
				refresh_list();
			}
			$('.dtr_button').removeAttr("disabled");
		},
		dataType: 'json',
		beforeSubmit: function() {
			$('.dtr_button').attr("disabled", "disabled");
			$('#message').show();
			$('#message').html('<div class="ui-state-highlight ui-corner-all"><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span> Saving...</div>');
			var code = $('#employee_code').val();
			if (code != '') {
				return true;
			}
			$('#message').hide();
			$('.dtr_button').removeAttr("disabled");
			return false;
		}
	});

	function removeLabels() {
		$('#message').hide();
		$('.user_image_holder').html('<img src="<?php echo MAIN_FOLDER; ?>/images/profile_noimage.gif">');
	}
</script>
<div id="dtr_container">
	<form id="dtr_form" name="dtr_form" action="<?php echo url('dtr/punch'); ?>">

		<!--
	<input onfocus="selectText()" checked="checked" type="radio" name="type" value="in"><span class="dtr_label">IN</span>
	<input onfocus="selectText()" type="radio" name="type" value="out"><span class="dtr_label">OUT</span>
	-->
		<div class="container">
			<div class="dtr_form_holder">
				<div class="user_image_holder">
					<img src="<?php echo MAIN_FOLDER; ?>/images/profile_noimage.gif">
				</div>
				<span class="dtr_label">Type Your Employee ID Here:</span>
				<input onkeydown="removeLabels()" onfocus="selectText()" autofocus="autofocus" type="text" id="employee_code" name="employee_code" class="dtr_textbox input-large" />
				<input name="type" type="submit" value="IN" class="dtr_button btn btn-large btn-primary btn-in" />
				<input name="type" type="submit" value="OUT" class="dtr_button btn btn-large btn-out" />
				<div class="clear"></div>
				<div id="message"></div>
				<tr>
					<td class="field_label">
						<select style="width:35%; text-align: center;" class="validate[required]" name="project_site_id" id="project_site_id" >
							<option value="" selected="selected">-- Select Project Site --</option>

							<?php foreach ($project_sites as $key => $value) { ?>
								<option value="<?php echo $value->getId(); ?>"><?php echo $value->getName(); ?></option>
							<?php } ?>

						</select>
					</td>
					&nbsp;&nbsp;&nbsp;
					<td class="field_label">
						<select style="width:35%; text-align: center;" class="validate[required] select_option" name="activity_name" id="activity_id">
							<option value="" selected="selected">-- Select Activity --</option>
							<?php foreach ($activity_skills as $key => $value) {
								$now = date('Y-m-d');
								if ($value->date_started <= $now && $value->date_ended >= $now) { ?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->activity_skills_name; ?></option>

							<?php
								}
							} ?>

						</select>
					</td>
					
				</tr>



				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</form>

	<div id="record_handler"></div>
</div>