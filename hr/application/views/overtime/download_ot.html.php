<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>

<table width="100%" border="1">
<thead>
	<th>EMPLOYEE ID</th>
    <th>DATE OF OT</th>
    <th>TIME IN</th>
    <th>TIME OUT</th>
    <th>REASON</th>
</thead>
<?php foreach($overtime as $ot): ?>
<?php $employee = G_Employee_Finder::findById($ot->getEmployeeId()); ?>
  <tr>
    <td><?php echo $employee->getEmployeeCode(); ?></td>
    <td><?php echo $ot->getDateStart(); ?></td>
    <td><?php echo  Tools::convert24To12Hour($ot->getTimeIn()); ?></td>
    <td><?php echo  Tools::convert24To12Hour($ot->getTimeOut()); ?></td>
    <td><?php echo $ot->getOvertimeComments(); ?></td>
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