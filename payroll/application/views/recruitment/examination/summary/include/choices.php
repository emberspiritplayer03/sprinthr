<div id="question_type_choices" class="question_list <?php if($arr_xml['question_'.$numbering]['result']=='correct') { ?>correct_answer<?php } else { ?>wrong_answer<?php }?>">
<table class="no_border">
  <tr>
    <td colspan="3"><h3 class="exam_question"><?php echo $numbering; ?>.&nbsp;<?php echo htmlentities($arr_xml['question_'.$numbering]['question']); ?></h3></td>
  </tr>
  <tr>
    <td rowspan="2" class="qlist_rw_icon">
    <div class="exam_qlist_rightwrong">
		<?php 
        if($arr_xml['question_'.$numbering]['result']=='correct') { ?>
        <img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/check.png" border="0" />
        
        <?php } else { ?>
        <img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/cross.png" border="0" />
        <?php }?>
        <input type="hidden" name="answer_<?php echo $arr_xml['question_'.$numbering]['id']; ?>" value="<?php echo htmlentities($arr_xml['question_'.$numbering]['user_answer']); ?>" />
    </div>
    </td>
    <td class="qlist_label"><strong>Correct Answer:</strong></td>
    <td class="qlist_value"><?php echo htmlentities($arr_xml['question_'.$numbering]['answer']); ?></td>
  </tr>
  <tr>
  	<td class="qlist_label"><strong>Your Answer:</strong></td>
    <td class="qlist_value qlist_useranswer"><?php echo htmlentities($arr_xml['question_'.$numbering]['user_answer']); ?></td>
  </tr>
</table>
</div>