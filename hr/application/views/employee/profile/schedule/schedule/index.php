<h2 class="field_title"><?php echo $title_work_schedule; ?></h2>
<div id="schedule_edit_form_wrapper"></div>
<div id="schedule_add_form_wrapper" style="display:none">

</div>
<div id="schedule_delete_wrapper"></div>
<div id="schedule_table_wrapper">

<table class="formtable" id="hor-minimalist-b" style="margin:0px">
  <thead>
    <tr>
      <th><strong>Weekly Schedule</strong></th>
      <th><strong>Changed Schedule</strong></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><div id="schedule_<?php echo $employee_id;?>">
        <?php 				
				echo $schedule;
			?>
      </div>
        <!--<div><a onclick="javascript:createAndAssignWeeklySchedule('<?php echo $employee_id;?>')" class="link_option" href="javascript:void(0)" title="Assign New Schedule">Assign New Schedule</a></div>-->
        </td>
      <td><div id="specific_<?php echo $employee_id;?>">
        <?php
			echo $specific;
		  ?>
      </div>
        <!--<div><a onclick="javascript:createSpecificSchedule('<?php echo $employee_id;?>')" class="link_option" href="javascript:void(0)" title="Add Schedule">Add Schedule</a></div>-->
        </td>
    </tr>
  </tbody>
</table>
</div>