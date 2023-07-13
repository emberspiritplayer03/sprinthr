<div id="question_type_blank">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php echo $numbering; ?>.&nbsp;<?php echo htmlentities($question->question); ?>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Answer:</td>
    <td><input type="text" class="validate[required]" name="answer_<?php echo $question->id; ?>" id="answer_<?php echo $question->id; ?>"></td>
  </tr>
</table>
</div>