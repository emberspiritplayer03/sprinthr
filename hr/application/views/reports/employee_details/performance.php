<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Performance</h2>
<div id="performance_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">Name</th>
          <th width="150" align="left" valign="middle" scope="col">Date</th>
          <th width="109" align="left" valign="middle" scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($performance as $key=>$e) { ?>
        <tr>
        <?php if($can_manage) { ?>
        <?php if($e->status=='pending') { ?>
          <td align="left" valign="top"><a href="<?php echo url('performance/performance_details?performance='.Utilities::encrypt($e->id)); ?>"><?php echo $e->performance_title; ?></a></td>
          <?php }else { ?>
		  <td align="left" valign="top"><a href="<?php echo url('performance/performance_summary?performance='.Utilities::encrypt($e->id)); ?>" ><?php echo $e->performance_title; ?></a></td>
		  <?php } ?>
        <?php } else {echo $e->performance_title;} ?>
          <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->created_date); ?></td>
          <td align="left" valign="top"><?php echo $e->status; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="3" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>