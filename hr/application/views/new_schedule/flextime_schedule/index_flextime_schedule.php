<style>
    .date_input {
        width: 44% !important;
    }
</style>
<script type="text/javascript">
	$(function() {
		
        $("#tabs").tabs({});
		load_flextime_schedule("#schedule");


		$(".no_schedule").click(function(){
			load_no_flextime_schedule('#no_schedule');
		});
        $(".leave").click(function(){
			load_leave_flextime_schedule('#leave');
		});
        $(".rest_day").click(function(){
			load_rest_day_flextime_schedule('#rest_day');
		});
        $(".ob").click(function(){
            load_ob_flextime_schedule('#official_business');
		});
        $(".rules").click(function(){

		});
        $(".employee").click(function(){
            load_flextime_schedule_employee_list_dt();
		});
        $(".schedule").click(function(){
			load_flextime_schedule_list_dt();
		});

	});
</script>
<?php
$days[1] = 'Monday';
$days[2] = 'Tuesday';
$days[3] = 'Wednesday';
$days[4] = 'Thursday';
$days[5] = 'Friday';
$days[6] = 'Saturday';
$days[7] = 'Sunday';
?>

<?php
$dt = new DateTime;
$date_format = DateTime::createFromFormat("Y-m-d", $date_log);

$dt->setISODate($date_format->format('Y'), $date_format->format('W'));

$year = $dt->format('o');
$week = $dt->format('W');
?>
<form method="get">
    <table>
        <tr>
            <td style="text-align: center;"><d style="font-size: 14px;">Select date:</d><br> <input type="text" id="date" style="font-size: 12px;" class="input-small" name="date" value="<?php echo $date_log; ?>"/></td>
            <td><input type="submit" class="blue_button" value="Go" /></td>
            <?php
            do {
                if($dt->format("Y-m-d") == $date_log){
                    echo    "   <td>
                                    <center>
                                        <input type=\"submit\" class=\"blue_button\" name=\"date\" value=\"" . $dt->format("Y-m-d") . "\">" . "<br>" . $dt->format('l') .
                            "       </center>
                                </td>\n";
                }else{
                    echo    "   <td>
                                    <center>
                                        <input type=\"submit\" class=\"gray_button\" name=\"date\" value=\"" . $dt->format("Y-m-d") . "\">" . "<br>" . $dt->format('l') .
                            "       </center>
                                </td>\n";
                }
                
                $dt->modify('+1 day');
            } while ($week == $dt->format('W'));
            ?>
        </tr>
    </table>
</form>
<br><br>
<?php echo $btn_create_schedule; ?>
<br><br><br>
<div id="tabs">
    <ul>
        <li><a class="all" href="#tabs-1">All</a></li>
        <li><a class="no_schedule" href="#tabs-2">No Schedule</a></li>
        <li><a class="leave" href="#tabs-3">Leave</a></li>
        <li><a class="rest_day" href="#tabs-4">Rest Day</a></li>
        <li><a class="ob" href="#tabs-5">Official Business</a></li>
        <li><a class="rules" href="#tabs-6">Rules</a></li>
        <li><a class="employee" href="#tabs-7">Employee</a></li>
        <li><a class="schedule"href="#tabs-8">Schedule</a></li>
    </ul>

    <div id="tabs-1">
        <div id="schedule"></div>
    </div>

    <div id="tabs-2">
        <div id="no_schedule"></div>
    </div>

    <div id="tabs-3">
        <div id="leave"></div>
    </div>

    <div id="tabs-4">
        <div id="rest_day"></div>
    </div>

    <div id="tabs-5">
        <div id="official_business"></div>
    </div>

    <div id="tabs-6">
        <form id="add_schedule_form" method="post" action="<?php echo $action; ?>">
            <div id="form_main" class="inner_form popup_form wider">
                <div id="form_default">
                    <!--<p class="red">Note : Day(s) with empty fields will be mark as restday</p>-->
                    <table class="no_border" width="100%">
                        <tr>
                            <td class="field_label">Complete: </td>
                            <td><input style="width:3%;" class="validate[required]" type="number" name="hours" id="hours" value="<?php echo $hours; ?>" /> Hours</td>
                        </tr>
                        <tr>
                            <td class="field_label">*Time In:</td>
                            <td>
                                <input class="validate[required] time_in" name="time_in" value="<?php echo $time_in; ?>" onchange="onStartTimeChanged(<?php echo 1; ?>)" type="text" id="start_time_<?php echo 1; ?>" style="width:60px" />
                            </td>
                        </tr>
                        <tr>
                            <td class="field_label">*Time Out:</td>
                            <td>
                                <input class="validate[required]" name="time_out" value="<?php echo $time_out; ?>" onchange="onEndTimeChanged(<?php echo 1; ?>)" type="text" id="end_time_<?php echo 1; ?>" style="width:60px" />
                            </td>
                        </tr>
                    </table>
                </div>
                <span id="schedule_message"></span>
                <div id="form_default" class="form_action_section">
                    <table class="no_border" width="100%">
                        <tr>
                            <td class="field_label">&nbsp;</td>
                            <td>
                                <input value="Save" id="add_schedule_submit" class="curve blue_button" type="submit">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </form>
    </div>

    <div id="tabs-7">
        <?php echo $btn_add_employees; ?>
        <script>
            load_flexible_schedule_employee_list_dt();
        </script>

        <div class="dt_top_nav">
            <!--
					<select name="chkAction" id="chkAction" onchange="javascript:withSelectedLogs(this.value);" disabled="disabled">
						<option value="">With Selected:</option>
						<option value="update">Batch Update</option>
						<option value="delete">Delete</option>
					</select>-->
        </div>
        <div class="formtable">
            <div class="inner">
                <table width="100%" class="formtable">
                    <thead>
                        <tr>
                            <th valign="top" width="20%">
                                <strong>Employees</strong>&nbsp;&nbsp;
                            </th>
                            <th valign="top" width="20%">
                                <strong>Schedule</strong>&nbsp;&nbsp;
                            </th>
                            <th valign="top" width="20%">
                                <strong>Department</strong>&nbsp;&nbsp;
                            </th>
                            <th valign="top" width="20%">
                                <strong>Project Site</strong>&nbsp;&nbsp;
                            </th>
                            <th>
                                <strong>Action</strong>&nbsp;&nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody id="employee_list_dt_wrapper">

                    </tbody>
                </table>
            </div>
        </div>
        <div id="status_message"></div>
        <div id="schedule_members_list"></div>
        <script language="javascript">
            $('.tooltip').tipsy({
                gravity: 's'
            });
        </script>
    </div>

    <div id="tabs-8">
        <script>
            load_flexible_schedule_list_dt();
        </script>

        <div class="dt_top_nav">
            <!--
					<select name="chkAction" id="chkAction" onchange="javascript:withSelectedLogs(this.value);" disabled="disabled">
						<option value="">With Selected:</option>
						<option value="update">Batch Update</option>
						<option value="delete">Delete</option>
					</select>-->
        </div>
        <div class="formtable">
            <div class="inner">
                <table width="100%" class="formtable">
                    <thead>
                        <tr>
                            <!--<th bgcolor="#cccccc">
            <div style="float:left"><strong>Groups</strong>&nbsp;&nbsp;<?php //echo $btn_add_employees; 
                                                                        ?></div>
          </th>-->
                            <th valign="top" width="20%">
                                <strong>Schedule Type</strong>&nbsp;&nbsp;
                            </th>
                            <th valign="top" width="20%">
                                <strong>Schedule Name</strong>&nbsp;&nbsp;
                            </th>
                            <th valign="top" width="10%">
                                <strong>Required Hours</strong>&nbsp;&nbsp;
                            </th>
                            <th valign="top" width="10%">
                                <strong>Schedule In</strong>&nbsp;&nbsp;
                            </th>
                            <th valign="top" width="10%">
                                <strong>Schedule Out</strong>&nbsp;&nbsp;
                            </th>
                            <th valign="top" width="10%">
                                <strong>Break Out</strong>&nbsp;&nbsp;
                            </th>
                            <th valign="top" width="10%">
                                <strong>Break In</strong>&nbsp;&nbsp;
                            </th>
                            <th>
                                <strong>Action</strong>&nbsp;&nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody id="schedule_list_dt_wrapper">

                    </tbody>
                </table>
            </div>
        </div>
        <div id="status_message"></div>
        <div id="schedule_members_list"></div>
        <script language="javascript">
            $('.tooltip').tipsy({
                gravity: 's'
            });
        </script>
    </div>
</div>

<script>
    var first_time_number = ''; // which is the first textbox supplied data
    $('.copy_time').hide();
    //$('.clear_link').hide();

    function copyTimeTo(source_number, to_number) {
        var start_time = $('#start_time_' + source_number).val();
        var end_time = $('#end_time_' + source_number).val();
        $('#start_time_' + to_number).val(start_time);
        $('#end_time_' + to_number).val(end_time);
        $('#clear_link_' + to_number).show();
    }

    function hideCopyTime(number) {
        $('#copy_time_' + number).hide();
    }

    function clearTime(number) {
        $('#start_time_' + number).val('');
        $('#end_time_' + number).val('');
        hideClearLink(number);
        hideCopyTime(number);
        if (first_time_number == number) {
            first_time_number = '';
        }
    }

    function showClearLink(number) {
        $('#clear_link_' + number).show();
    }

    function hideClearLink(number) {
        $('#clear_link_' + number).hide();
    }

    function onEndTimeChanged(number) {
        if (number == 1) {
            var end_time = $('#end_time_' + number).val();
            if (end_time != '') {
                $('#copy_time_1').show();
            }
        }
    }

    function onStartTimeChanged(number) {
        var start_time_id = '#start_time_' + number;
        var end_time_id = '#end_time_' + number;
        var start_time = $('#start_time_' + number).val();
        var split_time = start_time.split(':');
        var hour = parseFloat(split_time[0]) + 9;
        var split_minutes = split_time[1].split(' ');
        var minutes = split_minutes[0];
        var am = split_minutes[1];
        if (hour > 12) {
            hour = hour - 12;
        }

        if (am == 'pm') {
            am = 'am';
        } else {
            am = 'pm';
        }
        $(end_time_id).val(hour + ':' + minutes + ' ' + am);
        $(end_time_id).timepicker({
            'minTime': $(start_time_id).val(),
            'maxTime': $(start_time_id).val(),
            'timeFormat': 'g:i a',
            'showDuration': true
        });

        showClearLink(number);

        if (first_time_number == '') { // monday
            first_time_number = number;
            $('#copy_time_' + number).show();
        }
    }
    for (i = 1; i <= 7; i++) {
        $('#start_time_' + i).timepicker({
            'minTime': '6:00 am',
            'maxTime': '5:30 am',
            'timeFormat': 'g:i a'
        });
        $('#end_time_' + i).timepicker({
            'minTime': '6:00 am',
            'maxTime': '5:30 am',
            'timeFormat': 'g:i a'
        });
    }

    $("#add_schedule_form #start_date").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        onSelect: function(date) {
            $("#add_schedule_form #end_date").datepicker('option', {
                minDate: $(this).datepicker('getDate')
            });
        }
    });

    $(function() {
        $("#tabs").tabs();
    });

    $("#from").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true
    });
    $("#to").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true
    });
</script>