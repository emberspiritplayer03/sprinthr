
<?php //if($project_site_history) { ?>
	<h2 class="field_title"><?php echo $title_project_site_history; ?>
    <?php echo $btn_add_project_site_history; ?>
    </h2>
<div id="project_site_history_edit_form_wrapper"></div>
<div id="project_site_history_add_form_wrapper" style="display:none"><?php include 'form/project_site_history_add.php'; ?></div>
<div id="project_site_history_delete_wrapper"></div>
<div id="project_site_history_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Project Site</th>
          <th width="109" scope="col">Start Date</th>
          <th width="109" scope="col">End Date</th>
         <!-- <th width="109" scope="col">Employee Status</th>
          <th width="109" scope="col">Status Date</th>-->
        </tr>
      </thead>
      <tbody>
      <?php
			 $ctr = 0;
	

	   foreach($project_history as $key => $value){?>
        <tr>
          <td>

          <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
          	<a href="javascript:void(0);" onclick="javascript:loadProjectSiteHistoryEditForm('<?php echo $value['psh_id']; ?>');"><?php echo $value['project_name']; ?></a>
		  <?php } else {echo $value['project_name'];}  ?>
          </td>
          <td><?php echo Date::convertDateIntIntoDateString($value['start_date']) ; ?></td>
          <td><?php 
                 echo ($value['end_date'] == '')? 'Present' : Date::convertDateIntIntoDateString($value['end_date']) ;
                    ?>
          </td>
         <!-- <td><?php echo $value['employee_status'] ; ?></td>
          <td><?php echo ($value['status_date'] == '')? '' : Date::convertDateIntIntoDateString($value['status_date']) ; ?></td>-->
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
