<?php ob_start();?>
<h3><?php echo $report_title; ?></h3>

<table width="100%" border="1">
  <tr>
    <th>Date Attendance</th>
    <th>Time-In</th>
    <th>Time-Out</th>
    <th>Is Present</th>
  </tr>
  <tr>
  <?php foreach($attendance as $a): ?>
  <?php $t = $a->getTimeSheet(); ?>
    <td width="10%"><?php echo $a->getDate(); ?></td>
    <td><?php echo Tools::convert24To12Hour($t->getTimeIn()); ?></td>
    <td><?php echo Tools::convert24To12Hour($t->getTimeOut()); ?></td>
    <td><?php echo ($a->isPresent() == 1 ? 'Yes' : 'No'); ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<?php
header("Content-type: application/x-msexcel; charset=UTF-16LE"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>