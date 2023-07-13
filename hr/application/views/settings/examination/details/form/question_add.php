<script>
$(function() {
$("#question_add_form").validationEngine({scroll:false});
$('#question_add_form').ajaxForm({
	success:function(o) {
		
		//if(o.success==1) {
			dialogOkBox('Successfully Updated',{});
			loadExamQuestions(<?php echo $details->id; ?>);
			
		//}else {
			//dialogOkBox(o.message,{});		
			//dialogOkBox(o,{});	
		//}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

});
</script>
<h2>Add Question</h2>
<form id="question_add_form" name="question_add_form" method="post" action="<?php echo url('settings/_update_question'); ?>">
<div class="employee_form" id="form_main">
<input type="hidden" name="examination_id" value="<?php echo $details->id; ?>" />
    <div id="form_default">
      <table>
         <tbody>
         <tr>
          <td class="field_label">Question:</td>
          <td><textarea class="validate[required]" name="question" id="question"><?php echo $details->name; ?></textarea></td>
        </tr>
        <tr>
          <td class="field_label">Answer:</td>
          <td><textarea class="validate[required]" name="answer" id="answer"><?php echo $details->answer; ?></textarea></td>
        </tr>
        <tr>
          <td class="field_label">Type:</td>
          <td><select onchange="javascript:loadQuickChoicesForm();" class="validate[required]" name="type" id="type">
        <option value="">-- select type --</option>
        <option value="choices">Multiple Choice</option>
        <option value="essay">Essay</option>
        <option value="blank">Blank</option>
      </select>
      	<table id="quick_choices_table" width="100%" border="1" style="display:none;">
        <tr>
          <td colspan="2" align="center"><strong>*Please provide correct answer SAME with the answer above</strong></td>
          </tr>
        <tr>
          <td style="vertical-align:middle;" width="15%" align="right">&nbsp;&nbsp;Choice:</td>
          <td width="85%">a) <input name="choice1" type="text" id="choice1" size="40" /></td>
        </tr>
        <tr>
          <td style="vertical-align:middle;" align="right">&nbsp;&nbsp;Choice:</td>
          <td>b) <input name="choice2" type="text" id="choice2" size="40" /></td>
        </tr>
        <tr>
          <td style="vertical-align:middle;" align="right">&nbsp;&nbsp;Choice:</td>
          <td>c) <input name="choice3" type="text" id="choice3" size="40" /></td>
        </tr>
        <tr>
          <td style="vertical-align:middle;" align="right">&nbsp;&nbsp;Choice:</td>
          <td>d) <input name="choice4" type="text" id="choice4" size="40" /> </td>
        </tr>
      </table>
      </td>
        </tr>
      </tbody></table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table>
            <tbody><tr>
                <td class="field_label">&nbsp;</td>
                <td><input type="submit" name="button" class="blue_button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadQuestionTable();">Cancel</a></td>
            </tr>
        </tbody></table>
    </div><!-- #form_default.form_action_section -->
</div>
</form>