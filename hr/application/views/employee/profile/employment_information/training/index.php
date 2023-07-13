<h2 class="field_title"><?php echo $title_training; ?>
<?php echo $btn_add_training; ?>
</h2>
<div id="training_edit_form_wrapper"></div>
<div id="training_add_form_wrapper" style="display:none"><?php include 'form/training_add.php'; ?></div>
<div id="training_delete_wrapper"></div>
<div id="training_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Description</th>
          <th width="150" scope="col">Location</th>
          <th width="109" scope="col">Date</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($training as $key=>$e) { ?>
        <tr>
          <td>
          <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
          	<a href="javascript:void(0);" onclick="javascript:loadTrainingEditForm('<?php echo $e->id; ?>');"><?php echo $e->description; ?></a>
          <?php } else {echo $e->description;} ?>
          </td>
          <td><?php echo $e->location; ?></td>
          <td><?php echo Date::convertDateIntIntoDateString($e->from_date) . " - " . Date::convertDateIntIntoDateString($e->to_date) ; ?></td>
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