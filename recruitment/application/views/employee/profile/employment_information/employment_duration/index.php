<div class="sectionarea">
    <h2 class="field_title"><?php echo $title_duration; ?><a class="add_button" id="duration_add_button_wrapper" href="javascript:loadDurationAddForm();"><strong>+</strong><b>Add Duration</b></a></h2>
    <div id="duration_edit_form_wrapper"></div>
    <div id="duration_add_form_wrapper" style="display:none">
    <?php 
    include 'form/duration_add.php';
    ?>
    </div>
    <div id="duration_delete_wrapper"></div>
    <div id="duration_table_wrapper">
    <table width="778">
          <thead>
            <tr>
              <th width="170" scope="col">From </th>
              <th width="200" scope="col">To</th>
              <th width="99" scope="col">Status</th>
              <th width="289" scope="col">Attachment</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $ctr = 0;
           foreach($durations as $key=>$e) { ?>
            <tr>
              <td><a href="javascript:void(0);" onclick="javascript:loadDurationEditForm('<?php echo $e->id; ?>');"><?php echo Date::convertDateIntIntoDateString($e->start_date); ?></a></td>
              <td><?php echo Date::convertDateIntIntoDateString($e->end_date); ?></td>
              <td><?php 
			  $contract_status = ($e->is_done==0)? 'Present' : 'Expired' ;
			  echo $contract_status; ?></td>
              <td><?php if($e->attachment) { ?>
                <a class="blue_button small_button" target="_blank"  href="<?php echo FILES_FOLDER.$e->attachment; ?>"><?php echo $e->attachment; ?></a>
                <?php } ?>
  &nbsp; </td>
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
</div>