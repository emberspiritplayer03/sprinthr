<?php include('includes/employee_summary.php'); ?>

<div class="sectionarea">
    <h2 class="field_title">Contract</h2>
    <div id="duration_table_wrapper">
    <table width="778">
          <thead>
            <tr>
              <th width="170" align="left" valign="middle" scope="col">From </th>
              <th width="200" align="left" valign="middle" scope="col">To</th>
              <th width="99" align="left" valign="middle" scope="col">Status</th>
              <th width="289" align="left" valign="middle" scope="col">Attachment</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $ctr = 0;
           foreach($durations as $key=>$e) { ?>
            <tr>
              <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->start_date); ?></td>
              <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->end_date); ?></td>
              <td align="left" valign="top"><?php 
			  $contract_status = ($e->is_done==0)? 'Present' : 'Expired' ;
			  echo $contract_status; ?></td>
              <td align="left" valign="top">
			  <?php if($e->attachment) { ?>
                <a class="blue_button small_button" target="_blank"  href="<?php echo FILES_FOLDER.$e->attachment; ?>"></a>
                <?php } ?>
  &nbsp; </td>
            </tr>
           <?php 
           $ctr++;
           }
    
          if($ctr==0) { ?>
              <tr>
              <td colspan="4"><center><i>No Record(s) Found</i></center></td>
            </tr> 
            <?php }  ?>
          </tbody>
        </table>
    </div>
</div>