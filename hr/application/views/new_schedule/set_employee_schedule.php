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
<h3 class="section_title">Date Applied</h3>
<form method="get">
    <table width="100%">
        <tr>
            <td class="field_label">Select date:</td>
            <td><input type="date" id="date" class="validate[required]" name="date" value="<?php echo $date_log; ?>" /></td>
        </tr>
        <tr>
            <td><input type="submit" value="Go" /></td>
        </tr>
    </table>
</form>
<br>
<div id="tabs">

    <ul>
        <li><a href="#tabs-1">Shift Schedule <span class="noti_count"><?php echo "0"; ?></span></a></li>
        <li><a href="#tabs-2">Compress Schedule <span class="noti_count"><?php echo "0"; ?></span></a></li>
        <li><a href="#tabs-3">Staggered Schedule <span class="noti_count"><?php echo "0"; ?></span></a></li>
        <li><a href="#tabs-4">Flextime Schedule <span class="noti_count"><?php echo "0"; ?></span></a></li>
        <li><a href="#tabs-5">No Schedule <span class="noti_count"><?php echo "0"; ?></span></a></li>
    </ul>

    <div id="tabs-1">
        <script>
            showSetEmployeeShiftSchedule('#shift_schedule_members_list');
        </script>
        <div id="shift_schedule_members_list"></div>
    </div>
    <div id="tabs-2">
        <script>
            showSetEmployeeCompressSchedule('#compress_schedule_members_list');
        </script>
        <div id="compress_schedule_members_list"></div>
    </div>
    <div id="tabs-3">
        <script>
            showSetEmployeeStaggeredSchedule('#staggered_schedule_members_list');
        </script>
        <div id="staggered_schedule_members_list"></div>
    </div>
    <div id="tabs-4">
        <script>
            showSetEmployeeFlextimeSchedule('#flextime_schedule_members_list');
        </script>
        <div id="flextime_schedule_members_list"></div>
    </div>

    <div id="tabs-5">
        <script>
            showSetEmployeeNoSchedule('#no_schedule_members_list');
        </script>
        <div id="no_schedule_members_list"></div>

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