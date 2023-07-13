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
<input type="hidden" name="id" value="<?php echo $e->id; ?>" />
<input type="hidden" name="examination_id" value="<?php echo $e->exam_id; ?>" />
<input type="hidden" name="order_by" value="<?php echo $e->order_by; ?>" />

  <table class="table_form" width="365" border="0" cellpadding="3" cellspacing="3">
  	 <tr>
      <td width="57" align="right" valign="top">Question:</td>
      <td valign="top"><textarea name="question" id="question" cols="45" class="validate[required]" rows="5"><?php echo $e->question; ?></textarea></td>
    </tr>
    <tr>
      <td width="57" align="right" valign="top">Type:</td>
      <td valign="top"><select onchange="javascript:loadQuickChoicesForm();" class="validate[required]" name="type" id="type">
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
      <td width="57" align="right" valign="top">Answer:</td>
      <td width="356" valign="top"><textarea name="answer" id="answer" cols="45" rows="5"><?php echo $e->answer; ?></textarea></td>
    </tr>
    <tr>

      <td colspan="2" align="left" valign="top">&nbsp;
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td valign="top"><input type="submit" name="button" id="button" value="Update" /></td>
    </tr>
  </table>
</form>
