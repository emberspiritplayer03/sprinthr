<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="5"><?php echo $title;?></td>
  </tr>
  <tr>
    <td width="14%"><strong>Employee Code</strong></td>
    <td width="23%"><strong>Employee Name </strong></td>
    <td width="20%"><strong>Date</strong></td>
    <td width="18%"><strong>Time</strong></td>
    <td width="25%"><strong>Type</strong></td>
    <td width="25%"><strong>Device No.</strong></td>
  </tr>
  <?php foreach ($logs as $log):?>

  <?php

      $remarks = $log->getRemarks();
        //get device no.
        $remarks2 = explode(':', $remarks);
        $machine_no = $remarks2[1];

        $device_info = G_Attendance_Log_Finder::findDevice($machine_no);

        if($device_info){
          //utilities::displayArray($device_info->device_name);exit();
          $device_name = $machine_no.'-'. $device_info->device_name;
        }

  ?>

  <tr>
    <td><?php echo $log->getEmployeeCode();?></td>
    <td><?php echo $employee_names[$log->getEmployeeCode()];?></td>
    <td><?php echo $log->getDate();?></td>
    <td><?php echo $log->getTime();?></td>
    <td><?php echo $log->getType();?></td>

    <td><?php  echo $device_name;   ?></td>
  </tr>
  <?php endforeach;?>
</table>
<?php
header("Content-type: application/x-msexcel;charset:UTF-8");
header("Content-Disposition: attachment; filename=attendance_logs_{$from}-{$to}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>