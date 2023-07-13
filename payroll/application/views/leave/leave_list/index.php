<div id="leave_type_wrapper_form" style="display:none" >
<?php include 'form/add_leave_type.php'; ?>
</div>

<div id="import_employee_leave_wrapper" style="display:none">
<?php include 'form/import_employee_leave.php'; ?>
</div>


<div class="yui-skin-sam">
  <div id="employee_leave_datatable"></div>
</div>
<div id="employee_leave_wrapper"></div>
<div id="confirmation"></div>

<script>
load_employee_leave_datatable();
</script>
