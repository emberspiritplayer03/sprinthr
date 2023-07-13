<div id="import_leave_wrapper" style="display:none" >
<?php include 'ajax_import_leave.php'; ?>
</div>

<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
<!--<form id="search_employee" method="get" action="<?php echo $action;?>">-->
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" />
    <button id="create_schedule_submit" class="blue_button" type="submit">Search Employee</button>
    </div>
</form>
</div><!-- #employee_search_container -->
<div style="float:left">
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importTimesheet()">Import Timesheet</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importScheduleSpecific()">Import Changed Schedule</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importLeave()">Import Leave</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importOvertime()">Import OT</a>
</div>
<div style="float:right"><a href="<?php echo url('attendance/attendance_logs');?>">View Attendance Logs</a></div>

<br /><br />
<table class="formtable" width="100%">
<thead>
  <tr>
    <th width="160"><strong>Period</strong></th>
    <!--<th><strong>Errors</strong></th>-->
    <th><strong>Action</strong></th>
    <th></th>
    <th></th>
  </tr>
</thead>
  <?php foreach ($periods as $period):?>
<?php
	$from = $period['start'];
	$to = $period['end'];
?>
  <tr>
    <td width="160" class="payslip_period"><strong><a href="<?php echo url('attendance/manage?from='. $period['start'] .'&to='. $period['end']);?>"><?php echo Tools::getGmtDate('M j', strtotime($period['start']));?> - <?php echo Tools::getGmtDate('M j', strtotime($period['end']));?></a></strong></td>    
    <!--<td><div id="dropholder"><a class="red_button small_button" href="#">5 No IN or OUT</a> <a class="red_button small_button" href="#">10 No Schedules</a></div></td>-->
    <!--<td><div id="dropholder"><a class="dropbutton" href="<?php echo url('attendance/manage?from='. $period['start'] .'&to='. $period['end']);?>">Download Timesheet</a></div></td>-->
    <td><div id="dropholder"><a class="dropbutton" href="<?php echo url('attendance/manage?from='. $period['start'] .'&to='. $period['end']);?>">View List</a></div>
    </td>
    <td>
  <div id="dropholder">
  <a class="dropbutton" onclick="downloadTimesheet('<?php echo $from;?>', '<?php echo $to;?>')">Download Summary</a>
  </div>
</td>
<td>
<div id="dropholder">
	<a class="dropbutton" href="<?php echo url('attendance/download_timesheet_breakdown?from='. $from .'&to='. $to);?>">Download Breakdown</a>
</div>
</td>
  </tr>
  <?php endforeach;?>
</table>