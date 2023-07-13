<div id="employee_search_container">
<div class="employee_basic_search searchcnt" id="search_wrapper">
<form id="attendance_log_form" method="get" action="<?php echo $action;?>">
&nbsp;From:&nbsp;&nbsp;<input type="text" name="from" id="from" class="input-large" style="min-width:auto;" value="<?php echo $from;?>" /> 
&nbsp;To:&nbsp;&nbsp;<input type="text" name="to" id="to" class="input-large" style="min-width:auto;" value="<?php echo $to;?>" />
<button class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search</button>
</form>
</div>
</div>
<table class="formtable" width="100%" border="0" cellpadding="5" cellspacing="0">
  <thead>
  <tr>
    <th width="14%"><strong>Employee Code</strong></th>
    <th width="23%"><strong>Employee Name </strong></th>
    <th width="20%"><strong>Date</strong></th>
    <th width="18%"><strong>Time</strong></th>
    <th width="25%"><strong>Type</strong></th>
  </tr>
  </thead>
  <?php foreach ($logs as $log):?>
  <tr>
    <td><?php echo $log->getEmployeeCode();?></td>
    <td><?php echo $log->getEmployeeName();?></td>
    <td><?php echo $log->getDate();?></td>
    <td><?php echo $log->getTime();?></td>
    <td><?php echo $log->getType();?></td>
  </tr>
  <?php endforeach;?>
</table>

<script>
$("#attendance_log_form #from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#attendance_log_form #to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>