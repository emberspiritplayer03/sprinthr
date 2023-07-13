
<?php //if($job_history) { ?>
	<h2 class="field_title"><?php echo $title_job_history; ?>
    <?php echo $btn_add_job_history; ?> 
    </h2>
<div id="job_history_edit_form_wrapper"></div>
<div id="job_history_add_form_wrapper" style="display:none"><?php include 'form/job_history_add.php'; ?></div>
<div id="job_history_delete_wrapper"></div>
<div id="job_history_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Job</th>
          <th width="150" scope="col">Employment Status</th>
          <th width="109" scope="col">Start Date</th>
          <th width="109" scope="col">End Date</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($job_history as $key=>$e) { ?>
        <tr>
          <td>
          <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
          	<a href="javascript:void(0);" onclick="javascript:loadJobHistoryEditForm('<?php echo $e->id; ?>');"><?php echo $e->name; ?></a>
		  <?php } else {echo $e->name;} ?>
          </td>
          <td><?php echo $e->employment_status ?></td>
          <td><?php echo Date::convertDateIntIntoDateString($e->start_date) ; ?></td>
          <td><?php echo ($e->end_date=='')? 'Present' : Date::convertDateIntIntoDateString($e->end_date) ; ?></td>
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
<?php //} ?>