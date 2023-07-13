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
        min-width: 12px;
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

    .badge {
        background-color: blue;
        color: white;
    }

    .ui-autocomplete-input {
        width: 100%;
    }

    .dt_limit {
        width: 20%;
    }

    .logs-delete-btn {
        margin-left: 10px;
    }

    .schedule-list a.active {
        background-color: #0081c2;
        color: #ffffff;
    }

    .group-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
</style>
<script>
    $(function() {

        $("#btn-add-attendance-log").click(function() {
            addAttendanceLog();
        });

        $("#autocomplete_leave_staggered").hide();
        $("#all_emp_leave_staggered").show();
        load_leave_compress_schedule_dt();
        //$("table").tablesorter();

        var emp_selected = new $.TextboxList('#emp_selected_leave_staggered', {
            unique: true,
            plugins: {
                autocomplete: {
                    minLength: 1,
                    onlyFromValues: true,
                    queryRemote: true,
                    remote: {
                        url: base_url + 'employee/ajax_get_employees_autocomplete'
                    }

                }
            }
        });

        $('ul.textboxlist-bits').attr("title", "Type employee name to see suggestions.");
        $('ul.textboxlist-bits').tipsy({
            gravity: 's'
        });

        $('.department-cancel-filter').click(function(e) {

            $('.department-cancel-filter').hide();
            $('#error_filter').val('');
            load_leave_compress_schedule_dt();
            $('.department-list a').removeClass('active');
        });


        $('.department-list a').click(function(e) {
            var target = $(e.target);
            var filter_type = target.data('filter') ? target.data('filter') : '';

            if (target.hasClass('active')) {
                target.removeClass('active');
                filter_type = '';
                $('.department-cancel-filter').hide();
            } else {
                $('.department-list a').removeClass('active');
                target.addClass('active');
                $('.department-cancel-filter').show();
            }

            $('#error_filter').val(filter_type);

            load_leave_compress_schedule_dt();
        });

    });

    function gotoPage(displayStart, paginatorIndex) {
        var limit = $("#dt_limit").val();
        var orderBy = $("#orderBy").val();
        var sortColumn = $("#colName").val();

        var date_from = $("#s_from").val();
        var date_to = $("#s_to").val();
        var error_type = $("#s_error_type").val();
        var emp_sel = $("#s_emp_selected").val();

        var filter = $("#error_filter").val();

        $('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
        $.post(base_url + 'project_site/_load_attendance_logs_dt', {
                sortColumn: sortColumn,
                orderBy: orderBy,
                displayStart: displayStart,
                limit: limit,
                paginatorIndex: paginatorIndex,
                date_from: date_from,
                date_to: date_to,
                error_type: error_type,
                emp_sel: emp_sel,
                filter: filter
            },
            function(o) {
                $('#loading_wrapper').html('');
                $('#attendance_logs_dt_wrapper').html(o.table);
                $('.paginator').html(o.paginator)
            }, "json");
    }

    function sortDt(sortColumn) {
        var limit = $("#dt_limit").val();
        var orderBy = $("#orderBy").val();

        var date_from = $("#s_from").val();
        var date_to = $("#s_to").val();
        var error_type = $("#s_error_type").val();
        var emp_sel = $("#s_emp_selected").val();

        if (orderBy == 'ASC') {
            $("#orderBy").val("DESC");
            orderBy = 'DESC';
        } else {
            $("#orderBy").val("ASC");
            orderBy = 'ASC';
        }

        $('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
        $.post(base_url + 'project_site/_load_attendance_logs_dt', {
                limit: limit,
                sortColumn: sortColumn,
                orderBy: orderBy,
                date_from: date_from,
                date_to: date_to,
                error_type: error_type,
                emp_sel: emp_sel
            },
            function(o) {
                $("#colName").val(sortColumn);
                $('#attendance_logs_dt_wrapper').html(o.table);
                $('#loading_wrapper').html('');
                $('.paginator').html(o.paginator)
            }, "json");
    }

    function checkUncheckLeave() {
        if ($('input[name="dtrChkLeave[]"]:checked').length > 0) {
            $('#chkActionLeave').attr('disabled', false);
            if ($('input[name="dtrChkLeave[]"]:checked').length == $('input[name="dtrChkLeave[]"]').length) {
                $('#chkAllLeave').attr('checked', true);
            }
        } else {
            $('#chkActionLeave').attr('disabled', true);
        }
    }

    function chkAllLeave() {
        if ($('#chkAllLeave:checked').length) {
            $('#chkActionLeave').attr('disabled', false);
            $('input[name="dtrChkLeave[]"]').attr('checked', true);
        } else {
            $('#chkActionLeave').attr('disabled', true);
            $('input[name="dtrChkLeave[]"]').attr('checked', false);
        }
    }
</script>
<div id="employee_search_container" style="overflow:visible !important; padding-bottom:15px;">
    <div class="employee_basic_search searchcnt" id="search_wrapper">
        <input type="hidden" id="s_from" value="" />
        <input type="hidden" id="s_to" value="" />
        <input type="hidden" id="s_error_type" value="" />
        <input type="hidden" id="s_emp_selected" value="" />
        <input type="hidden" id="error_filter" value="" />
        <?php if (!empty($_GET['hpid_n'])) { ?>
            <input type="hidden" id="hpid_n" value="<?php echo $_GET['hpid_n']; ?>">
        <?php } ?>
        <div class="float-left">
            <span class="float-left" style="padding-top:6px;">Name:&nbsp;&nbsp;&nbsp;</span>
            <div id="all_emp_leave_staggered" class="float-left" style="width:312px;">
                <input disabled="disabled" type="text" name="input_disabled" id="input_disabled" style="width:290px; min-width:290px;" value="" />
            </div>
            <div id="autocomplete_leave_staggered" class="float-left">
                <input type="text" name="emp_selected" id="emp_selected_leave_staggered" />
            </div>
            <div class="clear"></div>
            <span class="float-left" style=" width:50px;">&nbsp;</span>
            <div class="float-left"><label><input checked="checked" type="checkbox" class="chk_employee" id="chk_employee" name="chk_employee_leave_staggered" onclick="javascript:chkEmployeeLeaveStaggeredSchedule(this);" />All employees</label></div>
            <div class="clear"></div>
        </div>
        <button class="blue_button" onclick="javascript:load_leave_compress_schedule_dt();"><i class="icon-search icon-white"></i> Search</button>
        <div style="float:right">
            <button class="gray_button pull-right department-cancel-filter" style="display:none;">
                Cancel
            </button>
            <div class="dropdown dropright pull-right department-list" id="dropholder" style="width: fit-content;">
                <button class="gray_button">
                    <div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
                    <div class="pull-right">Department<label class="filter-department"></label></div>
                </button>
                <ul class="dropdown-menu">
                <?php
                foreach($department_filter as $department){ ?>
                    <li><a href="javascript:void(0);" data-filter="<?php echo $department->id; ?>" id="<?php echo $department->id; ?>" class="text-black"><?php echo $department->title; ?></a></li>
                <?php }
                ?>
                </ul>
            </div>
        </div>
        <div class="clear"></div>

    </div>
</div>

<div>
    <div class="dt_top_nav">
        <input type="hidden" id="colName" value="" />
        <input type="hidden" id="orderBy" value="ASC" />

        <!--<div class="dt_search" align="right">Search : <input type="text" style="width:25%;" /></div>-->
        <div class="dt_limit">Limit :
            <select id="dt_limit" style="width:50px;" onchange="javascript:load_leave_compress_schedule_dt();">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
        </div>
    </div>

    <div class="group-2">
        <div class="dt_top_nav">
            <select name="chkActionLeave" id="chkActionLeave" onchange="javascript:withSelectedLogsLeave(this.value);" disabled="disabled">
                <option value="">With Selected:</option>
                <option value="update">Batch Update</option>
                <option value="delete">Delete</option>
            </select>
        </div>
    </div>

    <div class="clear"></div>
    <div class="paginator yui-skin-sam"></div>
    <div id="loading_wrapper"></div>
    <table id="" class="formtable">
        <thead>
            <tr>
                <th valign="top" width="2%">
                    <input type="checkbox" id="chkAllLeave" name="chkAllLeave" onchange="chkAllLeave();" original-title="Check All">
                </th>
                <th valign="top" onclick="" width="10%"><strong>Schedule</strong></th>
                <th valign="top" onclick="javascript:sortDt('time');" width="10%"><strong>Employee Code</strong></th>
                <th valign="top" onclick="javascript:sortDt('employee_name');" width="10%"><strong>Employee Name</strong></th>
                <th valign="top" onclick="javascript:sortDt('department');" width="10%"><strong>Department</strong></th>
                <!--<th valign="top" onclick="javascript:sortDt('type');" width="10%"><strong>Device No.</strong></th>-->
                <?php if ($permission_action == Sprint_Modules::PERMISSION_02) { ?>
                    <th valign="top" onclick="javascript:sortDt('type');" width="10%"></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody id="leave_compress_schedule_dt_wrapper">
        </tbody>
    </table>
    <div class="paginator yui-skin-sam"></div>
</div>

<script>
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

    /*$('#employees_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
    	minLength: 1,
    	onlyFromValues: true,
    	queryRemote: true,
    	remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
    }}});*/
</script>