<div id="question_type_blank" class="question_list answer_list <?php //if($arr_xml['question_'.$numbering]['result']=='correct')correct_answer { ?><?php //} else {wrong_answer ?><?php //}?>">
	<h3 class="exam_question"><?php echo $numbering; ?>.&nbsp;<?php echo htmlentities($arr_xml['question_'.$numbering]['question']); ?></h3>
	<div class="exam_inner">
        <div class="float-left answer_icon qlist_rw_icon">
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
            </div>&nbsp;
        </div>
        <div class="float-left exam_answer">
            <div class="float-left qlist_label"><strong>Correct Answer:</strong></div>
            <div class="float-left qlist_value bold"><?php echo $arr_xml['question_'.$numbering]['answer']; ?></div>
            <div class="clearleft"></div>
            <div class="exam_separator"></div>
            <div class="float-left qlist_label"><strong>Answer:</strong></div>
            <div class="float-left qlist_value qlist_useranswer blue"><?php echo htmlentities($arr_xml['question_'.$numbering]['user_answer']); ?></div>
            <div class="clearleft"></div>
        </div>
        <div class="float-left exam_checking">
			<label class="radio inline btn btn-small btn-success">
            <input onclick="javascript:displayCheck(<?php echo $arr_xml['question_'.$numbering]['id']; ?>);"  <?php echo $correct; ?>  class="validate[required]" type="radio" name="rechecked_<?php echo $arr_xml['question_'.$numbering]['id']; ?>" id="btn_correct_<?php echo $arr_xml['question_'.$numbering]['id']; ?>" value="correct" /><i class="icon-ok icon-white"></i> Correct</label><label class="radio inline btn btn-small btn-danger">
            <input onclick="javascript:displayCross(<?php echo $arr_xml['question_'.$numbering]['id']; ?>);"  <?php echo $wrong; ?>  class="validate[required]" type="radio" name="rechecked_<?php echo $arr_xml['question_'.$numbering]['id']; ?>" id="btn_incorrect_<?php echo $arr_xml['question_'.$numbering]['id']; ?>" value="incorrect" /><i class="icon-remove icon-white"></i> Incorrect</label>
            <input type="hidden" name="answer_<?php echo $arr_xml['question_'.$numbering]['id']; ?>" value="<?php echo $arr_xml['question_'.$numbering]['user_answer']; ?>" />
        </div>        
        <div class="clear"></div>
    </div>
</div>