<style>
#defaultCountdown { width: 240px; height: 45px; }
img.applicant_exam_pp {
    border: 1px solid #CCCCCC;
    border-radius: 3px 3px 3px 3px;
    height: auto;
    max-width: 95%;
    padding: 3px;
    width: auto;
	z-index:99999;
}
</style>
<script>
$(function() {
	$("#applicant_examination_form").validationEngine({scroll:true});
	$('#applicant_examination_form').ajaxForm({
		success:function(o) {
			dialogOkBox("Wait for checking.<br />We will email you for further updates.<br /><br />Thank you.",{ok_url:"applicant/dashboard"});	
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
	
	h = $('#h');
	m = $('#m');
	s = $('#s');
	var save_time = 0;
	var do_time_interval = setInterval(do_time,1000);
	
	function do_time() {
	
		hour = parseInt(h.text(), 10);
		minute = parseInt(m.text(), 10);
		second = parseInt(s.text(), 10);
	
		second--;
		
		if(hour <= 0 && minute <= 0 && second <= 0) {
			h.html("00");
			m.html("00");
			s.html("00");
			 $.blockUI({ message: $('#alternate_submit_wrapper') }); 
			 $('input').removeClass('validate[required]');
			 $('textarea').removeClass('validate[required]');
			 clearInterval(do_time_interval);
			
		} else {
			if (second < 0) {
				second = 59;
				minute = minute - 1;
			}
		
			if (minute < 0) {
				minute = 59;
				hour = hour - 1;
			}
			
			h.html("0".substring(hour >= 10) + hour);
			m.html("0".substring(minute >= 10) + minute);
			s.html("0".substring(second >= 10) + second);
		}
	
		if(save_time == 3) {
			$.post(base_url + 'examination/_record_time',{hour:hour,minute:minute,second:second},function(o) {});
			save_time = 0;
		}
		
		save_time++;
	}
});

</script>

<?php //Tools::showArray($_SESSION['sprinthr']['tmp_exam_timer']); ?>
<div id="dialog_examination_form"></div>
<div id="employee_search_container">
<div id="formwrap" class="employee_form_summary">
<div id="form_main" class="inner_form wider">
 <div id="form_default"> 
    <div class="col_1_4"><img class="applicant_exam_pp" src="<?php echo $filename;?>" alt="Profile Photo"  /></div>
    <div class="col_3_4">
    	<br />
          <table width="100%">
            <tr>
              <td class="field_label">Examination:</td>
              <td class="bold"><span class="blue"><?php echo $examination->getTitle(); ?></span></td>
            </tr>
            <tr>
              <td class="field_label">Applicant Name:</td>
              <td class="bold blue"><?php echo $applicant->lastname . ', ' . $applicant->firstname; ?></td>
            </tr>
            <tr>
              <td class="field_label">Passing Percentage:</td>
              <td class="bold"><?php echo $examination->getPassingPercentage(); ?>%</td>
            </tr>
            <tr>
              <td class="field_label">Status:</td>
              <td class="bold"><?php echo $examination->getStatus(); ?>&nbsp;</td>
            </tr>
            <!--<tr>
              <td class="field_label">Schedule Date:</td>
              <td class="bold"><?php echo Date::convertDateIntIntoDateString($examination->getScheduleDate()); ?> <i class="icon-calendar icon-fade"></i></td>
            </tr>-->
          </table>
    </div>
    <div class="clear"></div>
</div>
</div>
</div>
</div>
<div class="exam_direction"><span class="ui-icon ui-icon-info float-left vertical-middle"></span><strong>Direction:</strong> You are not allowed to use  any other aids on this part of the exam. When you are finished with this part of the exam, you may press done button and give advise from your Supervisor.</div>
<div class="exam_remaining_time btn btn-success">
	<small>Time Remaining:</small>
	<strong><span id="h"><?php echo $t['hour']; ?></span>:<span id="m"><?php echo $t['minute']; ?></span>:<span id="s"><?php echo $t['second']; ?></span></strong>
</div>
<br />
<div id="form_main" class="inner_form" style="width:90%; margin:0 auto;">
<form id="applicant_examination_form" name="applicant_examination_form" method="post" onsubmit="$.unblockUI();" action="<?php echo url('examination/_finish_answering_examination'); ?>">
<input type="hidden" name="applicant_examination_id" value="<?php echo $applicant_examination_id; ?>">
<input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">

<?php 
$numbering=0;
foreach($q as $key=>$question) {
$numbering++;
	if($question->type=='choices') {
		include 'include/choices.php';
	}elseif($question->type=='essay') {
		include 'include/essay.php';
	}elseif($question->type=='blank') {
		include 'include/blank.php';
	}
}
?>

<div align="center"><button class="blue_button" type="submit"  name="button" id="button"><i class="icon-ok icon-white"></i> Done</button></div>
</form>
</div>


<div id="alternate_submit_wrapper" style="display:none;">Thank you for answering the exam! Click <a href="javascript:void(0);" onclick="$('#applicant_examination_form').submit();">here</a> to continue.</div>
