<?php include('includes/employee_summary.php'); ?>
<h3 class="section_title"><?php echo $title_emergency_contacts; ?></h3>
<div id="emergency_contacts_table_wrapper">
<table width="858" id="hor-minimalist-b" summary="Employee Pay Sheet" border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">Person</th>
          <th width="150" align="left" valign="middle" scope="col">Relationship</th>
          <th width="109" align="left" valign="middle" scope="col">Landline</th>
          <th width="93" align="left" valign="middle" scope="col">Mobile</th>
          <th width="119" align="left" valign="middle" scope="col">Work Telephone</th>
          <th width="244" align="left" valign="middle" scope="col">Address</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($contacts as $key=>$e) { ?>
        <tr>
          <td align="left" valign="top">
          <?php if($can_manage) { ?>
          	<a href="javascript:void(0);" onclick="javascript:loadEmergencyContactEditForm('<?php echo $e->id; ?>');"><?php echo $e->person; ?></a>
          <?php } else { $e->person; }?>
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