<h2><?php echo $title; ?></h2>
<script>
    $("#birthdate").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true
    });
    $("#date_from").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        onSelect: function() {
            $("#date_to").datepicker('option', {
                minDate: $(this).datepicker('getDate')
            });
        }
    });

    $("#date_to").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        onSelect: function() {

        }
    });

    $(function() {
        $("#frm-report-attendance-absence").validationEngine({
            scroll: false
        });
    });
</script>
<div id="form_main" class="employee_form">

    <script>
        function selectForm() {
            var chosen = $('#import_selection').val();
            if (chosen == 'weekly') {
                importSchedule();
            } else if (chosen == 'specific') {
                importScheduleSpecific()
            } else if (chosen == 'restday') {
                importRestday();
            }
            $("#import_selection").val("");
        }
    </script>

    <div id="create_schedule_handler"></div>

    <div id="employee_search_container">
        <form method="get" action="<?php echo $action; ?>">
            <div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" placeholder="Type employee, department or section name" />
                <button id="employee_search_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search</button>
            </div>
        </form>
    </div><!-- #employee_search_container -->

    <div style="float:left">
        <?php if ($permission_action == Sprint_Modules::PERMISSION_02) { ?>
            <strong>Import:</strong>&nbsp;&nbsp;
            <select id="import_selection" name="import_selection" size="" onchange="selectForm()">
                <option selected="selected">- Select to Import -</option>
                <option value="weekly">Weekly Schedule</option>
                <option value="specific">Change Schedule</option>
                <option value="restday">Rest Day Schedule</option>
            </select>
        <?php } ?>
        <!--<a class="gray_button" href="javascript:void(0)" onclick="javascript:importSchedule()"><i class="icon-arrow-left"></i> Import Weekly Schedule</a>
    <a class="gray_button" href="javascript:void(0)" onclick="javascript:importScheduleSpecific()"><i class="icon-arrow-left"></i> Import Changed Schedule</a>-->
        <br /><br />
    </div>

    <div style="float:right">
        <form method="get">
            Show:
            <select name="month">
                <?php foreach ($months as $key => $month) : ?>
                    <option <?php echo (($key + 1) == $show_month) ? "selected='selected'" : ''; ?> value="<?php echo ($key + 1); ?>"><?php echo $month; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="year">
                <?php foreach ($years as $year) : ?>
                    <option <?php echo ($year == $show_year) ? "selected='selected'" : ''; ?>><?php echo $year; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Go" />
        </form>
    </div>
    <br><br><br>

    <div id="schedule_list"></div>

    <!--<div style="display:none" id="create_schedule_id">
<?php //include 'application/views/new_schedule/forms/create_schedule_form.php';
?>
</div>-->

    <script>
        showStaggeredScheduleList('#schedule_list');
    </script>
</div>
<div class="yui-skin-sam">
    <div id="applicant_list_datatable"></div>
</div>
