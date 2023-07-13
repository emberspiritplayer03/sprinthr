<div id="branch_history_edit_form_wrapper"></div>
<div id="branch_history_add_form_wrapper" style="display:none">
<?php 
include 'form/branch_history_add.php';
?>
</div>
<div id="branch_history_delete_wrapper"></div>
<a id="branch_history_add_button_wrapper" href="javascript:loadBranchHistoryAddForm();">Add History</a>
<div id="branch_history_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Branch Name</th>
          <th width="150" scope="col">Start Date</th>
          <th width="109" scope="col">End Date</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($branch_history as $key=>$e) { ?>
        <tr>
          <td><a href="javascript:void(0);" onclick="javascript:loadBranchHistoryEditForm('<?php echo $e->id; ?>');"><?php echo $e->branch_name; ?></a></td>
          <td><?php echo Date::convertDateIntIntoDateString($e->start_date); ?></td>
          <td><?php echo Date::convertDateIntIntoDateString($e->end_date); ?></td>
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