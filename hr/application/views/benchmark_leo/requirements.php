<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Requirements</h2>
<div id="requirements_table_wrapper">
<table width="100%" id="hor-minimalist-b" class="formtable">
 <thead>
    <tr>
      <th align="left" valign="top"></th>
      <th align="left" valign="middle">Title</th>
    </tr>
  </thead>
  <tbody>
  <?php 
  $ctr = 0;
   foreach($requirements as $key=>$e) { 
       foreach($e as $key=>$val) {
   ?>
   <?php if($can_manage) { ?> 
    <tr >
      <td width="20" align="left" valign="top"></td>
      <td align="left" valign="top" onmouseover="javascript:displayDelete('<?php echo $key; ?>');" onmouseout="javascript:hideDelete('<?php echo $key; ?>');"><?php echo Tools::friendlyTitle($key); ?><label class="delete_requirement_nav" id="<?php echo $key; ?>" > <a class="delete_link" href="javascript:loadRequirementsDeleteDialog('<?php echo $key; ?>');"><span class="delete"></span>delete</a></label></td>
    </tr>
   <?php }else { ?>
   <tr >
      <td width="20" align="left" valign="top"></td>
      <td align="left" valign="top"><?php echo Tools::friendlyTitle($key); ?></td>
    </tr>
   <?php } ?>
   <?php 
   $ctr++;
       }
    if($e) {
     ?>
    <?php if($can_manage) { ?> 
    <tr class="form_action_section">
      <td width="20" align="left" valign="top">&nbsp;</td>
      <td align="left" valign="top" class="action_section"><input class="blue_button" type="submit" value="Update" /></td>
    </tr>
    <?php } ?>
     <?php 
    }
   }

  if($ctr==0) { ?>
      <tr>
      <td colspan="2" align="center" valign="middle"><center><i>No Record(s) Found</i></center></td>
    </tr> 
    <?php }  ?>
  </tbody>
</table>
</div>