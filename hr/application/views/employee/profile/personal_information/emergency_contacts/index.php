<h2 class="field_title"><?php echo $title_emergency_contact; ?>
<?php echo $btn_add_emergency_contacts; ?>
</h2>
<div id="emergency_contacts_edit_form_wrapper"></div>
<div id="emergency_contacts_add_form_wrapper" style="display:none"><?php include 'form/emergency_contacts_add.php'; ?></div>
<div id="emergency_contacts_delete_wrapper"></div>
<div id="emergency_contacts_table_wrapper">
<table width="858" id="hor-minimalist-b" summary="Employee Pay Sheet" border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Person</th>
          <th width="150" scope="col">Relationship</th>
          <th width="109" scope="col">Landline</th>
          <th width="93" scope="col">Mobile</th>
          <th width="119" scope="col">Work Telephone</th>
          <th width="244" scope="col">Address</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($contacts as $key=>$e) { ?>
        <tr>
          <td>
          <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
          	<a href="javascript:void(0);" onclick="javascript:loadEmergencyContactEditForm('<?php echo $e->id; ?>');"><?php echo $e->person; ?></a>
          <?php } else { echo $e->person; }?>
          </td>
          <td><?php echo $e->relationship; ?></td>
          <td><?php echo $e->home_telephone; ?></td>
          <td><?php echo $e->mobile; ?></td>
          <td><?php echo $e->work_telephone; ?></td>
          <td><?php echo $e->address; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="6"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>