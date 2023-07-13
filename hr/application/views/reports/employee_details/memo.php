<?php include('includes/employee_summary.php'); ?>

<h2 class="field_title">Memo</h2>
<div id="memo_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="147" align="left" valign="middle" scope="col">Title</th>
          <th width="202" align="left" valign="middle" scope="col">Memo</th>          
          <th width="122" align="left" valign="middle" scope="col">Date Created</th>
          <th width="129" align="left" valign="middle" scope="col">Created By</th>
          <th width="236" align="left" valign="middle" scope="col">Attachment</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($memo as $key=>$e) { ?>
        <tr>
          <td align="left" valign="top"><?php echo $e->memo; ?></td>          
          <td align="left" valign="top"><?php echo Date::convertDateIntIntoDateString($e->date_created); ?></td>
          <td align="left" valign="top"><?php echo $e->created_by; ?></td>
          <td align="left" valign="top">
          <?php if($e->attachment) { ?>
          <a class="blue_button small_button" target="_blank" href="<?php echo FILES_FOLDER. $e->attachment; ?>">View Attachment<?php //echo $e->attachment; ?></a>
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