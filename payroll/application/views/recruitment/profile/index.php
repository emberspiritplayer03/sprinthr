<script>
$(function() {
$("#quick_search").autocomplete({
minLength: 2,
source:  base_url + 'recruitment/_quick_autocomplete',
select: function( event, ui ) {
			$( "#quick_search" ).val(ui.item.label);
			window.location = base_url+"recruitment/profile?rid="+ui.item.id+"&hash="+ui.item.hash+"#personal_details";
			return false;
		}
});
});
</script>

<?php 
include 'personal_information/photo/tipsy_photo.php';
?>
<div id="pagination_wrapper" class="nextprevious_record"> <?php echo $previous_record; ?><input type="text" name="quick_search" id="quick_search" /><?php echo $next_record; ?></div>
<div id="applicant_summary_wrapper">
<?php 
include 'applicant_summary.php';
?>


</div>

<div id="personal_details_wrapper"></div>
<div id="application_history_wrapper"></div>
<div id="contact_details_wrapper"></div>
<div id="requirements_wrapper"></div>
<div id="examination_wrapper"></div>
<div id="attachment_wrapper"></div>
<div id="work_experience_wrapper"></div>
<div id="education_wrapper"></div>
<div id="training_wrapper"></div>
<div id="skills_wrapper"></div>
<div id="license_wrapper"></div>
<div id="language_wrapper"></div>

<div id="application_event_wrapper"></div>
<input type="hidden" name="applicant_id" id="applicant_id" value="<?php echo $applicant_id; ?>">
<div id="photo_wrapper"></div>
<div id="photo_filename_wrapper"></div>
<script language="javascript">		
$('.tooltip_next').tipsy({gravity: 'w',html:true});
$('.tooltip_prev').tipsy({gravity: 'e',html:true});
</script>