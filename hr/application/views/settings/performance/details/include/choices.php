<table width="476" border="0" align="left">
     <tr>
        <td width="47">&nbsp;</td>
        <td width="241"><input type="text" name="choice_<?php echo $question_id; ?>" id="choice_<?php echo $question_id; ?>" /> 
          <a href="javascript:void(0);" onclick="javascript:addChoice(<?php echo $question_id; ?>);">Add Choice</a></td>
      </tr>
    <?php 
    $e = G_Exam_Question_Finder::findById($question_id);
    $choices = G_Exam_Choices_Finder::findByQuestionId($question_id);
    $num=0;
    foreach($choices as $key=>$value) {
    ?>
      <tr>
        <td>&nbsp;</td>
        <td onmouseout="javascript:hideChoiceDelete(<?php echo $value->id; ?>);" onmouseover="javascript:displayChoiceDelete(<?php echo $value->id; ?>);">
        <bold><?php echo Tools::change_to_letters($num); ?>)</bold> &nbsp;<?php echo htmlentities($value->choices); ?> 
          <label id="option_<?php echo $value->id; ?>" class="delete_choice_nav" > 
         <?php if(strtolower($e->answer)!=strtolower($value->choices)) { ?>
        	 <a href="javascript:void(0);" onclick="javascript:loadChoiceDeleteDialog(<?php echo $value->id; ?>,<?php echo $e->id; ?>);">Delete</a>
        <?php } else {
            echo "<font color='gray'>Unable to delete</font>";	
        } ?>
        <?php if($num!=0) { ?>
          <a href="javascript:void(0);" onclick="javascript:moveChoiceUp(<?php echo $value->id; ?>,<?php echo $e->id; ?>)">Move Up</a>
          <?php } ?>
        </label>
        </td>
      </tr>
     <?php
     $num++;
      } ?>
</table>
<script>
$(".delete_choice_nav").hide();
</script>