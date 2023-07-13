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
<br><br><br>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">All<span class="noti_count"><?php echo $all_notif; ?></span></a></li>
        <li><a href="#tabs-2">Shift Schedule <span class="noti_count"><?php echo $shift_notif; ?></span></a></li>
        <li><a href="#tabs-3">Compress Schedule <span class="noti_count"><?php echo $compress_notif; ?></span></a></li>
        <li><a href="#tabs-4">Staggered Schedule <span class="noti_count"><?php echo $staggered_notif; ?></span></a></li>
        <li><a href="#tabs-5">Flextime Schedule <span class="noti_count"><?php echo $flexible_notif; ?></span></a></li>
    </ul>
    <div id="tabs-1">
        <script>
            showAllScheduleMembersList('#all_schedule');
        </script>
        <div id="all_schedule"></div>
    </div>

    <div id="tabs-2">
        <script>
            showDashboardShiftScheduleMembersList('#shift_schedule_members_list');
        </script>
        <div id="shift_schedule_members_list"></div>
    </div>

    <div id="tabs-3">
        <script>
            showDashboardCompressScheduleMembersList('#compress_schedule_members_list');
        </script>
        <div id="compress_schedule_members_list"></div>
    </div>

    <div id="tabs-4">
        <script>
            showDashboardStaggeredScheduleMembersList('#staggered_schedule_members_list');
        </script>
        <div id="staggered_schedule_members_list"></div>
    </div>

    <div id="tabs-5">
        <script>
            showDashboardFlextimeScheduleMembersList('#flextime_schedule_members_list');
        </script>
        <div id="flextime_schedule_members_list"></div>
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
</script>