<h2 class="field_title"><?php echo $title_work_experience; ?>
<?php echo $btn_add_work_experience;?>
</h2>
<div id="work_experience_edit_form_wrapper"></div>
<div id="work_experience_add_form_wrapper" style="display:none"><?php include 'form/work_experience_add.php'; ?></div>
<div id="work_experience_delete_wrapper"></div>
<div id="work_experience_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="172" scope="col">Company</th>
          <th width="200" scope="col">Address</th>
          <th width="129" scope="col">Position</th>
          <th width="339" scope="col">Date</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($work_experience as $key=>$e) { ?>
        <tr>
          <td>
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
              <a href="javascript:void(0);" onclick="javascript:loadWorkExperienceEditForm('<?php echo $e->id; ?>');"><?php echo $e->company; ?></a>
            <?php }else { echo $e->company; } ?>
            </td>
          <td><?php echo $e->address; ?></td>
          <td><?php echo $e->job_title; ?></td>
          <td><?php echo Date::convertDateIntIntoDateString($e->from_date) . " - " . Date::convertDateIntIntoDateString($e->to_date) ; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="4"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>