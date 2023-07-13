<h2 class="field_title"><?php echo $title_compensation_history; ?>
<?php
//$employee_id=1;
//$module = 'compensation';
//$action = 'Add';
		
//Utilities::verifyAccessRights($employee_id,$module,$action);

 ?>
<a class="add_button" id="compensation_history_add_button_wrapper" href="javascript:loadCompensationHistoryAddForm();"><strong>+</strong><b>Add Compensation</b></a></h2>
<div id="compensation_history_edit_form_wrapper"></div>
<div id="compensation_history_add_form_wrapper" style="display:none"><?php include 'form/compensation_history_add.php'; ?></div>
<div id="compensation_history_delete_wrapper"></div>
<div id="compensation_history_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="117" scope="col">Type</th>
          <th width="150" scope="col">Basic Salary</th>
          <th width="109" scope="col">Start Date</th>
          <th width="109" scope="col">End Date</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	   foreach($compensation_history as $key=>$e) { ?>
        <tr>
          <td><a href="javascript:void(0);" onclick="javascript:loadCompensationHistoryEditForm('<?php echo $e->id; ?>');" ><?php echo Tools::friendlyTitle($e->type); ?></a></td>
          <td><?php echo number_format($e->basic_salary,2); ?></td>
          <td><?php echo Date::convertDateIntIntoDateString($e->start_date) ; ?></td>
          <td><?php echo ($e->end_date=='')? 'Present' : Date::convertDateIntIntoDateString($e->end_date) ; ?></td>
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