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
    $(".btn-remove-group-schedule").click(function(){
      var eid  = $(this).attr("data-index");
      var geid = $("#geid").val();
      removeGroupSchedule(geid, eid);
    });
    $(".btn-copy-default-restday").click(function(){
      var eid = $("#geid").val();
      copyDefaultRestdayToGroup(eid);
    });
  });
</script>
<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" value="<?php echo $query;?>" placeholder="Type employee, department or section name" />
    <button id="employee_search_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search</button>
    </div>
</form>
</div>

<div style="float:left">
  <h2 class="field_title blue" style="font-size:22px;">Group / Department Schedule : <?php echo $group_details['title']; ?></h2>
</div>
<div class="clear"></div>
<hr />
<table style="border:0px">
<tr>
    <td width="60%">
        <span style="font-size:18px; font-weight:bold">Weekly Schedules</span> <?php echo $btn_assign_new_schedule; ?>
        <input type="hidden" id="geid" value="<?php echo $group_details['id']; ?>" />
        <table id="box-table-a" class="formtable" summary="Schedule" style="margin:0px">
          <thead>
            <tr>
              <th width="80" scope="col">Schedule</th>              
              <th width="40" scope="col">Effectivity Date</th>
              <th width="350" scope="col">Working Days</th>
              <th width="150" scope="col">Breaktime</th>
              <th width="10" scope="col"></th>
            </tr>
          </thead>
          <tbody>
          <?php if( !empty($schedules) ){ ?>
            <?php foreach($schedules as $schedule){ ?>
            <?php 
              $group_schedule_id = $schedule['schedule_group_id'];
              $schedule_id       = $schedule['schedule_id'];
              $schedule_name     = $schedule['schedule_name'];
              $working_days      = $schedule['working_days'];
              $breaktime         = $schedule['breaktime'];
              $public_id         = $schedule['public_id']; 
              $date_start        = $schedule['date_start'];
              $time_in_out       = $schedule['time_in'] . " - " . $schedule['time_out'];
            ?>
              <tr>
                <td>
                  <a href="<?php echo url("new_schedule/show_schedule?id={$public_id}"); ?>"><?php echo $schedule_name ?></a>  
                </td>
                <td><?php echo Tools::convertDateFormat($date_start );?></td>
                <td><div class="item-detail-styled"><i class="icon-time icon-fade vertical-middle"></i><b><?php echo $working_days; ?> (<?php echo $time_in_out; ?>)</b></div></td>
                <td><div class="item-detail-styled"><?php echo $breaktime; ?></div></td>
                <td><a title="Remove" class="btn btn-small btn-remove-group-schedule" data-index="<?php echo $group_schedule_id; ?>" href="javascript:void(0);"><i class="icon icon-remove"></i></a></td>
              </tr>
            <?php } ?>
          <?php }else{ ?>
              <tr>
                  <td colspan="3"><i><center>- No Weekly Schedules -</center></i></td>                    
              </tr>
          <?php } ?>
          </tbody>
        </table>
    </td>
</tr>
</table>
<div class="restday-calendar">
  <div style="margin-top: 10px;">
    <span style="font-size:18px; font-weight:bold">Rest Days</span> (Click day to add/remove Rest Day)
    <a class="pull-right btn btn-copy-default-restday" href="javascript:void(0);">Copy default restday</a>
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

