<div id="question_type_choices">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php echo $numbering; ?>.&nbsp;<?php echo htmlentities($question->question); ?>&nbsp;</td>
  </tr>
  <?php 
  $choices = G_Exam_Choices_Finder::findByQuestionId($question->id);

  if($choices) {
	$ctr=0;
  foreach($choices as $key=>$choice) { 
  
  ?>
  <tr>
    <td width="30%" align="right"><?php echo strtoupper(Tools::change_to_letters($ctr)); ?>)
      <input class="validate[required]"  type="radio" name="answer_<?php echo $question->id; ?>" id="<?php echo $question->id; ?>" value="<?php echo htmlentities($choice->choices); ?>"></td>    <td width="70%"><?php echo htmlentities($choice->choices); ?></td>
  </tr>
  <?php $ctr++;
  }} ?>
  <tr>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>