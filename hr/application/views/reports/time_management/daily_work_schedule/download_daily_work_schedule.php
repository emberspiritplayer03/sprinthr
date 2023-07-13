<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<div id="employee_list">
  <table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
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
		  <?php if($e) { ?>
            <tr>
              <td valign="top" width="150"><?php echo $e->getEmployeeCode();?></td>
              <td valign="top" class="bold"><?php echo $e->getLastname();?>, <?php echo $e->getFirstname();?></td>
              <td valign="top">
                <div id="schedule_<?php echo $e->getId();?>">
                <?php 
                    $schedules = G_Schedule_Helper::getCurrentEmployeeSchedule($e);
                    echo G_Schedule_Helper::showSchedules($schedules);
                ?>
                </div>
              </td>
              <td valign="top">
				  <?php $specifics = G_Schedule_Specific_Helper::getEmployeeLastMonthUntilNextMonthSchedules($e); ?>
                  <?php 
                    if ($specifics) {
                        echo G_Schedule_Specific_Helper::showSchedules($specifics);
                    } else {
                        echo '<i>No changed schedules</i>';
                    }
                  ?>
              </td>
              <td valign="top">
				  <?php $specifics = G_Restday_Helper::getEmployeeLastMonthUntilNextMonthSchedules($e); ?>
                  <?php 
                    if ($specifics) {
                        echo G_Restday_Helper::showSchedules($specifics);
                    } else {
                        echo '<i>No changed schedules</i>';
                    }
                  ?>
              </td>          
            </tr>
          <?php } ?>        
      <?php endforeach;?> 
    </tbody>
  </table>
</div>
<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>