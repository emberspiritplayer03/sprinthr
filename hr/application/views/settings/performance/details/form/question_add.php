<script>
$(function() {
$("#question_add_form").validationEngine({scroll:false});
$('#question_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			loadExamQuestions(<?php echo $details->id; ?>);
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

});
</script>
<h2>Add Question</h2>
<div id="form_main" class="employee_form">
<form id="question_add_form" name="form1" method="post" action="<?php echo url('settings/_update_question'); ?>">
<input type="hidden" name="examination_id" value="<?php echo $details->id; ?>" />
	<div id="form_default">
  <table width="100%">
  	 <tr>
      <td width="156" align="right" valign="top">Question:</td>
      <td valign="top"><textarea class="validate[required]" name="question" id="question" cols="45" rows="5"><?php echo $details->name; ?></textarea></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Type:</td>
      <td valign="top"><select onchange="javascript:loadQuickChoicesForm();" class="validate[required]" name="type" id="type">
        <option value="">-- select type --</option>
        <option value="choices">Multiple Choice</option>
        <option value="essay">Essay</option>
        <option value="blank">Blank</option>
      </select></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Answer:</td>
      <td width="241" valign="top"><textarea class="validate[required]" name="answer" id="answer" cols="45" rows="5"><?php echo $details->answer; ?></textarea></td>
    </tr>
    <tr>

      <td colspan="2" align="left" valign="top">
      <table id="quick_choices_table" width="100%" border="1" style="display:none">
        <tr>
          <td colspan="2" align="center">*Please provide correct answer SAME with the answer above</td>
          </tr>
        <tr>
          <td width="40%" align="right">Choice :</td>
          <td width="60%">a) <input name="choice1" type="text" id="choice1" size="40" /></td>
        </tr>
        <tr>
          <td align="right">Choice :</td>
          <td>b) <input name="choice2" type="text" id="choice2" size="40" /></td>
        </tr>
        <tr>
          <td align="right">Choice :</td>
          <td>c) <input name="choice3" type="text" id="choice3" size="40" /></td>
        </tr>
        <tr>
          <td align="right">Choice :</td>
          <td>d) <input name="choice4" type="text" id="choice4" size="40" /> </td>
        </tr>
      </table></td>
    </tr>    
  </table>
  </div>
  <div id="form_default">
  	<table>
        <tr>
          <td align="right" valign="top">&nbsp;</td>
          <td valign="top"><input type="submit" name="button" id="button" value="Add" /> 
            <a href="javascript:void(0);" onclick="javascript:loadQuestionTable();">Cancel</a></td>
        </tr>
    </table>
  </div>
</form>
</div>
