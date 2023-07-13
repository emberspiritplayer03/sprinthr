<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Work Experience</h2>
<div id="work_experience_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="172" align="left" valign="middle" scope="col">Company</th>
          <th width="200" align="left" valign="middle" scope="col">Address</th>
          <th width="129" align="left" valign="middle" scope="col">Position</th>
          <th width="339" align="left" valign="middle" scope="col">Date</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($work_experience as $key=>$e) { ?>
        <tr>
          <td align="left" valign="top"><a href="javascript:void(0);" onclick="javascript:loadWorkExperienceEditForm('<?php echo $e->id; ?>');"><?php echo $e->company; ?></a></td>
          <td align="left" valign="top"><?php echo $e->address; ?></td>
          <td align="left" valign="top"><?php echo $e->job_title; ?></td>
          <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->from_date) . " - " . Date::convertDateIntIntoDateString($e->to_date) ; ?></td>
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