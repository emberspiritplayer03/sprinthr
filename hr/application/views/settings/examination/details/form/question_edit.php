<script>

$("#question_edit_form"+<?php echo $e->id; ?>).validationEngine({scroll:false});
$("#question_edit_form"+<?php echo $e->id; ?>).ajaxForm({
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


</script>
<form class="question_edit_form" id="question_edit_form_<?php echo $e->id; ?>" name="form1" method="post" action="<?php echo url('settings/_edit_question'); ?>">
<div class="employee_form" id="form_main">
<input type="hidden" name="id" value="<?php echo $e->id; ?>" />
<input type="hidden" name="examination_id" value="<?php echo $e->exam_id; ?>" />
<input type="hidden" name="order_by" value="<?php echo $e->order_by; ?>" />  
    <div id="form_default">
      <table>
         <tbody>
         <tr>
          <td class="field_label">Question:</td>
          <td><textarea name="question" id="question" class="validate[required]"><?php echo $e->question; ?></textarea></td>
        </tr>
        <tr>
          <td class="field_label">Type:</td>
          <td><select onchange="javascript:loadQuickChoicesForm();" class="validate[required]" name="type" id="type">
			  <?php 
              $essay = '';
              $blank ='';
              $choices= '';
              if($e->type=='essay') {
                    $essay = 'selected="selected"';  	
                }else if($e->type=='blank') {
                    $blank = 'selected="selected"';  	
                }else if($e->type=='choices') {
                    $choices = 'selected="selected"';  	
                }
              ?>
                <option value="">-- select type --</option>
                <option <?php echo $choices; ?> value="choices">Multiple Choice</option>
                <option <?php echo $essay; ?> value="essay">Essay</option>
                <option <?php echo $blank; ?> value="blank">Blank</option>
              </select></td>
        </tr>
        <tr>
          <td class="field_label">Answer:</td>
          <td><textarea name="answer" id="answer"><?php echo $e->answer; ?></textarea></td>
        </tr>
      </tbody></table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table>
            <tbody><tr>
                <td class="field_label">&nbsp;</td>
                <td><input type="submit" class="blue_button" name="button" id="button" value="Update" /></td>
            </tr>
        </tbody></table>
    </div><!-- #form_default.form_action_section -->
</div>
</form>
