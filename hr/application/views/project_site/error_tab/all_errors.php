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

        $("#autocomplete").hide();
        $("#all_emp").show();
        load_all_errors_dt('<?php echo $from; ?>', '<?php echo $to; ?>');
        //$("table").tablesorter();

        $('#kdt').fixheadertable({
            //height     : 200, 
            zebra: true,
            sortable: true,
            minColWidth: 50,
            resizeCol: true,
            zebraClass: 'ui-state-active' // default
        });

        var emp_selected = new $.TextboxList('#emp_selected', {
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
                $('#all_errors_wrapper').html(o.table);
                $('.paginator').html(o.paginator)
            }, "json");
    }

    /*function sortDt(sortColumn) {
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
                $('#all_errors_wrapper').html(o.table);
                $('#loading_wrapper').html('');
                $('.paginator').html(o.paginator)
            }, "json");
    }*/

    function checkUncheck() {
        if ($('input[name="dtrChkAllErrors[]"]:checked').length > 0) {
            $('#chkActionAllErrors').attr('disabled', false);
            if ($('input[name="dtrChkAllErrors[]"]:checked').length == $('input[name="dtrChkAllErrors[]"]').length) {
                $('#chkAll').attr('checked', true);
            }
        } else {
            $('#chkActionAllErrors').attr('disabled', true);
        }
    }

    function chkAllErrors() {
        if ($('#chkAllErrors:checked').length) {
            $('#chkActionAllErrors').attr('disabled', false);
            $('input[name="dtrChkAllErrors[]"]').attr('checked', true);
        } else {
            $('#chkActionAllErrors').attr('disabled', true);
            $('input[name="dtrChkAllErrors[]"]').attr('checked', false);
        }
    }
</script>

<div id="">
    
    <div class="group-2">
        <div class="dt_top_nav">
            <select name="chkActionAllErrors" id="chkActionAllErrors" onchange="javascript:withSelectedLogsAllErrors(this.value);" disabled="disabled">
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
                    <th valign="top" width="10%">Employee Code</th>
                    <th valign="top" width="10%">Employee Name</th>
                    <th valign="top" width="10%">Date</th>
                    <th valign="top" width="10%">Time In</th>
                    <th valign="top" width="10%">Time Out</th>
                    <th valign="top" width="10%">Project Site</th>
                    <th valign="top" width="10%">Device No.</th>
                    <th valign="top" width="10%"></th>
                </tr>
            </thead>
            <tbody id="all_errors_wrapper">
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