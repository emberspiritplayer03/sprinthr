<h2 class="field_title"><?php echo $title; ?></h2>
<?php if($_GET['add']=='show') { ?>
<div id="employee_performance_add_form_wrapper">
<?php } else { ?>
<div id="employee_performance_add_form_wrapper"  style="display:none">
<?php 	
}?>
<?php include 'form/performance_add.php'; ?>
</div>

<div class="yui-skin-sam">
  <div id="employee_performance_datatable"></div>
</div>

<script>
load_employee_performance_datatable();
</script>