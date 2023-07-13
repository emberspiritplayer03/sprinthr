<form id="attendance_log_form" method="get" action="<?php echo $action;?>">
From: 
<input type="text" name="from" id="from" value="<?php echo $from;?>" /> 
To: 
<input type="text" name="to" id="to" value="<?php echo $to;?>" />
<input type="submit" value="Search" />
</form>
<br /><br />
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