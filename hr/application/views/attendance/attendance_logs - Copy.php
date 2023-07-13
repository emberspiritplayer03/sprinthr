<form id="attendance_log_form" method="get" action="<?php echo $action;?>">
<!--Name <input type="text" name="employees_autocomplete" id="employees_autocomplete" />-->
From: 
<input type="text" name="from" id="from" value="<?php echo $from;?>" /> 
To: 
<input type="text" name="to" id="to" value="<?php echo $to;?>" />
Error: <select name="error_type">
	<option <?php echo ($error_type == '') ? 'selected="selected"' : '' ;?> value="">-</option>
	<option <?php echo ($error_type == 'multiple_swipes') ? 'selected="selected"' : '' ;?> value="multiple_swipes">Multiple Swipes</option>
    <option <?php echo ($error_type == 'no_time_in') ? 'selected="selected"' : '' ;?> value="no_time_in">No Time In</option>
    <option <?php echo ($error_type == 'no_time_out') ? 'selected="selected"' : '' ;?> value="no_time_out">No Time Out</option>
</select>
<input type="submit" value="Search" />
</form>
<br />
<div align="right"><a href="<?php echo url("attendance/attendance_logs?from={$from}&to={$to}&error_type={$error_type}&download=1");?>">Download Result</a></div>
<br />
<table class="formtable" width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td width="14%"><strong>Employee Code</strong></td>
    <td width="23%"><strong>Employee Name </strong></td>
    <td width="20%"><strong>Date</strong></td>
    <td width="18%"><strong>Time</strong></td>
    <td width="25%"><strong>Type</strong></td>
  </tr>
  <?php foreach ($logs as $log):?>
  <tr>
    <td><?php echo $log->getEmployeeCode();?></td>
    <td><?php echo $employee_names[$log->getEmployeeCode()];?></td>
    <td><?php echo $log->getDate();?></td>
    <td><?php echo $log->getTime();?></td>
    <td><?php echo $log->getType();?></td>
  </tr>
  <?php endforeach;?>
</table>

<script>
$("#attendance_log_form #from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#attendance_log_form #to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

/*$('#employees_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
	minLength: 1,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
}}});*/
</script>