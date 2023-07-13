<div id="question_type_essay" class="question_list <?php if($arr_xml['question_'.$numbering]['result']=='correct') { ?>correct_answer<?php } else { ?>wrong_answer<?php }?>">
<table class="no_border">
  <tr>
    <td colspan="3"><h3 class="exam_question"><?php echo $numbering; ?>.&nbsp;<?php echo $arr_xml['question_'.$numbering]['question']; ?></h3></td>
  </tr>
  <tr>
    <td rowspan="2" class="qlist_rw_icon">
    <div class="exam_qlist_rightwrong">
        <div id="cross_div_<?php echo $arr_xml['question_'.$numbering]['id']; ?>" style="display:none">
        <img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/cross.png" border="0" />
        </div>
        <div id="check_div_<?php echo $arr_xml['question_'.$numbering]['id']; ?>" style="display:none">
        <img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/check.png" border="0" />
        </div>
        <?php 
        if($arr_xml['question_'.$numbering]['result']=='correct') { 
        ?><script>
        displayCheck(<?php echo $arr_xml['question_'.$numbering]['id']; ?>);
        </script>
        <?php 
        }else { ?>
        <script>
        displayCross(<?php echo $arr_xml['question_'.$numbering]['id']; ?>);
        </script>	
        <?php }?>
    </div>
    </td>
    <td class="qlist_label"><strong>Correct Answer:</strong></td>
    <td class="qlist_value"><?php echo $arr_xml['question_'.$numbering]['answer']; ?></td>
  </tr>
  <tr>
    <td class="qlist_label"><strong>Your Answer:</strong></td>
    <td class="qlist_value qlist_useranswer"><?php echo $arr_xml['question_'.$numbering]['user_answer']; ?></td>
  </tr>
</table>
</div>
