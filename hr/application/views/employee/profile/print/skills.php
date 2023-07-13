<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Education</h2>
<div id="skill_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">Skill</th>
          <th width="150" align="left" valign="middle" scope="col">Years Experience</th>
         
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($skills as $key=>$e) { ?>
        <tr>
          <td align="left" valign="top"><?php echo $e->skill; ?></td>
          <td align="left" valign="top"><?php echo $e->years_experience; ?></td>

        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="3"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>