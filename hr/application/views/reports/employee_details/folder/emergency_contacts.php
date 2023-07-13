<h3 class="section_title">Emergency Contacts</h3>
<div id="emergency_contacts_table_wrapper">
<table>
      <thead>
        <tr>
          <th align="left" valign="middle" scope="col">Person</th>
          <th align="left" valign="middle" scope="col">Relationship</th>
          <th align="left" valign="middle" scope="col">Landline</th>
          <th align="left" valign="middle" scope="col">Mobile</th>
          <th align="left" valign="middle" scope="col">Work Telephone</th>
          <th align="left" valign="middle" scope="col">Address</th>
        </tr>
      </thead>
      <tbody>
    <?php 
	  $ctr = 0;
	   foreach($ec_emp as $key=>$e) { ?>
        <tr>
          <td align="left" valign="top">
          <?php echo $e->getPerson(); ?>
          </td>
          <td align="left" valign="top"><?php echo $e->relationship; ?></td>
          <td align="left" valign="top"><?php echo $e->home_telephone; ?></td>
          <td align="left" valign="top"><?php echo $e->mobile; ?></td>
          <td align="left" valign="top"><?php echo $e->work_telephone; ?></td>
          <td align="left" valign="top"><?php echo $e->address; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="6" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>