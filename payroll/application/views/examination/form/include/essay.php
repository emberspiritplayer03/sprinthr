<div id="question_type_essay">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php echo $numbering; ?>&nbsp;<?php echo htmlentities($question->question); ?>&nbsp;</td>
  </tr>
  <tr>
    <td width="25%" align="right" valign="top">Answer:</td>
    <td width="75%"><textarea class="validate[required]" name="answer_<?php echo $question->id; ?>" id="answer_<?php echo $question->id; ?>" cols="45" rows="5"></textarea></td>
  </tr>
</table>
</div>
