<div class="sectionarea">
    <h2 class="field_title"><?php echo $title_leave_available; ?>
    <?php echo $btn_add_leave;?>
    </h2>
    <div id="leave_available_edit_form_wrapper"></div>
    <div id="leave_available_add_form_wrapper"></div>
    <div id="leave_available_delete_wrapper"></div>
    <div id="leave_available_table_wrapper">
    <table width="858" id="hor-minimalist-b"  border="0">
          <thead>
            <tr>
              <th width="117" scope="col">Leave Type</th>
              <th width="150" scope="col">Number of Days Alloted</th>
              <th width="109" scope="col">Number of Days Available</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $ctr = 0;
           foreach($availables as $key=>$e) { ?>
           <?php $l = G_Leave_Finder::findById($e->leave_id); ?>
            <tr>
              <td>
              <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
              	<a href="javascript:void(0);" onclick="javascript:loadLeaveAvailableEditForm('<?php echo $e->id; ?>');"><?php echo $l->name; ?></a>
              <?php }else{echo $l->name;} ?>
              </td>
              <td><?php echo $e->no_of_days_alloted; ?></td>
              <td><?php echo $e->no_of_days_available; ?></td>
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
</div>