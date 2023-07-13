<style>
    .date_input {
        width: 44% !important;
    }
</style>

<script>
	$(function() {
		$(".all-employees").click(function() {
			if ($(this).is(':checked')) {
				$(".autocomplete-employees").hide();
			} else {
				$(".autocomplete-employees").show();
			}
		});

	});

	$(function() {
		$('input:checkbox').attr('unchecked','checked');
		$('.ckoptions, .cksectionoption').change(function() {
			$('#all_modules').attr('checked',false)
		});
	});
	
	function check_uncheck_options() {
		var a = $('#all_modules').attr('checked');
		var attr = (a=="checked" ? 'checked' : false);
		$('.ckoptions').attr('checked',attr);
		$('.cksectionoption').attr('checked',attr);
	}
	
	function check_uncheck_section(section) {
		
		if(section == 'personal_information') {
			var a = $('#personal_information_section').attr('checked');
			var attr = (a=="checked" ? 'checked' : false);
			$('.ckpersonal_information').attr('checked',attr);
			
		} else if(section == 'employment_information') {
			var a = $('#employment_information_section').attr('checked');
			var attr = (a=="checked" ? 'checked' : false);
			$('.ckemployment_information').attr('checked',attr);
		} else if(section == 'qualification') {
			var a = $('#qualification_section').attr('checked');
			var attr = (a=="checked" ? 'checked' : false);
			$('.ckqualification').attr('checked',attr);
		}
	}
	
	function uncheck_section(id) {
		$(id).attr('checked',false);
	}
</script>
<form method="post" action="<?php echo url('new_schedule/_assign_schedule_from_employee_list'); ?>">
	<div class="autocomplete-employees">
		<input type="hidden" name="schedule_id" value="<?php echo $schedule_id; ?>" />
		Type employees: <input type="text" name="employees_autocomplete" id="employees_autocomplete" />
	</div>
	<label class="checkbox"><input class="all-employees" name="apply_to_all" type="checkbox" />All Employees</label><br>
	*Start Date:<input class="validate[required] text-input date_input" type="date" name="start_date" id="start_date2" value="" /><br>
	*End Date:&nbsp;<input class="validate[required] text-input date_input" type="date" name="end_date" id="end_date2" value="" /><br><br>
	<div class="">
		<h4>Schedule</h4>
		<?php
		foreach($schedule as $schedule_name){?>
			
			<input type="radio" id="module[contact_details]" onclick="javascript:uncheck_section('#personal_information_section');" name="schedule" value="<?php echo $schedule_name->getId();?>" class="ckoptions ckpersonal_information" /> <?php echo $schedule_name->getName();?>
			
		<?php }

		?>
	</div>

</form>

<div id="status_message"></div>
<div id="schedule_members_list"></div>

<script>

$("#add_schedule_form #start_date2").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect: function(date){
       $("#add_schedule_form #end_date2").datepicker('option',{minDate:$(this).datepicker('getDate')});
    }
});

$("#add_schedule_form #end_date2").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true
});
</script>