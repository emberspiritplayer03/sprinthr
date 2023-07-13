<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>

<table width="100%" border="1">
<thead>
	<th>EMPLOYEE CODE</th>
    <th>LEAVE TYPE</th>
    <th>DATE APPLIED</th>
    <th>FROM</th>
    <th>TO</th>
    <th>IS PAID</th>
    <th>REASON</th>
</thead>
<?php foreach($leave as $l): ?>
<?php 
	$employee 	= G_Employee_Finder::findById($l->getEmployeeId()); 
	$leave_type	= G_Leave_Finder::findById($l->getLeaveId());
?>
  <tr>
    <td><?php echo $employee->getEmployeeCode(); ?></td>
    <td><?php echo $leave_type->getName(); ?></td>
    <td><?php echo $l->getDateApplied(); ?></td>
    <td><?php echo $l->getDateStart(); ?></td>
    <td><?php echo $l->getDateEnd(); ?></td>
    <td><?php echo $l->getIsPaid(); ?></td>
    <td><?php echo $l->getLeaveComments(); ?></td>
  </tr>
<?php endforeach; ?>
</table>


<?php
header("Content-type: application/x-msexcel;charset=UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>