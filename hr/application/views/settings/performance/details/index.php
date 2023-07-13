<h2 class="field_title"><?php echo $title; ?></h2>
<div id="performance_details_edit_form_wrapper">
<?php 
include 'form/performance_details_edit.php';
?>
</div>
<div id="performance_details_table_wrapper">
<?php 
include 'performance_table.php';
?>
</div>
<div id="kpi_add_form_wrapper" style="display:none">
<?php 
include 'form/kpi_add.php';
?>
</div>
<div id="kpi_delete_wrapper"></div>
<h2 class="field_title"><a class="add_button" id="kpi_add_button_wrapper" href="javascript:loadKpiAddForm();"><strong>+</strong><b>Add Key Performance Indicator</b></a></h2>
<div id="kpi_table_wrapper">
<?php include 'include/kpi_table.php'; ?>
</div>