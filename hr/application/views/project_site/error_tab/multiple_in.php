<style>
    .ui-autocomplete-input {
        width: 100%;
    }

    .dt_limit {
        width: 20%;
    }

    .logs-delete-btn {
        margin-left: 10px;
    }

    .dtr-error-list a.active {
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

        $("#autocomplete_multiple_in").hide();
        $("#all_emp_multiple_in").show();
        load_multiple_in_dt('<?php echo $from; ?>', '<?php echo $to; ?>');
        //$("table").tablesorter();

        $('#kdt').fixheadertable({
            //height     : 200, 
            zebra: true,
            sortable: true,
            minColWidth: 50,
            resizeCol: true,
            zebraClass: 'ui-state-active' // default
        });

        var emp_selected = new $.TextboxList('#emp_selected_multiple_in', {
            unique: true,
            plugins: {
                autocomplete: {
                    minLength: 1,
                    onlyFromValues: true,
                    queryRemote: true,
                    remote: {
                        url: base_url + 'project_site/ajax_get_employees_autocomplete'
                    }

                }
            }
        });

        $('ul.textboxlist-bits').attr("title", "Type employee name to see suggestions.");
        $('ul.textboxlist-bits').tipsy({
            gravity: 's'
        });

        $('.cancel-filter').click(function(e) {

            $('.cancel-filter').hide();
            $('#error_filter').val('');
            load_attendance_logs_list_dt();
            $('.dtr-error-list a').removeClass('active');
        });


        $('.dtr-error-list a').click(function(e) {
            var target = $(e.target);
            var filter_type = target.data('filter') ? target.data('filter') : '';

            if (target.hasClass('active')) {
                target.removeClass('active');
                filter_type = '';
                $('.cancel-filter').hide();
            } else {
                $('.dtr-error-list a').removeClass('active');
                target.addClass('active');
                $('.cancel-filter').show();
            }

            $('#error_filter').val(filter_type);

            load_attendance_logs_list_dt();
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
                $('#multiple_in_wrapper').html(o.table);
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
                $('#multiple_in_wrapper').html(o.table);
                $('#loading_wrapper').html('');
                $('.paginator').html(o.paginator)
            }, "json");
    }

    function checkUncheck() {
        if ($('input[name="dtrChkMultipleIn[]"]:checked').length > 0) {
            $('#chkActionMultipleIn').attr('disabled', false);
            if ($('input[name="dtrChkMultipleIn[]"]:checked').length == $('input[name="dtrChkMultipleIn[]"]').length) {
                $('#chkAllMultipleIn').attr('checked', true);
            }
        } else {
            $('#chkActionMultipleIn').attr('disabled', true);
        }
    }

    function chkAllMultipleIn() {
        if ($('#chkAllMultipleIn:checked').length) {
            $('#chkActionMultipleIn').attr('disabled', false);
            $('input[name="dtrChkMultipleIn[]"]').attr('checked', true);
        } else {
            $('#chkActionMultipleIn').attr('disabled', true);
            $('input[name="dtrChkMultipleIn[]"]').attr('checked', false);
        }
    }
</script>

<div id="">
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
                <div id="all_emp_multiple_in" class="float-left" style="width:312px;">
                    <input disabled="disabled" type="text" name="input_disabled" id="input_disabled" style="width:290px; min-width:290px;" value="" />
                </div>
                <div id="autocomplete_multiple_in" class="float-left">
                    <input type="text" name="emp_selected" id="emp_selected_multiple_in" />
                </div>
                <div class="clear"></div>
                <span class="float-left" style=" width:50px;">&nbsp;</span>
                <div class="float-left"><label><input checked="checked" type="checkbox" class="chk_employee" id="chk_employee" name="chk_employee" onclick="javascript:chkEmployeeMultipleIn(this);" />All employees</label></div>
                <div class="clear"></div>
            </div>
            <button class="blue_button" onclick="javascript:load_attendance_logs_list_dt();"><i class="icon-search icon-white"></i> Search</button>
            <div style="float:right">
                <?php echo $error_notification; ?>
            </div>
            <div class="clear"></div>

        </div>
    </div>
    <div class="group-2">
        <div class="dt_top_nav">
            <select name="chkActionMultipleIn" id="chkActionMultipleIn" onchange="javascript:withSelectedLogsMultipleIn(this.value);" disabled="disabled">
                <option value="">With Selected:</option>
                <option value="update">Batch Update</option>
                <option value="delete">Delete</option>
            </select>
        </div>
    </div>

    <div>
        <table id="" class="formtable">
            <thead>
                <tr>
                    <th valign="top" width="2%">
                        <input type="checkbox" id="chkAllErrors" name="chkAll" onchange="chkAllErrors();" original-title="Check All">
                    </th>
                    <th valign="top" width="10%">Schedule</th>
                    <th valign="top" width="10%">Time In</th>
                    <th valign="top" width="10%">Time Out</th>
                    <th valign="top" width="10%">Employee Name</th>
                    <th valign="top" width="10%">Project Site</th>
                    <th valign="top" width="10%"></th>
                </tr>
            </thead>
            <tbody id="multiple_in_wrapper">
            </tbody>
        </table>
    </div>
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