<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">License</h2>
<div id="language_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">License Type</th>
          <th width="150" align="left" valign="middle" scope="col">License Number</th>
          <th width="150" align="left" valign="middle" scope="col">Issued Date</th>
          <th width="150" align="left" valign="middle" scope="col">Expiry Date</th>
          <th width="150" align="left" valign="middle" scope="col">Notes</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($license as $l) { ?>
        <tr>
            <td align="left" valign="top"><?php echo $l->getLicenseType(); ?></td>
            <td align="left" valign="top"><?php echo $l->getLicenseNumber(); ?></td>
            <td align="left" valign="top"><?php echo $l->getIssuedDate(); ?></td>
            <td align="left" valign="top"><?php echo $l->getExpiryDate(); ?></td>
            <td align="left" valign="top"><?php echo $l->getNotes(); ?></td>
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