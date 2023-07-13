<table width="753" border="1">
  <tr>
    <td width="154"><strong>Job Vacancy</strong></td>
    <td width="222"><strong>Hiring Manager</strong></td>
    <td width="146"><strong>Publication Date</strong></td>
    <td width="203"><strong>Advertisement End</strong></td>
  </tr>
  <?php foreach($data as $key=>$val) { ?>
  <tr>
    <td><?php echo $val['job_vacancy']; ?></td>
    <td><?php echo $val['hiring_manager']; ?></td>
    <td><?php echo $val['publication_date']; ?></td>
    <td><?php echo $val['advertisement_end']; ?></td>
  </tr>
  <?php } ?>
</table>
<?php

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="JOB_ADVERTISEMENT.xls"');
header('Cache-Control: max-age=0');

?>