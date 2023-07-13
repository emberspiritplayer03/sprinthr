<script>

$("#kpi_edit_form"+<?php echo $e->id; ?>).validationEngine({scroll:false});
$("#kpi_edit_form"+<?php echo $e->id; ?>).ajaxForm({
	success:function(o) {
		var o = o;
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			loadExamQuestions(<?php echo $e->exam_id; ?>);
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});


</script>test
<div id="form_main" class="popup_form">
<form class="question_edit_form" id="question_edit_form_<?php echo $e->id; ?>" name="form1" method="post" action="<?php echo url('settings/_edit_question'); ?>">
<input type="hidden" name="id" value="<?php echo $e->id; ?>" />
<input type="hidden" name="examination_id" value="<?php echo $e->exam_id; ?>" />
<input type="hidden" name="order_by" value="<?php echo $e->order_by; ?>" />
  <div id="form_default">
    <table width="100%">
        <tr>
            <td class="field_label">Title:</td>
            <td><input name="title" type="text" id="title" value="<?php echo $e->title; ?>" /></td>
        </tr>
        <tr>
            <td class="field_label">Description:</td>
            <td><textarea name="description" id="description"><?php echo $e->description; ?></textarea></td>
        </tr>
    </table>
  </div>
  <div id="form_default" class="form_action_section">
  	<table width="100%">
    	<tr>
          <td class="field_label">&nbsp;</td>
          <td><input type="submit" class="blue_button" name="button" id="button" value="Update" /></td>
        </tr>
    </table>
  </div>
</form>
</div>
