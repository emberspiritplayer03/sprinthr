<h2 class="field_title"><?php echo $title_language; ?>
    <?php echo $btn_add_language; ?>
</h2>
<div id="language_edit_form_wrapper"></div>
<div id="language_add_form_wrapper" style="display:none"><?php include 'form/language_add.php'; ?></div>
<div id="language_delete_wrapper"></div>
<div id="language_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Language</th>
          <th width="150" scope="col">Fluency</th>
          <th width="150" scope="col">Competency</th>
          <th width="150" scope="col">Comments</th>
         
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($languages as $key=>$e) { ?>
        <tr>
          <td>
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
              <a href="javascript:void(0);" onclick="javascript:loadLanguageEditForm('<?php echo $e->id; ?>');"><?php echo $e->language; ?></a>
            <?php }else{ echo $e->language; } ?>
          </td>
          <td><?php echo $e->fluency; ?></td>
          <td><?php echo $e->competency; ?></td>
          <td><?php echo $e->comments; ?></td>

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