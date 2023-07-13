<script>
$(()=>{
  load_employees_enrolled_to_benefit('<?php echo $eid; ?>');
  load_employees_exclude_to_benefit('<?php echo $eid; ?>');
  $("#back-benefit-btn").click(function(){
    showBenefitsContainer();
  });
});
</script>
<div class="pull-right">
  <a id="back-benefit-btn" class="btn" href="javascript:void(0);"><b>Back</b></a>
</div>
<p style="font-size:12px;">Employees enrolled to <b><?php echo $benefit_name; ?></b></p>

<div class="yui-skin-sam">
    <div id="employees-enrolled-to-benefit-dt"></div>
</div>

<p style="font-size:12px;">Employees exclude to <b><?php echo $benefit_name; ?></b></p>

<div class="table-container">
<table id="dtCompany" class="mws-table mws-datatable thead-title-black">
    <thead>
      <tr>   
        <th>Employee Code</th>
        <th>Employee Name</th>
      </tr>
    </thead>
    <tbody id="employee-container">  
    	<?php foreach($leave_added_employees as $leave_added_employee) {
            $e=G_Employee_Finder::findById($leave_added_employee['employee_id']);
			      $l=G_Leave_Finder::findById($leave_added_employee['leave_id']); 
		        $lc = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId($leave_added_employee['employee_id'], $leave_added_employee['leave_id']);?>
			  <tr>
                  <td><?=$e->employee_code;?></td>
                  <td><?=$e->getName();?></td>
                  <td><?=date('Y-m-d', strtotime($leave_added_employee['added_date']));?></td>
                  <td><?=$l->getName();?></td>
                  <td><?=$leave_added_employee['added_credit'];?></td>
                  <td><?=$lc->no_of_days_available?></td>
              </tr>
       <?php } ?>
    </tbody>	
</table>
</div