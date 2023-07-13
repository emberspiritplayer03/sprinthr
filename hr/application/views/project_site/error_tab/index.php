<script language="javascript">
  $('.tooltip').tipsy({
    gravity: 's'
  });

  $(function() {
    $("#attendance-tabs").tabs();
    loadNewAttendanceSelectedTab();
  });
</script>

<style>
  .noti_count { display:block; position:absolute; z-index:100; font-size:11px; right:-8px; top:-12px; color:#ffffff; padding:0 4px; min-width:12px; text-align:center;
  background-color: #2690dd;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;-moz-box-shadow: 0px 1px 1px #222222;-webkit-box-shadow: 0px 1px 1px #222222;box-shadow: 0px 1px 1px #222222;filter: progid:DXImageTransform.Microsoft.Shadow(strength = 1, direction = 180, color = '#222222');-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(strength = 1, Direction = 180, Color = '#222222')";filter: progid:DXImageTransform.Microsoft.gradient(startColorstr = '#fd5252', endColorstr = '#f60304');-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr = '#fd5252', endColorstr = '#f60304')";background-image: -moz-linear-gradient(top, #fd5252, #f60304);background-image: -ms-linear-gradient(top, #fd5252, #f60304);background-image: -o-linear-gradient(top, #fd5252, #f60304);background-image: -webkit-gradient(linear, center top, center bottom, from(#fd5252), to(#f60304));background-image: -webkit-linear-gradient(top, #fd5252, #f60304);background-image: linear-gradient(top, #fd5252, #f60304);-moz-background-clip: padding;-webkit-background-clip: padding-box;background-clip: padding-box;}

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
      load_all_errors_dt();
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

      load_all_errors_dt();
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
    $.post(base_url + 'project_site/_load_all_errors_dt', {
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
</script>

<div>
  <div class="dt_top_nav">
    <input type="hidden" id="colName" value="" />
    <input type="hidden" id="orderBy" value="ASC" />

    <!--<div class="dt_search" align="right">Search : <input type="text" style="width:25%;" /></div>-->
    <div class="dt_limit">Limit :
      <select id="dt_limit" style="width:50px;" onchange="javascript:load_error_tab();">
        <option value="10">10</option>
        <option value="15">15</option>
        <option value="20">20</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="200">200</option>
      </select>
    </div>

    <!--<a class="gray_button float-right" href="javascript:void(0);" onclick="javascript:download_attendance_log();"><i class="icon-excel icon-custom"></i> Download Result</a>-->
    <?php echo $btn_payroll; ?>
    <?php echo $btn_employee_list; ?>
    <!--<?php echo $btn_sync_attendance_log; ?>-->
  </div>

  <div class="clear"></div>
  <div class="paginator yui-skin-sam"></div>
</div>

<div id="attendance-tabs">
  <ul>
    <li><a id="all_errors" class="aload-data" href="#all-attendance-tab">All Errors <span id="noti_count" class="noti_count"><?php echo $all_errors?></span></a></li>
    <li><a id="incomplete_logs" class="aload-data" href="#incomplete-logs-tab">Incomplete Logs <span class="noti_count"><?php echo $incomplete_logs?></span></a></li>
    <li><a id="multiple_in" class="aload-data" href="#multiple-in-tab">Multiple IN <span class="noti_count"><?php echo $multiple_time_in?></span></a></li>
    <li><a id="multiple_out" class="aload-data" href="#multiple-out-tab">Multiple Out <span class="noti_count"><?php echo $multiple_time_out?></span></a></li>
    <!--<li><a id="incomplete_break_logs" class="aload-data" href="#incomplete-breaklogs-tab">Incomplete Break Logs </a></li>
    <li><a id="no_break_logs" class="aload-data" href="#no-breaklogs-tab">No Break Logs </a></li>
    <li><a id="early_break_in" class="aload-data" href="#early-break-in-tab">Early Break Out </a></li>
    <li><a id="late_break_out" class="aload-data" href="#late-break-out-tab">Late Break Out </a></li>-->
  </ul>

  <div id="all-attendance-tab">
    <div class="attendance-all-wrapper">1</div>
  </div>

  <div id="incomplete-logs-tab">
    <div class="attendance-incomplete-wrapper">2</div>
  </div>


  <div id="multiple-in-tab">
    <div class="attendance-multiple-in-wrapper">3</div>
  </div>

  <div id="multiple-out-tab">
    <div class="attendance-multiple-out-wrapper">3</div>
  </div>

  <!--<div id="incomplete-breaklogs-tab">
    <div class="attendance-incomplete-breaklogs-wrapper">4</div>
  </div>

  <div id="no-breaklogs-tab">
    <div class="attendance-no-breaklogs-wrapper">5</div>
  </div>

  <div id="early-break-in-tab">
    <div class="attendance-early-break-in-wrapper">6</div>
  </div>

  <div id="late-break-out-tab">
    <div class="attendance-late-break-out-wrapper">7</div>
  </div>-->


</div>