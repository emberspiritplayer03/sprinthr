<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div>
<h2 class="field_title"><?php echo $title; ?></h2>
<div id="examination_details_edit_form_wrapper">
<?php 
include 'form/examination_details_edit.php';
?>
</div>
<div id="examination_details_table_wrapper">
<?php 
include 'examination_table.php';
?>
</div>
<div id="question_add_form_wrapper" style="display:none">
<?php 
include 'form/question_add.php';
?>
</div>
<div id="question_delete_wrapper"></div>
<h2 class="field_title"><a id="question_add_button_wrapper" href="javascript:loadQuestionAddForm();" class="add_button"><strong>+</strong><b>Add Question</b></a></h2>
<div id="question_table_wrapper">
<?php include 'include/question_table.php'; ?>
</div>