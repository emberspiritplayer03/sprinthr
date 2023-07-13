<div id="question_type_choices" class="question_list">
	<h3 class="exam_question"><?php echo $numbering; ?>.&nbsp;<?php echo htmlentities($question->question); ?>&nbsp;</h3>
    <div class="exam_inner">
    	<div class="float-left answer_icon qlist_rw_icon">&nbsp;</div>
        <div class="float-left qlist_value">
        	<table width="100%">
              <tr><td>
              <?php 
              $choices = G_Exam_Choices_Finder::findByQuestionId($question->id);
            
              if($choices) {
                $ctr=0;
              foreach($choices as $key=>$choice) {               
              ?>
              	<div class="col_1_2"><div class="link_option"><label class="radio hover"><input class="validate[required]"  type="radio" name="answer_<?php echo $question->id; ?>" id="<?php echo $question->id; ?>" value="<?php echo htmlentities($choice->choices); ?>"><?php echo strtoupper(Tools::change_to_letters($ctr)); ?>)&nbsp;&nbsp;&nbsp;<?php echo htmlentities($choice->choices); ?></label></div></div>
              <?php $ctr++;
              }} ?>
              <div class="clear"></div>
              </td></tr>
            </table>
        </div>
        <div class="clear"></div>
    </div>
</div>