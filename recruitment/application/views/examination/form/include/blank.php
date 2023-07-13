<div id="question_type_blank" class="question_list">
    <h3 class="exam_question"><?php echo $numbering; ?>.&nbsp;<?php echo htmlentities($question->question); ?>&nbsp;</h3>
    <div class="exam_inner">
    	<div class="float-left answer_icon qlist_rw_icon" style="height:30px;">&nbsp;</div>
        <div class="float-left qlist_answering_space">
        	<strong>Answer:</strong>
        </div>
        <div class="float-left">
        	<input type="text" class="validate[required] input-xxlarge" name="answer_<?php echo $question->id; ?>" id="answer_<?php echo $question->id; ?>">
        </div>
        <div class="clear"></div>
    </div>
</div>