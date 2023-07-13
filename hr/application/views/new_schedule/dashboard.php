<style>
    .noti_count {
        display: block;
        position: absolute;
        z-index: 100;
        font-size: 11px;
        right: -8px;
        top: -12px;
        color: #ffffff;
        padding: 0 4px;
        min-width: 2px;
        text-align: center;
        background-color: #2690dd;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        -moz-box-shadow: 0px 1px 1px #222222;
        -webkit-box-shadow: 0px 1px 1px #222222;
        box-shadow: 0px 1px 1px #222222;
        filter: progid:DXImageTransform.Microsoft.Shadow(strength=1, direction=180, color='#222222');
        -ms-filter: "progid:DXImageTransform.Microsoft.Shadow(strength = 1, Direction = 180, Color = '#222222')";
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fd5252', endColorstr='#f60304');
        -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr = '#fd5252', endColorstr = '#f60304')";
        background-image: -moz-linear-gradient(top, #fd5252, #f60304);
        background-image: -ms-linear-gradient(top, #fd5252, #f60304);
        background-image: -o-linear-gradient(top, #fd5252, #f60304);
        background-image: -webkit-gradient(linear, center top, center bottom, from(#fd5252), to(#f60304));
        background-image: -webkit-linear-gradient(top, #fd5252, #f60304);
        background-image: linear-gradient(top, #fd5252, #f60304);
        -moz-background-clip: padding;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
    }
</style>
<style>
    .date_input {
        width: 44% !important;
    }
</style>
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
            <td style="text-align: center;">
                <d style="font-size: 14px;">Select date:</d><br> <input type="text" id="date" style="font-size: 12px;" class="input-small" name="date" value="<?php echo $date_log; ?>" />
            </td>
            <td><input type="submit" class="blue_button" value="Go" /></td>
            <?php
            do {
                if ($dt->format("Y-m-d") == $date_log) {
                    echo    "   <td>
                                    <center>
                                        <input type=\"submit\" class=\"blue_button\" name=\"date\" value=\"" . $dt->format("Y-m-d") . "\">" . "<br>" . $dt->format('l') .
                        "       </center>
                                </td>\n";
                } else {
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
<br><br><br>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Shift Schedule </span></a></li>
        <li><a href="#tabs-2">Compress Schedule </span></a></li>
        <li><a href="#tabs-3">Staggered Schedule </span></a></li>
        <li><a href="#tabs-4">Flextime Schedule </span></a></li>
    </ul>
    <div id="tabs-1">
        <div id="sub_tabs_shift">
            <ul>
                <li><a href="#shift-1">All </a></li>
                <li><a href="#shift-2">Leave </a></li>
                <li><a href="#shift-3">OB </a></li>
                <li><a href="#shift-4">No Schedule </a></li>
            </ul>
            <div id="shift-1">
                <script>
                    showDashboardShiftScheduleMembersList('#shift_schedule_members_list');
                </script>
                <div id="shift_schedule_members_list"></div>
            </div>
            <div id="shift-2">
                <script>
                    showDashboardShiftLeaveScheduleMembersList('#shift_leave_schedule_members_list');
                </script>
                <div id="shift_leave_schedule_members_list"></div>
            </div>
            <div id="shift-3">
                <script>
                    showDashboardShiftOBScheduleMembersList('#shift_ob_schedule_members_list');
                </script>
                <div id="shift_ob_schedule_members_list"></div>
            </div>
            <div id="shift-4">
                <script>
                    showDashboardShiftNoScheduleMembersList('#shift_no_schedule_members_list');
                </script>
                <div id="shift_no_schedule_members_list"></div>
            </div>
        </div>

    </div>

    <div id="tabs-2">
        <div id="sub_tabs_compress">
            <ul>
                <li><a href="#compress-1">All </a></li>
                <li><a href="#compress-2">Leave </a></li>
                <li><a href="#compress-3">OB </a></li>
                <li><a href="#compress-4">No Schedule </a></li>
            </ul>
            <div id="compress-1">
                <script>
                    showDashboardCompressScheduleMembersList('#compress_schedule_members_list');
                </script>
                <div id="compress_schedule_members_list"></div>
            </div>
            <div id="compress-2">
                <script>
                    showDashboardCompressLeaveScheduleMembersList('#compress_leave_schedule_members_list');
                </script>
                <div id="compress_leave_schedule_members_list"></div>
            </div>
            <div id="compress-3">
                <script>
                    showDashboardCompressOBScheduleMembersList('#compress_ob_schedule_members_list');
                </script>
                <div id="compress_ob_schedule_members_list"></div>
            </div>
            <div id="compress-4">
                <script>
                    showDashboardCompressNoScheduleMembersList('#compress_no_schedule_members_list');
                </script>
                <div id="compress_no_schedule_members_list"></div>
            </div>
        </div>
    </div>

    <div id="tabs-3">
        <div id="sub_tabs_staggered">
            <ul>
                <li><a href="#staggered-1">All </a></li>
                <li><a href="#staggered-2">Leave </a></li>
                <li><a href="#staggered-3">OB </a></li>
                <li><a href="#staggered-4">No Schedule </a></li>
            </ul>

            <div id="staggered-1">
                <script>
                    showDashboardStaggeredScheduleMembersList('#staggered_schedule_members_list');
                </script>
                <div id="staggered_schedule_members_list"></div>
            </div>
            <div id="staggered-2">
                <script>
                    showDashboardStaggeredLeaveScheduleMembersList('#staggered_leave_schedule_members_list');
                </script>
                <div id="staggered_leave_schedule_members_list"></div>
            </div>
            <div id="staggered-3">
                <script>
                    showDashboardStaggeredOBScheduleMembersList('#staggered_ob_schedule_members_list');
                </script>
                <div id="staggered_ob_schedule_members_list"></div>
            </div>
            <div id="staggered-4">
                <script>
                    showDashboardStaggeredNoScheduleMembersList('#staggered_no_schedule_members_list');
                </script>
                <div id="staggered_no_schedule_members_list"></div>
            </div>
        </div>
    </div>

    <div id="tabs-4">
        <div id="sub_tabs_flextime">
            <ul>
                <li><a href="#flextime-1">All </a></li>
                <li><a href="#flextime-2">Leave </a></li>
                <li><a href="#flextime-3">OB </a></li>
                <li><a href="#flextime-4">No Schedule </a></li>
            </ul>

            <div id="flextime-1">
                <script>
                    showDashboardFlextimeScheduleMembersList('#flextime_schedule_members_list');
                </script>
                <div id="flextime_schedule_members_list"></div>
            </div>
            <div id="flextime-2">
                <script>
                    showDashboardFlextimeLeaveScheduleMembersList('#flextime_leave_schedule_members_list');
                </script>
                <div id="flextime_leave_schedule_members_list"></div>
            </div>
            <div id="flextime-3">
                <script>
                    showDashboardFlextimeOBScheduleMembersList('#flextime_ob_schedule_members_list');
                </script>
                <div id="flextime_ob_schedule_members_list"></div>
            </div>
            <div id="flextime-4">
                <script>
                    showDashboardFlextimeNoScheduleMembersList('#flextime_no_schedule_members_list');
                </script>
                <div id="flextime_no_schedule_members_list"></div>
            </div>
        </div>
    </div>

</div>




<script language="javascript">
    $('.tooltip').tipsy({
        gravity: 's'
    });
    $('.info').tipsy({
        gravity: 's'
    });

    $(function() {
        $("#tabs").tabs();
    });
    $(function() {
        $("#sub_tabs_shift").tabs();
    });
    $(function() {
        $("#sub_tabs_compress").tabs();
    });
    $(function() {
        $("#sub_tabs_staggered").tabs();
    });
    $(function() {
        $("#sub_tabs_flextime").tabs();
    });
</script>