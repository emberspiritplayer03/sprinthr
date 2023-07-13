<table id="box-table-a" class="formtable" summary="Schedule" style="margin:0px">
  <thead>
    <tr>
      <th width="90" scope="col">Schedule</th>
      <!--<th width="45" scope="col">Grace Period<br />(<i>in minutes</i>)</th>-->
      <th width="75" scope="col">Start Date</th>
      <th width="75" scope="col">End Date</th>
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
    <!--<td style="padding-left:25px;"><?php echo $schedule_group->getGracePeriod(); ?></td>-->
    <td>
        <?php if ($schedule_group->isDefault()):?>
            -
        <?php else:?>
            <?php echo Tools::convertDateFormat($schedule_group->getEffectivityDate());?>
        <?php endif;?>
    </td>
    <td>
        <?php if ($schedule_group->isDefault()):?>
            -
        <?php else:?>
            <?php echo Tools::convertDateFormat($schedule_group->getEndDate());?>
        <?php endif;?>
    </td>
    <td>
		<?php echo $schedule_string;?>	
   	</td>
          <td>
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
              <a class="link_option" href="javascript:void(0)" onclick="javascript:editWeeklyScheduleFromList('<?php echo $schedule_group->getPublicId();?>')" title="Edit"><i class="icon-edit"><span class="tooltip" title="Edit"></span></i> Edit</a>&nbsp;&nbsp;&nbsp;
            <?php } ?>
<?php if (!$schedule_group->isDefault()) { ?>
<!--<a class="float-left link_option" title="Delete this schedule" onclick="javascript:deleteScheduleList('<?php echo $schedule_group->getPublicId();?>')" href="javascript:void(0)"><i class="icon-trash"><span class="tooltip" title="Delete Schedule"></span></i> Delete</a>-->
<?php }?>
          </td>
    </tr>
  <?php endforeach;?> 
  </tbody>
</table>

<script language="javascript">		
$('.tooltip').tipsy({gravity: 's'});
$('.info').tipsy({gravity: 's'});
</script>