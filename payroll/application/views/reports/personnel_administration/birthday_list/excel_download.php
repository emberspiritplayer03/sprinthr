<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<table width="949" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
    <td width="158"><strong>Department</strong></td>
    <td width="120"><strong>Employee Code</strong></td>
    <td width="141"><strong>Employee Name</strong></td>
    <td width="162"><strong>Date of Birth</strong></td>
    <td width="92"><strong>Age</strong></td>
    <td width="157"><strong>Position</strong></td>
    <td width="190"><strong>Employment Status</strong></td>
  </tr>
  <?php 
  $x=0;
  foreach($data as $key=>$val) { ?>
  <tr>
    <td><?php echo $val['department']; ?></td>
    <td><?php echo $val['employee_code']; ?></td>
    <td><?php echo $val['employee_name'];?></td>
    <td><?php echo date("m-d-Y",strtotime($val['birthdate'])); ?></td>
    <td><?php echo $age = ($val['age']>0) ? $val['age'] : 0 ; ' Yrs Old'; ?></td>
    <td><?php echo $val['position']; ?></td>
    <td><?php echo $val['employment_status']; ?></td>
  </tr>
  <?php
  $x++;
   } ?>
  <tr>
    <td colspan="3">Total Record(s): <?php echo $x; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=birthday_list.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

