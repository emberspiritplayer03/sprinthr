<h2 class="field_title"><?php echo $title_memo; ?>
<?php echo $btn_add_memo;?>
</h2>
<div id="memo_edit_form_wrapper"></div>
<div id="view_dialog_wrapper"></div>
<div id="memo_add_form_wrapper" style="display:none"><?php include 'form/memo_add.php'; ?></div>
<div id="memo_delete_wrapper"></div>
<div id="memo_table_wrapper">
<table class="formtable" width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="147" scope="col">Title</th>
          <!-- <th width="202" scope="col">Memo</th> -->          
          <th width="122" scope="col">Date Created</th>
          <th width="129" scope="col">Created By</th>
          <th width="236" scope="col">Remarks</th>
          <th width="236" scope="col">Attachment</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;

/*    Utilities::displayArray($memo);*/

	   foreach($memo as $key=>$e) { ?>
        <tr>
          <td>
          	<?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
          		<a href="javascript:void(0);" onclick="javascript:loadMemoEditForm('<?php echo $e->id; ?>');"><?php echo $e->title; ?></a>
            <?php }else{ echo $e->title; } ?>
          </td>
          <!-- <td><?php //echo $e->memo; ?></td> -->          
          <td><?php echo Date::convertDateIntIntoDateString($e->date_created); ?></td>
          <td><?php echo $e->created_by; ?></td>
          <td><?php echo $e->remarks;?></td>
          <td>
          <?php if($e->attachment) { ?>
          <a class="blue_button small_button" target="_blank" href="<?php echo FILES_FOLDER. $e->attachment; ?>"><i class="icon-file icon-white"></i> View Attachment<?php //echo $e->attachment; ?></a>
          <?php }else {
			 echo "No Attachment"; 
			 } ?>
          </td>
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