<table id="box-table-a" class="formtable" summary="Schedule" style="margin:0px">
  <thead>
    <tr>
      <th width="60" scope="col">Schedule</th>
      <th width="75" scope="col">Effectivity Date</th>
      <th width="75" scope="col">Working Days</th>
      <th width="64" scope="col">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($schedule_groups as $schedule_group):?>
  <?php
  	$schedules = G_Schedule_Finder::findAllByScheduleGroup($schedule_group);
	$schedule_string = G_Schedule_Helper::showSchedules($schedules);
	$style = "";
	$is_default = false;
	if ($schedule_group->isDefault()) {
		$is_default = true;
		$style = "style='font-weight:bold'";
	}	  	
  ?>
  	<tr>
    <td><?php if ($schedule_group->isDefault()):?><div class="ui-icon ui-icon-info info float-left" title="This is the default schedule. All employees without assigned schedule will be using this default schedule."></div><?php endif;?>&nbsp;<a <?php echo $style;?> href="<?php echo url('schedule/show_schedule?id='. $schedule_group->getPublicId());?>"><?php echo $schedule_group->getName();?></a></td>
    <td><?php echo $schedule_group->getEffectivityDate();?></td>
    <td>
		<?php echo $schedule_string;?>
   	</td>
          <td>
              <a class="link_option" href="javascript:void(0)" onclick="javascript:editWeeklyScheduleFromList('<?php echo $schedule_group->getPublicId();?>')" title="Edit"><i class="icon-edit"><span class="tooltip" title="Edit"></span></i> Edit</a>
              <a class="float-left link_option" href="javascript:void(0)" onclick="javascript:deleteScheduleList('<?php echo $schedule_group->getPublicId();?>')" title="Delete this schedule">
<i class="icon-trash">
<span class="tooltip" original-title="Delete Schedule"></span>
</i>
Delete
</a>
          </td>
    </tr>
  <?php endforeach;?> 
  </tbody>
</table>

<script language="javascript">		
$('.tooltip').tipsy({gravity: 's'});
$('.info').tipsy({gravity: 's'});
</script>