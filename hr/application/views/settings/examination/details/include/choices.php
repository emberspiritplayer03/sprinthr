<table width="100%" border="0" align="left" class="form_table_choices">
     <tr>
        <td><input type="text" name="choice_<?php echo $question_id; ?>" id="choice_<?php echo $question_id; ?>" /> 
          <a class="btn btn-small" href="javascript:void(0);" onclick="javascript:addChoice(<?php echo $question_id; ?>);" title="Add Choice"><i class="icon-plus"></i> Add Choice</a></td>
      </tr>
    <?php 
    $e = G_Exam_Question_Finder::findById($question_id);
    $choices = G_Exam_Choices_Finder::findByQuestionId($question_id);
    $num=0;
    foreach($choices as $key=>$value) {
    ?>
      <tr class="list_choices">
        <td style="min-height:22px;" onmouseout="javascript:hideChoiceDelete(<?php echo $value->id; ?>);" onmouseover="javascript:displayChoiceDelete(<?php echo $value->id; ?>);">
         <?php echo Tools::change_to_letters($num); ?>)&nbsp;<?php echo htmlentities($value->choices); ?> 
          <label id="option_<?php echo $value->id; ?>" class="delete_choice_nav" style="float:right;"> 
         <?php if(strtolower($e->answer)!=strtolower($value->choices)) { ?>
        <a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:loadChoiceDeleteDialog(<?php echo $value->id; ?>,<?php echo $e->id; ?>);" title="Delete"><span class="delete"></span></a>
        <?php } else {
            echo "<font color='gray'>Unable to delete</font>";	
        } ?>
        <?php if($num!=0) { ?>
          <a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:moveChoiceUp(<?php echo $value->id; ?>,<?php echo $e->id; ?>)" title="Move Up"><i class="icon-arrow-up"></i></a>
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