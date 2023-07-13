<table width="200" border="1">
  <tr>
    <td>Year</td>
    <td>Month</td>
    <td>Applied</td>
    <td>Hired</td>
    <td>Declined</td>
  </tr>
  <?php foreach($data as $key=>$val) { ?>
  <tr>
    <td><?php echo $val['year']; ?></td>
    <td><?php echo date("F", mktime(0, 0, 0, $val['month'], 10)); ?></td>
    <td><?php echo $val['application_submitted']; ?></td>
    <td><?php echo $val['hired']; ?></td>
    <td><?php echo $val['declined']; ?></td>
  </tr>
  <?php } ?>
</table>

<?php
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="APPLICANT_STATISTICS.xls"');
header('Cache-Control: max-age=0');

?>