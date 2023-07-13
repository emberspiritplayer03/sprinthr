<div id="import_leave_wrapper" style="display:none" >
<?php include 'ajax_import_leave.php'; ?>
</div>

<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
<!--<form id="search_employee" method="get" action="<?php echo $action;?>">-->
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" />
    <button id="create_schedule_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search Employee</button>
    </div>
</form>
</div><!-- #employee_search_container -->
<div style="float:left; margin-top:-6px;">
<strong>Import:</strong>&nbsp;
<select>
<option selected="selected">- Please select -</option>
<option onclick="javascript:importTimesheet()">Timesheet</option>
<option onclick="javascript:importScheduleSpecific()">Changed Schedule</option>
<option onclick="javascript:importLeave()">Leave</option>
<option onclick="javascript:importOvertime()">OT</option>
<option onclick="javascript:importRestday()">Rest Day</option>
</select>
<!--<a class="gray_button" href="javascript:void(0)" onclick="javascript:importTimesheet()"><i class="icon-arrow-left"></i> Import Timesheet</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importScheduleSpecific()"><i class="icon-arrow-left"></i> Import Changed Schedule</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importLeave()"><i class="icon-arrow-left"></i> Import Leave</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importOvertime()"><i class="icon-arrow-left"></i> Import OT</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importRestday()"><i class="icon-arrow-left"></i> Import Rest Day</a>-->
</div>
<div style="float:right"><a href="<?php echo url('attendance/attendance_logs');?>" class="gray_button"><i class="icon-align-justify"></i> View Attendance Logs</a></div>

<br /><br />
<table class="formtable" width="100%">
<thead>
  <tr>
    <th width="200"><strong>Period</strong></th>
    <!--<th><strong>Errors</strong></th>-->
    <th width="180"><strong>Action</strong></th>
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
    <td class="payslip_period"><strong><a href="<?php echo url('attendance/manage?from='. $period['start'] .'&to='. $period['end']);?>"><?php echo Tools::getGmtDate('M j', strtotime($period['start']));?> - <?php echo Tools::getGmtDate('M j', strtotime($period['end']));?></a></strong></td>    
    <!--<td><div id="dropholder"><a class="red_button small_button" href="#">5 No IN or OUT</a> <a class="red_button small_button" href="#">10 No Schedules</a></div></td>-->
    <!--<td><div id="dropholder"><a class="dropbutton" href="<?php echo url('attendance/manage?from='. $period['start'] .'&to='. $period['end']);?>">Download Timesheet</a></div></td>-->
    <td class="vertical-middle"><div id="dropholder"><a class="dropbutton" href="<?php echo url('attendance/manage?from='. $period['start'] .'&to='. $period['end']);?>"><i class="icon-zoom-in icon-fade"></i> View List</a></div>
    </td>
    <td class="vertical-middle"><i class="icon-download-alt icon-fade"></i> Download Timesheet:&nbsp;&nbsp;&nbsp;<a class="btn btn-mini download" title="Download Summarized Timesheet" onclick="downloadTimesheet('<?php echo $from;?>', '<?php echo $to;?>')">Summarized</a>&nbsp;&nbsp;<a class="btn btn-mini download" title="Download Detailed Timesheet" href="<?php echo url('attendance/download_timesheet_breakdown?from='. $from .'&to='. $to);?>">Detailed</a>
    <td class="vertical-middle text-right"></td>
</td>
  </tr>
  <?php endforeach;?>
</table>

<script language="javascript">		
$('.download').tipsy({gravity: 's'});
</script>