<h2 class="field_title"><?php echo $title; ?></h2>
<script>
$("#exam_form").validationEngine({scroll:false});
$('#exam_form').ajaxForm({
	success:function(o) {
		
		if(o==0) {
			dialogOkBox('Invalid Exam Code',{dialog_id: '#summary_dialog',ok_url: 'examination'});	
		}else if(o==-1) {
			dialogOkBox('Exam Code Already Done',{dialog_id: '#summary_dialog',ok_url: 'examination'});

		}else {
			$.post(base_url + 'examination/_get_examination_summary',{applicant_examination_id:o},
			function(summary){
				dialogOkBox(summary,{dialog_id: '#summary_dialog',width:450,height:250,icon: 'no-icon',ok_url: 'examination/start_exam?examination='+o});			
			});
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Checking...');	
	
	}
});
</script>
<form id="exam_form" name="exam_form" method="post" action="<?php echo url('examination/_verify_exam_code'); ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>" />
  Exam Code: 
  <input type="text" class="validate[required] text-input text" name="exam_code" id="exam_code" />
  <input type="submit" class="curve blue_button" name="button" id="button" value="Take Exam" />
</form>
<div id="summary_dialog"></div>