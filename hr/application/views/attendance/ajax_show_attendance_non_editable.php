<style>
    table.timesheet-table tr td {
        padding: 3px 5px;
    }

    table.timesheet-table tr th {
        padding: 12px 9px;
    }
</style>

<script>
    $(function() {
        var jq = jQuery.noConflict();
        jq('.dropdown-toggle').dropdown();

        $(".attendance-btn-more-details").click(function() {
            var eid = $(this).attr("data-index");
            var date = $(this).parent("td").attr("data-label");
            showAttendanceOtherDetails(date, eid);
        });

        $(".attendance-btn-edit").click(function() {
            var eid = $(this).attr("data-index");
            var date = $(this).parent("td").attr("data-label");
            editTimesheetInOut(date, eid);

        });
    });
</script>

<script>
    $(function() {



        $("#payslip-report-year-selector").change(function() {
            changePayPeriodByYear(this.value, '<?php echo $cutoff_selected; ?>', 'payslip-pay-period-container', $("#payslip-report-frequency-selector").val());
        });

        $("#payslip-report-frequency-selector").change(function() {
            changePayPeriodByYear($("#payslip-report-year-selector").val(), '<?php echo $cutoff_selected; ?>', 'payslip-pay-period-container', this.value);
        });

        changePayPeriodByYear($("#payslip-report-year-selector").val(), '<?php echo $cutoff_selected; ?>', 'payslip-pay-period-container', $("#payslip-report-frequency-selector").val());





    });
</script>

<script>
    /*$(function(){
    $("#frm-employee-timesheet").validationEngine({scroll:false}); 
    var t = new $.TextboxList('#h_employee_id', {max:1,plugins: {
        autocomplete: {
            minLength: 3,
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}
        }
    }});
    
    t.addEvent('blur',function(o) {
        load_show_employee_request_approvers();
    });    
    
});*/
</script>

<?php
$path = 'application/views/attendance/_helper.php';
include $path;
?>



<div class="btn-group" style="position:absolute;left:793px;top:71px;z-index:9999;">
    <button class="blue_button dropdown-toggle" type="button"><i class="icon-download-alt icon-white"></i> Download Timesheet <span class="icon icon-chevron-down icon-white"></span></button>
    <ul class="dropdown-menu " style="margin-top:4px;margin-left:4px;">
        <li><a href="<?php echo url('attendance/download_timesheet_breakdown_by_employee_and_period?employee_id=' . $encrypted_employee_id . '&from=' . $start_date . '&to=' . $end_date . '&report=detailed'); ?>">Detailed </a></li>
        <li><a href="<?php echo url('attendance/download_timesheet_breakdown_by_employee_and_period?employee_id=' . $encrypted_employee_id . '&from=' . $start_date . '&to=' . $end_date . '&report=summarized'); ?>">Summarized </a></li>
    </ul>
</div>
<div class="additional_info_container" style="position:relative;">
    <h2>Period: <?php echo date('M j', strtotime($start_date)); ?> - <?php echo date('M j, Y', strtotime($end_date)); ?></h2>
</div>

<div class="container_12 npbutton_container">


    <div class="col_1_2 nextprevious_record nprecord_standalone" align="center">
        <?php if ($previous_encrypted_employee_id != '') { ?>
            <a href="javascript:void(0)" onclick="javascript:showAttendanceFromNavigation('#attendance_container', '<?php echo $previous_encrypted_employee_id; ?>', '<?php echo $start_date; ?>', '<?php echo $end_date; ?>', '<?php echo $previous_employee_name; ?>')" class="tooltip_prev" title="Load previous employee"><span>Previous</span></a>
        <?php } else { ?>
            <strong class="disabled_prev"><span>Previous</span></strong>
        <?php } ?>
        <h4 class="blue">Employee</h4>
        <?php if ($next_encrypted_employee_id != '') { ?>
            <a href="javascript:void(0)" onclick="javascript:showAttendanceFromNavigation('#attendance_container', '<?php echo $next_encrypted_employee_id; ?>', '<?php echo $start_date; ?>', '<?php echo $end_date; ?>', '<?php echo $next_employee_name; ?>')" class="tooltip_next" title="Load next employee"><span>Next</span></a>
        <?php } else { ?>
            <strong class="disabled_next"><span>Next</span></strong>
        <?php }; ?>
    </div>


    <!-- 
    <div class="col_1_2 nextprevious_record nprecord_standalone" align="center">
		<?php //if ($previous_start_date != '') {
        ?>
        	<a href="javascript:void(0)" onclick="javascript:showAttendanceFromNavigation('#attendance_container', '<?php echo $encrypted_employee_id; ?>', '<?php echo $previous_start_date; ?>', '<?php echo $previous_end_date; ?>')" class="tooltip_prev" title="Load previous timesheet"><span>Previous</span></a>     
        <?php //} else {
        ?>
        	<strong class="disabled_prev"><span>Previous</span></strong>
        <?php //};/
        ?>
        <h4 class="blue">Timesheet</h4>
        <?php //if ($next_start_date != '') {
        ?>
        	<a href="javascript:void(0)" onclick="javascript:showAttendanceFromNavigation('#attendance_container','<?php echo $encrypted_employee_id; ?>', '<?php echo $next_start_date; ?>', '<?php echo $next_end_date; ?>')" class="tooltip_next" title="Load next timesheet"><span>Next</span></a>	
        <?php //} else {/
        ?>
        	<strong class="disabled_next"><span>Next</span></strong>
        <?php //};
        ?>
    </div> 
    -->

    <!-- <div class="col_1_2 nextprevious_record nprecord_standalone" align="center">&nbsp;&nbsp;</div> -->

    <div class="col_1_2 nextprevious_record nprecord_standalone" align="center">

        <form id="frm-employee-timesheet" method="get" action="<?php echo url('attendance/show_attendance'); ?>">
            <input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($employee_id); ?>">
            <!-- <input type="text" id="h_employee_id" name="h_employee_id" class="validate[required]" /> -->
            <div class="payslip-pay-period-container" style="display:inline-block;"></div>
            <select name="year_selected" id="payslip-report-year-selector">
                <?php foreach ($all_cutoff_years as $year) { ?>
                    <?php if ($year <= date("Y")) { ?>
                        <option <?php echo $year_selected == $year ? 'selected="selected"' : '' ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
                    <?php } ?>
                <?php } ?>

            </select>
            <?php $frequency_id =  $_GET['frequency_id']; ?>
            <select id="payslip-report-frequency-selector" name="selected_frequency">

                <?php

                foreach (G_Settings_Pay_Period_Finder::findAll() as $period) {

                ?>

                    <option value="<?php echo $period->id; ?>" <?php echo $frequency_id == $period->id ? 'selected="selected"' : '' ?>> <?php echo $period->pay_period_name; ?> </option>

                <?php

                }

                ?>


            </select>
            <input class="gray_button" type="submit" name="submit" value="Load">
        </form>

    </div>
    <div class="clear"></div>
</div>
<!--<div>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importTimesheet()">Import Timesheet</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importScheduleSpecific()">Import Changed Schedule</a>
<br /><br /></div>-->
<table width="100%" class="formtable manydetails timesheet-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Day</th>
            <th>&nbsp;</th>
            <th>Attendance</th>
            <th>Time In-Out</th>
            <th>Overtime In-Out</th>
            <th>OT Hours</th>
            <th>Early/Late <br> Break Hours</th>
            <th>Late Hours</th>
            <th>Undertime Hours</th>
            <th>Break Hours</th>
            <th></th>
            <!--<td width="34%" bgcolor="#efefef"><strong>Time In - Out</strong></td>-->
            <!--<th width="100">&nbsp;</th>-->
        </tr>
    </thead>
    <?php foreach ($dates as $date) : ?>
        <?php
        $attendance_string = '';
        $a = $attendance[$date];
        if ($a) :
            $eid = Utilities::encrypt($a->getId());
            $attendance_string = get_attendance_string($a);
            if ($a->isOfficialBusiness()) {
                $attendance_string .= " (OB)";
            }
            $t = $a->getTimesheet();
            $is_present = $a->isPresent();
            $is_paid = $a->isPaid();

            $breaktime_late = G_Employee_Breaktime_Helper::getLateHoursByEmployeeIdPeriod($employee_id, $date, $date);

            $break_logs_summary = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($a->getId());
        ?>
            <tr>
                <td><small><?php echo date('m/d', strtotime($date)); ?></small></td>
                <td>
                    <?php
                    if (date('D', strtotime($date)) == 'Sun' || date('D', strtotime($date)) == 'Sat') {
                    ?><small style="color:#999999"><?php echo date('D', strtotime($date)); ?></small><?php
                                                                                                } else {
                                                                                                    ?><small><?php echo date('D', strtotime($date)); ?></small><?php
                                                                                                }
                                                                                ?>
                </td>
                <td>
                    <?php //if (!$is_paid):
                    ?>
                    <!--<span style="float:left" title="This attendance is 'without pay'" class="ui-icon ui-icon-alert edit"></span>-->
                    <?php //endif;
                    ?>
                </td>
                <td>
                    <?php
                    // if(!$is_present && !$is_paid && $t->getTimeIn() != '' && $t->getTimeOut() != '' ){
                    //     echo '<span class="absent-font-style">Incorrect Shift</span>';
                    // } else {
                    //     echo $attendance_string;
                    // }
                    echo $attendance_string;
                    ?>
                    <?php //echo $attendance_string; 
                    ?>
                </td>
                <td valign="top">
                    <?php if (($t->getTimeIn() == '00:00:00' || $t->getTimeIn() == '') && ($t->getTimeOut() == '00:00:00' || $t->getTimeOut() == '')) { ?>
                        <small>-</small>
                    <?php } else { ?>
                        <?php if (!$is_present && !$is_paid && $t->getTimeIn() != '' && $t->getTimeOut() != '') { ?>
                            <small><?php echo Tools::timeFormat($t->getTimeIn()); ?> - <?php echo Tools::timeFormat($t->getTimeOut()); ?></small>
                        <?php } else { ?>
                            <?php if ($is_present) { ?>
                                <small><?php echo Tools::timeFormat($t->getTimeIn()); ?> - <?php echo Tools::timeFormat($t->getTimeOut()); ?></small>
                            <?php } elseif ($t->getTimeIn()) { ?>
                                <small><?php echo Tools::timeFormat($t->getTimeIn()); ?> - No Out</small>
                            <?php } elseif ($t->getTimeOut()) { ?>
                                <small>No In - <?php echo Tools::timeFormat($t->getTimeOut()); ?></small>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </td>
                <td valign="top">
                    <?php if (($t->getOverTimeIn() == '00:00:00' || $t->getOverTimeIn() == '') && ($t->getOverTimeOut() == '00:00:00' || $t->getOverTimeOut() == '')) : ?>
                        <small>-</small>
                    <?php else : ?>
                        <!-- <?php if ($is_present) : ?> -->
                        <small><?php echo Tools::timeFormat($t->getOverTimeIn()); ?> - <?php echo Tools::timeFormat($t->getOverTimeOut()); ?></small>
                        <!-- <?php endif; ?> -->
                    <?php endif; ?>
                </td>
                <td valign="top">
                    <!-- <?php if ($is_present) : ?>
                    <small>
                        <?php echo Tools::convertHourToTime($t->getTotalOvertimeHours()); ?>
                    </small>
                <?php else : ?>
                    <small>-</small>
                <?php endif; ?> -->

                    <?php if ($t->getTotalOvertimeHours()) : ?>
                        <small>
                            <?php echo Tools::convertHourToTime($t->getTotalOvertimeHours()); ?>
                        </small>
                    <?php else : ?>
                        <small>-</small>
                    <?php endif; ?>
                </td>
                <td valign="top">
                    <?php if ($break_logs_summary) : ?>
                        <small>
                            <?php echo number_format($break_logs_summary->getTotalEarlyBreakOutHrs() + $break_logs_summary->getTotalLateBreakInHrs(), 2) + 0; ?>
                        </small>
                    <?php else : ?>
                        <small>-</small>
                    <?php endif; ?>
                </td>
                <td valign="top">
                    <!-- <?php if ($is_present) : ?>
                 <?php if ($breaktime_late && $break_logs_summary) { ?>
                            <?php
                                    $total_late_hrs = $t->getLateHours() + $breaktime_late;
                            ?>
                            <small>
                                <?php echo Tools::convertHourToTime($total_late_hrs); ?>
                            </small>
                 <?php } else { ?>
                            <small>
                                <?php echo Tools::convertHourToTime($t->getLateHours()); ?>
                            </small>
                 <?php } ?>

                <?php else : ?>
                <small>-</small>
            <?php endif; ?>       -->

                    <?php if ($t->getLateHours()) : ?>
                        <?php if ($breaktime_late && $break_logs_summary) { ?>
                            <?php
                            $total_late_hrs = $t->getLateHours() + $breaktime_late;
                            ?>
                            <small>
                                <?php echo Tools::convertHourToTime($total_late_hrs); ?>
                            </small>
                        <?php } else { ?>
                            <small>
                                <?php echo Tools::convertHourToTime($t->getLateHours()); ?>
                            </small>
                        <?php } ?>

                    <?php else : ?>
                        <small>-</small>
                    <?php endif; ?>
                </td>
                <td valign="top">
                    <!-- <?php if ($is_present) : ?>
                    <small>
                        <?php echo Tools::convertHourToTime($t->getUndertimeHours()); ?>
                    </small>
                <?php else : ?>
                    <small>-</small>
                <?php endif; ?> -->

                    <?php if ($t->getUndertimeHours()) : ?>
                        <small>
                            <?php echo Tools::convertHourToTime($t->getUndertimeHours()); ?>
                        </small>
                    <?php else : ?>
                        <small>-</small>
                    <?php endif; ?>
                </td>
                <td valign="top">
                    <?php if ($break_logs_summary) : ?>
                        <small>
                            <?php echo number_format($break_logs_summary->getTotalBreakHrs(), 2) + 0; ?>
                        </small>
                    <?php else : ?>
                        <small>-</small>
                    <?php endif; ?>
                </td>
                <td valign="top" data-label="<?php echo strtotime($date); ?>">
                    <?php if ($employee_break_logs_summary_helper->checkHasIncompleteBreakByDateRangeEmployeeId($date, $date, $employee_id) && !$a->getHoliday() && !$a->isRestday() && !$a->getLeaveId()) { ?>
                        <div class="exclamation-triangle-icon with-tooltip" original-title="Incomplete Break Logs"></div>
                    <?php } ?>
                    <a href="javascript:void(0)" data-index="<?php echo $encrypted_employee_id; ?>" class="link_option attendance-btn-more-details" style="display:flex;justify-content:center;">
                        <!-- More Details -->
                        <div class="three-dots-icon with-tooltip" original-title="More Details">☰</div>
                    </a>
                </td>
            </tr>
        <?php else : ?>
            <tr>
                <td><small><?php echo date('m/d', strtotime($date)); ?></small></td>
                <td>
                    <?php
                    if (date('D', strtotime($date)) == 'Sun' || date('D', strtotime($date)) == 'Sat') {
                    ?><small style="color:#999999"><?php echo date('D', strtotime($date)); ?></small><?php
                                                                                                } else {
                                                                                                    ?><small><?php echo date('D', strtotime($date)); ?></small><?php
                                                                                                }
                                                                                ?>
                </td>
                <td>&nbsp;</td>
                <td>-</td>
                <td valign="top">-</td>
                <td valign="top">-</td>
                <td valign="top">-</td>
                <td valign="top">-</td>
                <td valign="top">-</td>
                <td valign="top">-</td>
                <td valign="top">-</td>
                <td valign="top" data-label="<?php echo strtotime($date); ?>">

                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>

<script language="javascript">
    $('.edit').tipsy({
        gravity: 's'
    });
    $('.with-tooltip').tipsy({
        gravity: 's'
    });
</script>