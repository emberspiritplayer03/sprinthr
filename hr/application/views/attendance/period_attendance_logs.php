<div id="import_leave_wrapper" style="display:none" >
<?php include 'ajax_import_leave.php'; ?>
</div>

<table class="formtable" width="100%">
<thead>
  <tr>
    <th width="200"><strong>Period</strong></th>
    <!--<th><strong>Errors</strong></th>-->
    <th width="180"><strong>Action</strong></th>
    <th></th>
  </tr>
</thead>
  <?php foreach ($periods as $period):?>
<?php
	$from = $period['start'];
	$to = $period['end'];
?>
  <tr>
    <td class="payslip_period"><strong><a href="<?php echo url('attendance/attendance_logs_period?from='. $period['start'] .'&to='. $period['end'] . '&hpid=' . Utilities::encrypt($period['id']));?>"><?php echo Tools::getGmtDate('M j', strtotime($period['start']));?> - <?php echo Tools::getGmtDate('M j', strtotime($period['end']));?></a></strong></td>
    <!--<td><div id="dropholder"><a class="red_button small_button" href="#">5 No IN or OUT</a> <a class="red_button small_button" href="#">10 No Schedules</a></div></td>-->
    <!--<td><div id="dropholder"><a class="dropbutton" href="<?php echo url('attendance/attendance_logs_period?from='. $period['start'] .'&to='. $period['end']);?>">Download Timesheet</a></div></td>-->
    <td class="vertical-middle"><div id="dropholder"><a class="dropbutton" href="<?php echo url('attendance/attendance_logs_period?from='. $period['start'] .'&to='. $period['end'] . '&hpid=' . Utilities::encrypt($period['id']));?>"><i class="icon-zoom-in icon-fade"></i> View List</a></div>
    </td>
    <td class="vertical-middle text-right">
    <?php if($period['is_lock'] == G_Cutoff_Period::NO){ ?>
	     <div style="margin-right:17px;font-size:13px;">
	     	   <a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:lockPayrollPeriod('<?php echo Utilities::encrypt($period['id']); ?>');"><i class="icon-repeat vertical-middle"></i> Update Attendance</a>
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