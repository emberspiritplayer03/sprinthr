<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Training</h2>
<div id="training_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">Description</th>
          <th width="150" align="left" valign="middle" scope="col">Location</th>
          <th width="109" align="left" valign="middle" scope="col">Date</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($training as $key=>$e) { ?>
        <tr>
          <td align="left" valign="top">
          <?php if($can_manage) { ?>
          	<a href="javascript:void(0);" onclick="javascript:loadTrainingEditForm('<?php echo $e->id; ?>');"><?php echo $e->description; ?></a>
          <?php } else {echo $e->description;} ?>
          </td>
          <td align="left" valign="top"><?php echo $e->location; ?></td>
          <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->from_date) . " - " . Date::convertDateIntIntoDateString($e->to_date) ; ?></td>
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