<div id="employee_list">
  <table class="formtable" id="box-table-a" style="margin:0px">
    <thead>
    	<tr>
        	<th width="50"><strong>Employee #</strong></th>
            <th><strong>Employee Name</strong></th>
            <th><strong>Weekly Schedule</strong></th>
            <th><strong>Changed Schedule</strong></th>
            <th>Rest Day</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($employees as $row=>$val):?>
      <?php $e = G_Employee_Finder::findById($val['employee_id']); ?>
        <tr>
          <td width="150"><?php echo $e->getEmployeeCode();?></td>
          <td class="bold"><?php echo $e->getLastname();?>, <?php echo $e->getFirstname();?></td>
          <td>
          	<div id="schedule_<?php echo $e->getId();?>">
			<?php 
				$schedules = G_Schedule_Helper::getCurrentEmployeeSchedule($e);
				echo G_Schedule_Helper::showSchedules($schedules);
			?>
            </div>
          </td>
          <td>
          <div id="specific_<?php echo $e->getId();?>">
          <?php
		  		$specifics = G_Schedule_Specific_Helper::getEmployeeLastMonthUntilNextMonthSchedules($e);
				
		  ?>
          </div>
          <?php 
		  	if ($specifics) {
				echo G_Schedule_Specific_Helper::showSchedules($specifics);
			} else {
				echo '<i>No changed schedules</i>';
			}
		  ?>
          </td>
          <td valign="top">

          <div id="restday_<?php echo $e->getId();?>">
          <?php
		  		$specifics = G_Restday_Helper::getEmployeeLastMonthUntilNextMonthSchedules($e);
				
		  ?>
          </div>
          <?php 
		  	if ($specifics) {
				echo G_Restday_Helper::showSchedules($specifics);
			} else {
				echo '<i>No changed schedules</i>';
			}
		  ?>
          </td>          
        </tr>
      <?php endforeach;?> 
    </tbody>
  </table>
</div>