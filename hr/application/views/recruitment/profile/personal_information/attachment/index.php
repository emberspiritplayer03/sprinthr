<h2 class="field_title"><?php echo $title; ?><a class="add_button" id="attachment_add_button_wrapper" href="javascript:loadAttachmentAddForm();"><strong>+</strong><b>Add Attachment</b></a></h2>
<div id="attachment_edit_form_wrapper"></div>
<div id="attachment_add_form_wrapper" style="display:none">
<?php 
include 'form/attachment_add.php';
?>
</div>
<div id="attachment_delete_wrapper"></div>
<div id="attachment_table_wrapper">
<table width="600" id="hor-minimalist-b" summary="Employee Pay Sheet" border="0">
      <thead>
        <tr>
          <th width="118" scope="col">Name</th>
          <th width="241" scope="col">Description</th>
          <th colspan="2" align="center" scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
      <?php if($attached_resume_exist == 1) { ?>
        <tr>
          <td><a href="javascript:void(0);" onclick="javascript:loadAttachmentEditForm('<?php echo $e->id; ?>');"><?php echo $e->name; ?></a>Resume</td>
          <td>Resume submit by applicant</td>
          <td width="128"><a href="<?php echo BASE_FOLDER . 'files/applicant/resume/' .  $attached_resume; ?>" target="_blank" class="blue_button small_button">View Attachment</a></td>
          <td width="95"></td>
        </tr>
		<?php } ?>      	  
      <?php $ctr = 0; ?>
	   <?php foreach($attachment as $key=>$e) { ?>
        <tr>
          <td><a href="javascript:void(0);" onclick="javascript:loadAttachmentEditForm('<?php echo $e->id; ?>');"><?php echo $e->name; ?></a></td>
          <td><a href="javascript:void(0);" onclick="javascript:loadAttachmentEditForm('<?php echo $e->id; ?>');"><?php echo $e->description; ?></a></td>
          <td width="128"><a href="<?php echo FILES_FOLDER.$e->filename; ?>" target="_blank" class="blue_button small_button">View Attachment</a></td>
          <td width="95"><a class="blue_button small_button" onclick="javascript:loadAttachmentEditForm('<?php echo $e->id; ?>');" href="javascript:void(0);">Edit</a></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="4"><center>
          <i> No Other Record(s) Found</i>
          </center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>