<?php if($job_history) { ?>
<h2 class="field_title">Job History</h2>
<div id="job_history_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
  <thead>
    <tr>
      <th width="117" align="left" valign="middle" scope="col">Job</th>
      <th width="150" align="left" valign="middle" scope="col">Employment Status</th>
      <th width="109" align="left" valign="middle" scope="col">Start Date</th>
      <th width="109" align="left" valign="middle" scope="col">End Date</th>
    </tr>
  </thead>
  <tbody>
  <?php 
  $ctr = 0;
   foreach($job_history as $key=>$e) { ?>
    <tr>
      <td align="left" valign="top"><?php echo $e->name; ?></td>
      <td align="left" valign="top"><?php echo $e->employment_status ?></td>
      <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->start_date) ; ?></td>
      <td align="left" valign="top"><?php echo ($e->end_date=='')? 'Present' : Date::convertDateIntIntoDateString($e->end_date) ; ?></td>
    </tr>
   <?php 
   $ctr++;
   }

  if($ctr==0) { ?>
      <tr>
      <td colspan="4" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
    </tr> 
    <?php }  ?>
  </tbody>
</table>
</div>
<?php } ?>