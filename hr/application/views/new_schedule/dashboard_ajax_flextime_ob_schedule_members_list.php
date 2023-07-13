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

        $("#autocomplete_ob_staggered").hide();
        $("#all_emp_ob_staggered").show();
        load_ob_flextime_schedule_dt();
        //$("table").tablesorter();

        var emp_selected = new $.TextboxList('#emp_selected_ob_staggered', {
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
            load_ob_flextime_schedule_dt();
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

            load_ob_flextime_schedule_dt();
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

    function checkUncheck() {
        if ($('input[name="dtrChk[]"]:checked').length > 0) {
            $('#chkAction').attr('disabled', false);
            if ($('input[name="dtrChk[]"]:checked').length == $('input[name="dtrChk[]"]').length) {
                $('#chkAll').attr('checked', true);
            }
        } else {
            $('#chkAction').attr('disabled', true);
        }
    }

    function chkAll() {
        if ($('#chkAll:checked').length) {
            $('#chkAction').attr('disabled', false);
            $('input[name="dtrChk[]"]').attr('checked', true);
        } else {
            $('#chkAction').attr('disabled', true);
            $('input[name="dtrChk[]"]').attr('checked', false);
        }
    }
</script>
<input type="hidden" id="s_from" value="" />
<input type="hidden" id="s_to" value="" />
<input type="hidden" id="s_error_type" value="" />
<input type="hidden" id="s_emp_selected" value="" />
<input type="hidden" id="error_filter" value="" />
<?php if (!empty($_GET['hpid_n'])) { ?>
    <input type="hidden" id="hpid_n" value="<?php echo $_GET['hpid_n']; ?>">
<?php } ?>
<input hidden checked="checked" type="checkbox" class="chk_employee" id="chk_employee" name="chk_employee" onclick="javascript:chkEmployee(this);" />

<div>
    <div class="dt_top_nav">
        <input type="hidden" id="colName" value="" />
        <input type="hidden" id="orderBy" value="ASC" />

        <!--<div class="dt_search" align="right">Search : <input type="text" style="width:25%;" /></div>-->
        <div class="dt_limit">Limit :
            <select id="dt_limit" style="width:50px;" onchange="javascript:load_ob_flextime_schedule_dt();">
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
            <select name="chkAction" id="chkAction" onchange="javascript:withSelectedLogs(this.value);" disabled="disabled">
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
                    <input type="checkbox" id="chkAll" name="chkAll" onchange="chkAll();" original-title="Check All">
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
        <tbody id="ob_flextime_schedule_dt_wrapper">
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