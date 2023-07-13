<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

<table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
    <td><strong>Department</strong></td>
    <td><strong>Employee Code</strong></td>
    <td><strong>Lastname</strong></td>
    <td><strong>Firstname </strong></td>
    <td><strong>Middlename</strong></td>
    <td><strong>Extension Name</strong></td>
    <td><strong>Position</strong></td>
    <td><strong>Hired Date</strong></td>
  </tr>
<?php 
$x=0;
foreach ($data as $key => $val) { ?>
  <tr>
    <td><?php echo $val['department']; ?></td>
    <td><?php echo $val['employee_code']; ?></td>
    <td><?php echo $val['lastname']; ?></td>
    <td><?php echo $val['firstname']; ?></td>
    <td><?php echo $val['middlename']; ?></td>
    <td><?php echo $val['extension_name']; ?></td>
    <td><?php echo $val['position']; ?></td>
    <td><?php echo $val['hired_date']; ?></td>
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
    <td>&nbsp;</td>
  </tr>
</table>
<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=employee_list.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
