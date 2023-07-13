<div id="import_leave_wrapper" style="display:none" >
<?php include 'ajax_import_leave.php'; ?>
</div>

<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
<!--<form id="search_employee" method="get" action="<?php echo $action;?>">-->
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" />
    <button id="create_schedule_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search Employee</button>&nbsp;&nbsp;
        <label class="checkbox inline">    	
            <input type="checkbox" checked="checked" id="s_exact" name="s_exact" />Exact match to entered query
        </label>
    </div>
</form>
</div><!-- #employee_search_container -->
<div style="float:left; margin-top:-6px;">
<strong>Import:</strong>&nbsp;
<select onchange="javascript:attendanceAction(this.value);" id="attendance_action">
<option selected="selected">- Please select -</option>
<option value="timesheet">Timesheet</option>
<option value="change_schedule">Changed Schedule</option>
<option value="leave">Leave</option>
<option value="ot">OT</option>
<option value="rest_day">Rest Day</option>
</select>
<!--<a class="gray_button" href="javascript:void(0)" onclick="javascript:importTimesheet()"><i class="icon-arrow-left"></i> Import Timesheet</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importScheduleSpecific()"><i class="icon-arrow-left"></i> Import Changed Schedule</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importLeave()"><i class="icon-arrow-left"></i> Import Leave</a>
<a style="display:none;" class="gray_button" href="javascript:void(0)" onclick="javascript:importOvertime()"><i class="icon-arrow-left"></i> Import OT</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importRestday()"><i class="icon-arrow-left"></i> Import Rest Day</a>-->
</div>
<div style="float:right"><a class="gray_button" href="<?php echo url('project_site/face_recognition_timesheet_generator');?>"><i class="icon-file"></i> Attendance File Generator</a><a class="gray_button" href="<?php echo url('project_site/attendance_logs');?>"><i class="icon-align-justify"></i> View Attendance Logs</a></div>

<br /><br />
<table class="formtable" width="100%">
<thead>
  <tr>
    <th width="200"><strong>Period</strong></th>
    <!--<th><strong>Errors</strong></th>-->
    <th width="220"><strong>Action</strong></th>
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
    <td class="payslip_period"><strong><a href="<?php echo url('project_site/manage?from='. $period['start'] .'&to='. $period['end'] . '&hpid=' . Utilities::encrypt($period['id']));?>"><?php echo Tools::getGmtDate('M j', strtotime($period['start']));?> - <?php echo Tools::getGmtDate('M j', strtotime($period['end']));?></a></strong></td>
    <!--<td><div id="dropholder"><a class="red_button small_button" href="#">5 No IN or OUT</a> <a class="red_button small_button" href="#">10 No Schedules</a></div></td>-->
    <!--<td><div id="dropholder"><a class="dropbutton" href="<?php echo url('project_site/manage?from='. $period['start'] .'&to='. $period['end']);?>">Download Timesheet</a></div></td>-->
    <td class="vertical-middle">
    <div id="dropholder" style="width:50%">
    <a class="dropbutton" href="<?php echo url('project_site/manage?from='. $period['start'] .'&to='. $period['end'] . '&hpid=' . Utilities::encrypt($period['id']));?>"><i class="icon-zoom-in icon-fade"></i> View List</a>
    </div>
   <!-- <div id="dropholder" style="width:50%">
    <a class="dropbutton" href="<?php echo url('project_site/filter_by_range?from='. $period['start'] .'&to='. $period['end'] . '&hpid=' . Utilities::encrypt($period['id']));?>"><i class="icon-zoom-in icon-fade"></i> Filter by Range</a>
    </div>-->
    </td>
    <td class="vertical-middle">
    	<i class="icon-download-alt icon-fade"></i> Download:&nbsp;&nbsp;&nbsp;
    	<a class="btn btn-mini" onclick="downloadTimesheet('<?php echo $from;?>', '<?php echo $to;?>')">Summary</a>&nbsp;&nbsp;
    	<a class="btn btn-mini" href="<?php echo url('project_site/download_timesheet_breakdown?from='. $from .'&to='. $to);?>">Breakdown</a>
    	<a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:filterTimeSheetBreakDown('<?php echo $from; ?>','<?php echo $to; ?>');">Filter Breakdown</a>
    </td>
    <td class="vertical-middle text-right">
    <?php if($period['is_lock'] == G_Cutoff_Period::NO){ ?>
        <div style="margin-right:17px;font-size:13px;">
            <a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:lockPayrollPeriod('<?php echo Utilities::encrypt($period['id']); ?>');"><i class="icon-lock"></i> Lock Period</a>
        </div>
    <?php }else{ ?>
        <div style="margin-right:17px;font-size:13px;">
            <a class="btn disabled active btn-mini" href="#"><i class="icon-lock disabled"></i> Period Locked</a>
        </div>
    <?php } ?>
    </td>
  </tr>
  <?php endforeach;?>
</table>