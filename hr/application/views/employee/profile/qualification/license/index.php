<h2 class="field_title"><?php echo $title_license; ?>
  <?php echo $btn_add_license; ?>
</h2>
<div id="license_edit_form_wrapper"></div>
<div id="license_add_form_wrapper" style="display:none"><?php include 'form/license_add.php'; ?></div>
<div id="license_delete_wrapper"></div>
<div id="license_table_wrapper">
<table width="100%" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th  scope="col">License Type</th>
          <th scope="col">License Number</th>
          <th  scope="col">Issued Date</th>
          <th  scope="col">Expiry Date</th>
         
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($licenses as $key=>$e) { ?>
        <tr>
          <td>
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
              <a href="javascript:void(0);" onclick="javascript:loadLicenseEditForm('<?php echo $e->id; ?>');"><?php echo $e->license_type; ?></a>
            <?php }else { echo $e->license_type; } ?>
          </td>
          <td><?php echo $e->license_number; ?></td>
          <td><?php
		  $issued = ($e->issued_date=='')? '' : Date::convertDateIntIntoDateString($e->issued_date);
		   echo $issued;  ?></td>
          <td><?php 
		  $expiry= ($e->expiry_date=='')? '' : Date::convertDateIntIntoDateString($e->expiry_date);;
		  echo $expiry; ?></td>

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