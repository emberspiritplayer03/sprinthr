<div id="question_type_choices" class="question_list <?php //if($arr_xml['question_'.$numbering]['result']=='correct') {correct_answer ?><?php //} else {wrong_answer ?><?php //}?>">
	<h3 class="exam_question"><?php echo $numbering; ?>.&nbsp;<?php echo htmlentities($arr_xml['question_'.$numbering]['question']); ?></h3>
	<div class="exam_inner">
        <div class="float-left answer_icon qlist_rw_icon">
            <div class="exam_qlist_rightwrong">
                <?php 
                if($arr_xml['question_'.$numbering]['result']=='correct') { ?>
                <img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/check.png" border="0" />
                
                <?php } else { ?>
                <img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/cross.png" border="0" />
                <?php }?>
                <input type="hidden" name="answer_<?php echo $arr_xml['question_'.$numbering]['id']; ?>" value="<?php echo htmlentities($arr_xml['question_'.$numbering]['user_answer']); ?>" />
            </div>&nbsp;
        </div>
        <div class="float-left exam_answer">
            <div class="float-left qlist_label"><strong>Correct Answer:</strong></div>
            <div class="float-left qlist_value bold"><?php echo htmlentities($arr_xml['question_'.$numbering]['answer']); ?></div>
            <div class="clearleft"></div>
            <div class="exam_separator"></div>
            <div class="float-left qlist_label"><strong>Answer:</strong></div>
            <div class="float-left qlist_value qlist_useranswer blue"><?php echo htmlentities($arr_xml['question_'.$numbering]['user_answer']); ?></div>
            <div class="clearleft"></div>
        </div>
        
        <div class="clear"></div>
    </div>
</div>