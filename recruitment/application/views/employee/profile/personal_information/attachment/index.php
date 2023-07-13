<h2 class="field_title"><?php echo $title_attachment; ?><a class="add_button" id="attachment_add_button_wrapper" href="javascript:loadAttachmentAddForm();"><strong>+</strong><b>Add Attachment</b></a></h2>
<div id="attachment_edit_form_wrapper"></div>
<div id="attachment_add_form_wrapper" style="display:none"><?php $employee_id;include 'form/attachment_add.php'; ?></div>
<div id="attachment_delete_wrapper"></div>
<div id="attachment_table_wrapper">
<table width="858" id="hor-minimalist-b" summary="Employee Pay Sheet" border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Filename</th>
          <th width="150" scope="col">Description</th>
          <th width="109" scope="col">Date Attached</th>
          <th width="93" scope="col">Size</th>
          <th width="119" scope="col">Type</th>
          <th width="244" scope="col">Added By</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($attachment as $key=>$e) { ?>
        <tr>
          <td><a target="_blank" href="<?php echo FILES_FOLDER.$e->filename; ?>">Download</a> <a href="javascript:void(0);" onclick="javascript:loadAttachmentEditForm('<?php echo $e->id; ?>');"><?php echo $e->filename; ?></a></td>
          <td><?php echo $e->description; ?></td>
          <td><?php echo $e->date_attached; ?></td>
          <td><?php echo $e->size; ?></td>
          <td><?php echo $e->type; ?></td>
          <td><?php echo $e->added_by; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="6"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>