<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Languages</h2>
<div id="language_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">Language</th>
          <th width="150" align="left" valign="middle" scope="col">Fluency</th>
          <th width="150" align="left" valign="middle" scope="col">Competency</th>
          <th width="150" align="left" valign="middle" scope="col">Comments</th>
         
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($languages as $key=>$e) { ?>
        <tr>
          <td align="left" valign="top"><?php echo $e->language; ?></td>
          <td align="left" valign="top"><?php echo $e->fluency; ?></td>
          <td align="left" valign="top"><?php echo $e->competency; ?></td>
          <td align="left" valign="top"><?php echo $e->comments; ?></td>

        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="5"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>