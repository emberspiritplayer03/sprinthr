<script>
$(document).ready(function() {
$("#schedule_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true,minDate: 0, maxDate: '+1M +10D'});

	$("#resched_form").validationEngine({scroll:false});

	$('#resched_form').ajaxForm({
		success:function(o) {
			window.location = "examination";
				
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
});


</script>
<div id="detailscontainer" class="detailscontainer_blue">
	<div id="applicant_details">
        <div class="employee_form_summary" id="formwrap">
            <div class="inner_form" id="form_main">
                <div id="form_default">
                    <div class="col_1_4"><img class="applicant_exam_pp" src="<?php echo BASE_FOLDER;?>images/profile_noimage.gif" alt="Profile Photo"  /></div>
                    <div class="col_3_4">
                        <form id="resched_form" name="resched_form" method="post" action="<?php echo url('recruitment/_update_applicant_examination'); ?>">
                        <input type="hidden" name="applicant_examination_id" value="<?php echo $applicant_examination_id; ?>">
                          <table>
                            <tr>
                              <td class="field_label">Applicant Name:</td>
                              <td class="bold blue"><?php echo $applicant->lastname . ', ' . $applicant->firstname; ?></td>
                            </tr>
                            <tr>
                              <td class="field_label">Examination:</td>
                              <td class="bold"><?php echo $examination->title; ?></td>
                            </tr>
                            <tr>
                              <td class="field_label">Passing Percentage:</td>
                              <td class="bold"><?php echo $examination->passing_percentage; ?>%</td>
                            </tr>
                            <tr>
                              <td class="field_label">Status:</td>
                              <td class="bold"><?php echo $examination->status; ?>&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="field_label">Schedule Date:</td>
                              <td class="bold"><?php echo Date::convertDateIntIntoDateString($examination->schedule_date); ?></td>
                            </tr>
                            <tr>
                              <td class="field_label">Reschedule:</td>
                              <td class="bold"><input class="text-input" type="text" name="schedule_date" id="schedule_date"></td>
                            </tr>
                          </table>
                        </form>
                    </div>
                    <div class="clear"></div>
                </div><!-- #form_default -->
                <div id="form_default" class="yellow_form_action_section form_action_section yellow_section">
                	<div class="col_1_4">&nbsp;</div>
                    <div class="col_3_4"><input type="submit" class="curve blue_button" name="button" id="button" value="Update Exam Schedule">&nbsp;<a href="javascipt:void(0);">Cancel Examination</a></div>
                    <div class="clear"></div>
                </div>
            </div><!-- #form_main -->        
        </div>
    </div><!-- #applicant_details -->
</div>

