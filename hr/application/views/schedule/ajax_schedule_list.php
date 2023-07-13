<table class="formtable" summary="Schedule" style="margin:0px">
  <thead>
    <tr>
      <th width="60" scope="col">Schedule</th>
      <th width="75" scope="col">Working Days</th>
      <th width="64" scope="col">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($schedules as $schedule_name => $schedule):?>
  	<tr>
    <td><a <?php echo $style;?> href="<?php echo url('schedule/show_schedule?name='. $schedule_name);?>"><?php echo $schedule_name;?></a></td>
    <td>
    	<table class="no_border" width="100%">
			<?php foreach ($schedule as $s):?>
              <?php
              $style = "";
              $is_default = false;
              if ($s->isDefault()) {
                  $is_default = true;
                  $style = "style='font-weight:bold'";
              }	  
           ?>
            <tr>
            <td <?php echo $style;?>><?php echo $s->getWorkingDays();?></td>
            <td><?php echo Tools::timeFormat($s->getTimeIn());?> - <?php echo Tools::timeFormat($s->getTimeOut());?></td>
            </tr>        
        <?php endforeach;?> 
    	</table>
    	</td>
          <td>
              <a class="ui-icon ui-icon-pencil tooltip" href="javascript:void(0)" onclick="javascript:editScheduleFromList(<?php echo $s->getId();?>)" style="float:right" title="Edit"></a>
              <?php if ($is_default):?>
                <div class="ui-icon ui-icon-info info" style="float:right" title="This is the default schedule. All employees without assigned schedule will be using this default schedule."></div>
              <?php endif;?>      
          </td>
    </tr>
  <?php endforeach;?> 
  </tbody>
</table>

<script language="javascript">		
$('.tooltip').tipsy({gravity: 's'});
$('.info').tipsy({gravity: 's'});
</script>