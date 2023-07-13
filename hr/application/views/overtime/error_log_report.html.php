<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>

<table width="100%" border="1">
<thead>
    <th>EMPLOYEE CODE</th>
    <th>EMPLOYEE NAME</th>
    <th>DATE ATTENDANCE</th>
    <th>TIME-IN</th>
    <th>TIME OUT</th>
    <th>REMARKS</th>
</thead>
<?php foreach($error_logs as $el): ?>
  <tr>
    <td><?php echo $el->getEmployeeCode(); ?></td>
    <td><?php echo $el->getEmployeeName(); ?></td>
    <td><?php echo date("m/d/y",strtotime($el->getDate())); ?></td>
    <td><?php echo  Tools::convert24To12Hour($el->getTimeIn()); ?></td>
    <td><?php echo  Tools::convert24To12Hour($el->getTimeOut()); ?></td>
    <td><?php echo $el->getMessage(); ?></td>
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