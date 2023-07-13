<div class="sectionarea">
    <h2 class="field_title"><?php echo $title_leave_available; ?><a class="add_button" id="leave_available_add_button_wrapper" href="javascript:loadLeaveAvailableAddForm();"><strong>+</strong><b>Add Leave</b></a></h2>
    <div id="leave_available_edit_form_wrapper"></div>
    <div id="leave_available_add_form_wrapper" style="display:none"><?php include 'form/leave_available_add.php'; ?></div>
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
            <tr>
              <td><a href="javascript:void(0);" onclick="javascript:loadLeaveAvailableEditForm('<?php echo $e->id; ?>');">
              <?php 
              $l = G_Leave_Finder::findById($e->leave_id);
              echo $l->name; ?>
              </a></td>
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
<div class="sectionarea">    
    <h2 class="field_title"><?php echo $title_request; ?><a class="add_button" id="leave_request_add_button_wrapper" href="javascript:loadLeaveRequestAddForm();"><strong>+</strong><b>Add Leave Request</b></a></h2>
    <div id="leave_request_edit_form_wrapper"></div>
    <div id="leave_request_add_form_wrapper" style="display:none"><?php include 'form/leave_request_add.php'; ?></div>
    <div id="leave_request_delete_wrapper"></div>
    <div id="leave_request_table_wrapper">
    <table width="1052" id="hor-minimalist-b"  border="0">
          <thead>
            <tr>
              <th width="186" scope="col">Request</th>
              <th width="191" scope="col">Date Applied</th>
              <th width="249" scope="col">Effectivity Date</th>
              <th width="240" scope="col">Days</th>
              <th width="164" scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $ctr = 0;
           foreach($request as $key=>$e) { ?>
            <tr>
              <td><a href="javascript:void(0);" onclick="javascript:loadLeaveRequestEditForm('<?php echo $e->id; ?>');">
              <?php  
            $l = G_Leave_Finder::findById($e->leave_id);
              echo $l->name;
              ?></a></td>
              <td><?php echo $e->date_applied; ?></td>
              <td><?php echo $e->date_start . " - " . $e->date_end; ?></td>
              <td><?php 
			  $d =  Date::get_day_diff($e->date_start,$e->date_end);
			  $d['days'] +=1;
			  echo $d['days']; ?> day(s)</td>
              <td><?php echo $e->is_approved; ?></td>
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
</div>