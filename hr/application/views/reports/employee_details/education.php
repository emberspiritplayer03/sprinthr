<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Education</h2>
<div id="education_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="top" scope="col">Institute</th>
          <th width="150" align="left" valign="top" scope="col">Course</th>
          <th width="109" align="left" valign="top" scope="col">Year</th>
          <th width="109" align="left" valign="top" scope="col">GPA Score</th>
          <th width="109" align="left" valign="top" scope="col">Attainment</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($education as $key=>$e) { ?>
        <tr>
          <td align="left" valign="middle"><?php echo $e->institute; ?></td>
          <td align="left" valign="middle"><?php echo $e->course; ?></td>
          <td align="left" valign="middle"><?php echo $e->year; ?></td>
          <td align="left" valign="middle"><?php echo $e->gpa_score; ?></td>
          <td align="left" valign="middle"><?php echo $e->attainment; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="5" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>