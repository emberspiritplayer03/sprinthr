<h2 class="field_title"><?php echo $title; ?></h2>
<div id="performance_edit_form_wrapper"></div>
<div id="performance_add_form_wrapper" style="display:none">
<?php 
include 'form/performance_add.php';
?>
</div>
<div id="performance_delete_wrapper"></div>
<a id="performance_add_button_wrapper" href="javascript:loadPerformanceAddForm();">Add Performance</a>
<div id="performance_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Name</th>
          <th width="150" scope="col">Date</th>
          <th width="109" scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($performance as $key=>$e) { ?>
        <tr>
          <td><a href="javascript:void(0);" onclick="javascript:loadPerformanceEditForm('<?php echo $e->id; ?>');"><?php echo $e->performance_title; ?></a></td>
          <td><?php echo Date::convertDateIntIntoDateString($e->created_date); ?></td>
          <td><?php echo $e->status; ?></td>
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