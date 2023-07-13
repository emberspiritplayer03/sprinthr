<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Leave Available</h2>
<div id="leave_available_table_wrapper">
    <table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" align="left" valign="middle" scope="col">Leave Type</th>
          <th width="150" align="left" valign="middle" scope="col">Number of Days Alloted</th>
          <th width="109" align="left" valign="middle" scope="col">Number of Days Available</th>
        </tr>
      </thead>
      <tbody>
      <?php 
      $ctr = 0;
       foreach($availables as $key=>$e) { ?>
       <?php $l = G_Leave_Finder::findById($e->leave_id); ?>
        <tr>
          <td align="left" valign="top">
          <?php if($can_manage) { ?>
            <a href="javascript:void(0);" onclick="javascript:loadLeaveAvailableEditForm('<?php echo $e->id; ?>');"><?php echo $l->name; ?></a>
          <?php }else{echo $l->name;} ?>
          </td>
          <td align="left" valign="top"><?php echo $e->no_of_days_alloted; ?></td>
          <td align="left" valign="top"><?php echo $e->no_of_days_available; ?></td>
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
<div class="sectionarea">    
    <h2 class="field_title">Leave Request</h2>
    <div id="leave_request_table_wrapper">
    <table width="1052" id="hor-minimalist-b"  border="0">
          <thead>
            <tr>
              <th width="186" align="left" valign="top" scope="col">Request</th>
              <th width="191" align="left" valign="top" scope="col">Date Applied</th>
              <th width="249" align="left" valign="top" scope="col">Effectivity Date</th>
              <th width="240" align="left" valign="top" scope="col">Days</th>
              <th width="164" align="left" valign="top" scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $ctr = 0;
           foreach($request as $key=>$e) { ?>
           <?php $l = G_Leave_Finder::findById($e->leave_id); ?>
            <tr>
              <td align="left" valign="middle">
              	<?php if($can_manage) { ?>
              	<a href="javascript:void(0);" onclick="javascript:loadLeaveRequestEditForm('<?php echo $e->id; ?>');"><?php echo $l->name; ?></a>
                <?php }else{echo $l->name;} ?>
              </td>
              <td align="left" valign="middle"><?php echo $e->date_applied; ?></td>
              <td align="left" valign="middle"><?php echo $e->date_start . " - " . $e->date_end; ?></td>
              <td align="left" valign="middle"><?php 
			  $d =  Date::get_day_diff($e->date_start,$e->date_end);
			  $d['days'] +=1;
			  echo $d['days']; ?> day(s)</td>
              <td align="left" valign="middle"><?php echo $e->is_approved; ?></td>
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

