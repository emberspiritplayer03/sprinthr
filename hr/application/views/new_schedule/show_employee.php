
<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" value="<?php echo $query;?>" />
    <button id="employee_search_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search Employee</button>
    </div>
</form>
</div><!-- #employee_search_container -->

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
      <?php foreach ($employees as $e):?>
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
            <div><a onclick="javascript:createAndAssignWeeklySchedule('<?php echo $e->getId();?>')" class="link_option" href="javascript:void(0)" title="Assign New Schedule">Assign New Schedule</a></div>
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
          <div><a onclick="javascript:createSpecificSchedule('<?php echo $e->getId();?>')" class="link_option" href="javascript:void(0)" title="Add Schedule"><strong>+</strong> Add Schedule</a></div>
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
          <!--<div><a onclick="javascript:createRestday('<?php echo $e->getId();?>')" class="link_option" href="javascript:void(0)" title="Add Rest Day"><strong>+</strong> Add Rest Day</a></div>-->
          </td>          
        </tr>
      <?php endforeach;?> 
    </tbody>
  </table>
</div>

