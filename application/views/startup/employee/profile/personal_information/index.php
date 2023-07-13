<script>
$(function() {
$("#quick_search").autocomplete({
minLength: 2,
source:  base_url + 'startup/_quick_autocomplete',
select: function( event, ui ) {
			$( "#quick_search" ).val(ui.item.label);
			window.location = base_url+"startup/profile?eid="+ui.item.id+"&hash="+ui.item.hash+"#personal_details";
			return false;
		}
});
});
</script>
<?php 
include 'personal_information/photo/tipsy_photo.php';
?>

<!--<div id="pagination_wrapper" class="nextprevious_record"><?php // echo $previous_record; ?><input type="text" name="quick_search" id="quick_search" /><?php //echo $next_record; ?> </div>-->

<div id="employee_summary_wrapper"><?php include 'employee_summary.php';?></div>
<div id="personal_details_wrapper"></div>
<div id="personal_history_wrapper"></div>
<div id="contact_details_wrapper"></div>
<div id="emergency_contacts_wrapper"></div>
<div id="dependents_wrapper"></div>
<div id="banks_wrapper"></div>
<div id="medical_history_wrapper"></div>
<div id="employment_status_wrapper" class="section_container"></div>
<div id="compensation_wrapper"></div>
<div id="compensation_history_wrapper" class="section_container"></div>
<div id="performance_wrapper"></div>
<div id="training_wrapper"></div>
<div id="memo_notes_wrapper"></div>
<div id="supervisor_wrapper"></div>
<div id="leave_wrapper"></div>
<div id="deductions_wrapper"></div>
<div id="membership_wrapper"></div>
<div id="work_experience_wrapper"></div>
<div id="education_wrapper"></div>
<div id="skills_wrapper"></div>
<div id="language_wrapper"></div>
<div id="license_wrapper"></div>
<div id="work_schedule_wrapper"></div>
<div id="attachment_wrapper"></div>
<div id="requirements_wrapper"></div>
<div id="contribution_wrapper"></div>
<div id="duration_wrapper"></div>
<div id="subdivision_history_wrapper" class="section_container"></div>
<div id="job_history_wrapper" class="section_container"></div>

<input type="hidden" name="employee_id" id="employee_id" value="<?php echo $employee_id; ?>">
<div id="photo_wrapper"></div>
<div id="photo_filename_wrapper"></div>

<script language="javascript">		
$('.tooltip_next').tipsy({gravity: 'w',html:true});
$('.tooltip_prev').tipsy({gravity: 'e',html:true});
</script>