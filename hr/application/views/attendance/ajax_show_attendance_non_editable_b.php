<style>
.manydetails tr td.attendance-remarks{text-align: left;}
.manydetails tr td{text-align: center;}
</style>
<?php
$path = 'application/views/attendance/_helper.php';
include $path;?>
<div class="additional_info_container">
<div align="right" class="float-right"><a class="blue_button" href="<?php echo url('attendance/download_timesheet_breakdown_by_employee_and_period?employee_id='. $encrypted_employee_id .'&from='. $start_date .'&to='. $end_date);?>"><i class="icon-download-alt icon-white"></i> Download Timesheet</a> 
<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
	<!--<a class="blue_button" onclick="javascript:updateAttendanceByEmployee('<?php echo $encrypted_employee_id;?>', '<?php echo $start_date;?>', '<?php echo $end_date;?>')" href="javascript:void(0)"><i class="icon-repeat icon-white"></i> Update Attendance</a>-->
<?php } ?>
</div>
<h2>Period: <?php echo date('M j', strtotime($start_date));?> - <?php echo date('M j, Y', strtotime($end_date));?></h2></div>

<div style="width:80%">   

    <?php include('timesheet/_timesheet_body_detailed.php'); ?>
</div>