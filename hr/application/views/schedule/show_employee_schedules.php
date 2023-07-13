<style>
.cal-today {
	font-weight:bold;
	//background-color:#00FFFF;
	text-align:center;
}
.cal-table {
	width:100%;
	background-color:#EFEFEF;
	border:1px solid #CCCCCC;
	border-collapse:collapse;
}
.cal-table .cal-month, .cal-year {
	color:black;
}
.cal-table tbody {
	background-color:#FFFFFF;
}
.cal-table td {
	border:1px solid #CCCCCC;
}
.cal-day {
	color:black;
	text-align:center;
}
.cal-table thead {
	text-align:center;
	background-color:#EFEFEF;
}
.cal-day-title {
   text-align:center;
}
.cal-table .cal-title {
   text-align:center;
}
.cal-week {
}
.cal-has-event {
	background-color:black;
	text-align:center;
    color:white;
}
ul.rd-calendar-ul{list-style: none;}
ul.rd-calendar-ul li{display: inline-block;width: 45%;margin:10px;height:288px;}
</style>
<script>
  $(function(){   
    $(".btn-copy-default-restday-employee").click(function(){
      var eid = $("#eid").val();      
      copyDefaultRestdayToEmployee(eid);
    });
  });
</script>
<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" value="<?php echo $query;?>" placeholder="Type employee, department or section name" />
    <button id="employee_search_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search</button>
    </div>
</form>
</div><!-- #employee_search_container -->

<div style="float:left">
<h2 class="field_title blue" style="font-size:22px;"><?php echo $name;?>: <?php echo date('F', mktime(0, 0, 0, $show_month, 1, $show_year));?> - <?php echo $show_year;?></h2>
</div>

<div style="float:right">
<form method="get">
Show Schedule:
<input type="hidden" name="eid" id="eid" value="<?php echo $encrypted_employee_id;?>" />
<input type="hidden" name="hash" value="<?php echo $hash;?>" />
<select name="month">
    <?php foreach ($months as $key => $month):?>
    <option <?php echo (($key+1) == $show_month) ? "selected='selected'" : '' ;?> value="<?php echo ($key+1);?>"><?php echo $month;?></option>
    <?php endforeach;?>
</select>
<select name="year">
    <?php for($x = $start_year; $x <= date("Y"); $x++){ ?>
      <option <?php echo $selected_year == $x ? "selected='selected'" : ''; ?> ><?php echo $x;?></option>
    <?php } ?>    
</select>
<input type="submit" value="Go" />
</form>
</div>

<br><br><br>
<table style="border:0px">
<tr>
    <td width="60%">
        <span style="font-size:18px; font-weight:bold">Weekly Schedules</span> <?php echo $btn_assign_new_schedule; ?>
        <table id="box-table-a" class="formtable" summary="Schedule" style="margin:0px">
          <thead>
            <tr>
              <th width="70" scope="col">Schedule</th>
              <!--<th width="45" scope="col">Grace Period<br />(<i>in minutes</i>)</th>-->
              <th width="30" scope="col">Effectivity Date</th>
              <th width="150" scope="col">Working Days</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($schedule_groups):?>
            <?php foreach ($schedule_groups as $schedule_group):?>
            <?php
            	$schedules = $schedule_group->getSchedules();
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
              <td><?php echo Tools::convertDateFormat($schedule_group->getEffectivityDate());?></td>
              <td>
          		<?php echo $schedule_string;?>
             	</td>
              </tr>
            <?php endforeach;?>
          <?php else:?>
                <tr>
                    <td colspan="3"><i><center>- No Weekly Schedules -</center></i></td>
                </tr>
          <?php endif;?>
          </tbody>
        </table>
    </td>
    <td>
        <span style="font-size:18px; font-weight:bold">Change Schedules</span> <?php echo $btn_add_change_schedule; ?>
        <table id="box-table-a" class="formtable" summary="Schedule" style="margin:0px">
          <thead>
            <tr>
              <th width="170" scope="col">Date</th>
              <th width="150" scope="col">Time In/Out</th>
              <th width="10" ></th>
            </tr>
          </thead>
          <tbody>
          <?php
          if ($changed_schedules):?>
            <?php foreach ($changed_schedules as $cs):?>
            	<tr>
                  <td><?php echo G_Schedule_Specific_Helper::showDateString($cs->getDateStart(), $cs->getDateEnd());?></td>
                  <td><?php echo Tools::timeFormat($cs->getTimeIn());?> - <?php echo Tools::timeFormat($cs->getTimeOut());?></td>
                  <td>
                    <?php if($permission_action == G_Sprint_Modules::PERMISSION_02) { ?>
                      <a style="float:left" class="link_option" href="javascript:void(0)" onclick="javascript:editSpecificSchedule(<?php echo $cs->getId();?>)" title="Edit">Edit</a> <a style="float:right" class="link_option" href="javascript:void(0)" onclick="javascript:deleteSpecificSchedule(<?php echo $cs->getId();?>)" title="Delete">Delete</a>
                    <?php } ?>
                  </td>
              </tr>
            <?php endforeach;?>
          <?php else:?>
                <tr>
                    <td colspan="3"><i><center>- No Change Schedules -</center></i></td>
                </tr>
          <?php endif;?>
          </tbody>
        </table>        
    </td>
</tr>
</table>
<div class="restday-calendar">
  <div style="margin-top: 10px;">
    <span style="font-size:18px; font-weight:bold">Rest Days</span> (Click day to add/remove Rest Day)
    <a class="pull-right btn btn-copy-default-restday-employee" href="javascript:void(0);">Copy default restday</a>
  </div>
  <div class="clear"></div>
  <ul class="rd-calendar-ul">
  <?php echo $calendar;?>
  </ul>
</div>

<script language="javascript">
$('.tooltip').tipsy({gravity: 's'});
$('.info').tipsy({gravity: 's'});
</script>

