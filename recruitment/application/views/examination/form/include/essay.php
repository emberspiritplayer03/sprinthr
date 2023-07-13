<div id="question_type_essay" class="question_list">
    <h3 class="exam_question"><?php echo $numbering; ?>.&nbsp;<?php echo htmlentities($question->question); ?>&nbsp;</h3>
    <div class="exam_inner">
    	<div class="float-left answer_icon qlist_rw_icon">&nbsp;</div>
        <div class="float-left qlist_answering_space">
        	<strong>Answer:</strong>
        </div>
        <div class="float-left">
        	<textarea class="validate[required]" name="answer_<?php echo $question->id; ?>" id="answer_<?php echo $question->id; ?>"></textarea>
        </div>
        <div class="clear"></div>
    </div>
</div>