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
<a id="question_add_button_wrapper" href="javascript:loadQuestionAddForm();">Add Question</a>
<div id="question_table_wrapper">
<?php include 'include/question_table.php'; ?>
</div>