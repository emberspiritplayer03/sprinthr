<table width="100%" border="0" cellpadding="0" cellspacing="0" class="formtable">
  <thead>
  <tr>
    <th width="49">Month</th>
    <th width="129">Application Submitted</th>
    <th width="137">From Last Month</th>
    <th width="44">Hired</th>
    <th width="69">Declined</th>
    <th width="65">Pending</th>
  </tr>
  </thead>
  <?php 
  $total_last_month =0;
  foreach($total_applicant as $key=>$applicant) { 
  $total_sum = $applicant['hired']+$applicant['declined'];
  $total_pending = $applicant['application_submitted']+$total_last_month;
  $pending = $total_pending -$total_sum;
  if($pending < 0){$pending = 0;}

  ?>
  <tr>
    <td><strong><?php echo date("F", mktime(0, 0, 0, $applicant['month'], 10)); ?></strong></td>
    <td><?php echo $applicant['application_submitted']; ?></td>
    <td><?php echo $total_last_month; ?></td>
    <td><?php echo $applicant['hired']; ?></td>
    <td><?php echo $applicant['declined']; ?></td>
    <td><?php echo $pending ?></td>
  </tr>
  <?php 
  $total_last_month = ($pending!=0)? $pending : 0 ;
  } ?>
</table>
