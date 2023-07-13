<h2 class="field_title"><?php echo $title; ?></h2>
<div id="examination_edit_form_wrapper"></div>
<div id="examination_add_form_wrapper" style="display:none">
<?php 
include 'form/examination_add.php';
?>
</div>
<div id="examination_delete_wrapper"></div>

<div id="examination_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="164" scope="col">Title</th>
          <th width="160" scope="col">Date Taken</th>
          <th width="203" scope="col">Passing Percentage</th>
          <th width="153" scope="col">Result</th>
          <th width="156" scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($examination as $key=>$e) { ?>
        <tr>
          <td><a href="<?php echo url('recruitment/examination_summary?examination='.Utilities::encrypt($e->id)); ?>" onclick="javascript:loadExaminationEditForm('<?php echo $e->id; ?>');"><?php echo $e->title; ?>s</a></td>
          <td><?php echo Date::convertDateIntIntoDateString($e->schedule_date); ?></td>
          <td><?php echo$e->passing_percentage; ?></td>
          <td><?php echo$e->result; ?></td>
          <td><?php echo$e->status; ?></td>
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