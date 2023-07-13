<h2 class="field_title"><?php echo $title; ?></h2>
<script>
$(function() {
	$("#choose_exam_form").validationEngine({scroll:false});
	$('#choose_exam_form').ajaxForm({
		success:function(o) {
			
			if(o.is_saved == true) {
				window.location.href = base_url + 'examination/start_exam?examination='+o.h_exam_id;
			} else {
				dialogOkBox(o.message,{dialog_id: '#summary_dialog'});
			}
		},
		beforeSubmit:function() {
			showLoadingDialog('Generating Exam...');	
		
		},
		dataType: 'json'
	});
});
</script>
<form id="choose_exam_form" name="choose_exam_form" method="post" action="<?php echo url('examination/create_exam_set'); ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>" />
<input type="hidden" name="h_app_id" value="<?php echo $h_app_id; ?>" />
  <select id="h_exam_id" name="h_exam_id" style="width:150px;" class="validate[required]">
  	<option value=""> -- Choose Exam --</option>
    <?php foreach($examination as $e): ?>
    	<option value="<?php echo Utilities::encrypt($e->getId()); ?>"><?php echo $e->getTitle(); ?></option>
    <?php endforeach; ?>
  </select>
  <input type="submit" class="curve blue_button" name="button" id="button" value="Take Exam" />
</form>
<div id="summary_dialog"></div>