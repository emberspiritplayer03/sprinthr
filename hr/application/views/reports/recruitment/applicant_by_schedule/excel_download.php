<table width="1164" border="1">
  <tr>
    <td width="221"><strong>Date</strong></td>
    <td width="136"><strong>Appointment To</strong></td>
    <td width="115"><strong>Applicant Name</strong></td>
    <td width="147"><strong>Position Applied</strong></td>
    <td width="137"><strong>Description</strong></td>
    <td width="115"><strong>Remarks</strong></td>
    <td width="247"><strong>Date Created</strong></td>
  </tr>
  <?php foreach ($data as $key => $val) { ?>
  <tr>
    <td><?php echo Date::convertDateIntIntoDateString($val['date_time_event']); ?></td>
    <td><?php echo $val['hiring_manager']; ?></td>
    <td><?php echo $val['applicant_name']; ?></td>
    <td><?php echo $val['position_applied']; ?></td>
    <td><?php echo  $GLOBALS['hr']['application_status'][$val['application_status_id']]; ?></td>
    <td><?php echo $val['notes']; ?></td>
    <td><?php echo Date::convertDateIntIntoDateString($val['date_time_created']); ?></td>
  </tr>
  <?php } ?>
</table>
<?php


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="APPLICANT_BY_SCHEDULE.xls"');
header('Cache-Control: max-age=0');

?>