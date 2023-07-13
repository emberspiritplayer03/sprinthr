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

		$("#autocomplete").hide();
		$("#all_emp").show();
		load_attendance_logs_list_dt();
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

		$('.schedule-cancel-filter').click(function(e) {

			$('.schedule-cancel-filter').hide();
			$('#error_filter').val('');
			load_attendance_logs_list_dt();
			$('.schedule-list a').removeClass('active');
		});


		$('.schedule-list a').click(function(e) {
			var target = $(e.target);
			var filter_type = target.data('filter') ? target.data('filter') : '';

			if (target.hasClass('active')) {
				target.removeClass('active');
				filter_type = '';
				$('.schedule-cancel-filter').hide();
			} else {
				$('.schedule-list a').removeClass('active');
				target.addClass('active');
				$('.schedule-cancel-filter').show();
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
<script type="text/javascript">
	$(function() {
		
        $("#tabs").tabs({});
		//load_staggered_schedule("#schedule");

		$(".no_error").click(function(){
			load_no_error_tab('#no_error');
		});
        $(".inc_logs").click(function(){
			load_incomplete_logs_tab('#incomplete_logs');
		});
        $(".multiple_in").click(function(){
			load_multiple_in_tab('#multiple_in');
		});
        $(".multiple_out").click(function(){
           load_multiple_out_tab('#multiple_out');
		});
        $(".no_schedule").click(function(){
			load_no_sched_tab('#no_sched');
		});
        $(".conflict_sched").click(function(){
            load_conflict_sched_tab('#conflict_sched');
		});
        $(".leave").click(function(){
			load_leave_tab('#leave');
		});
		$(".rd").click(function(){
			load_rd_tab('#rd');
		});
		$(".ob").click(function(){
			load_ob_tab('#ob');
		});

	});
</script>

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
		<li><a href="#tabs-1">All</a></li>
		<li><a class="no_error" href="#tabs-2">No Error</a></li>
		<li><a class="inc_logs" href="#tabs-3">Inc Logs</a></li>
		<li><a class="multiple_in" href="#tabs-4">Multiple In</a></li>
		<li><a class="multiple_out" href="#tabs-5">Multiple Out</a></li>
		<li><a class="no_schedule" href="#tabs-6">No Sched</a></li>
		<li><a class="conflict_sched" href="#tabs-7">Conflict Sched</a></li>
		<li><a class="leave" href="#tabs-8">Leave</a></li>
		<li><a class="rd" href="#tabs-9">RD</a></li>
		<li><a class="ob" href="#tabs-10">OB</a></li>
	</ul>
	<div id="tabs-1">
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
					<div id="all_emp" class="float-left" style="width:312px;">
						<input disabled="disabled" type="text" name="input_disabled" id="input_disabled" style="width:290px; min-width:290px;" value="" />
					</div>
					<div id="autocomplete" class="float-left">
						<input type="text" name="emp_selected" id="emp_selected" />
					</div>
					<div class="clear"></div>
					<span class="float-left" style=" width:50px;">&nbsp;</span>
					<div class="float-left"><label><input checked="checked" type="checkbox" class="chk_employee" id="chk_employee" name="chk_employee" onclick="javascript:chkEmployee(this);" />All employees</label></div>
					<div class="clear"></div>
				</div>
				<button class="blue_button" onclick="javascript:load_attendance_logs_list_dt();"><i class="icon-search icon-white"></i> Search</button>
				<div style="float:right">
					<?php echo $department; ?>
					<?php echo $schedule; ?>
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
					<select id="dt_limit" style="width:50px;" onchange="javascript:load_attendance_logs_list_dt();">
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
				<?php echo $btn_sync_attendance_log; ?>
				<?php echo $btn_update_attendance_log; ?>
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
						<th valign="top" onclick="javascript:sortDt('time');" width="10%"><strong>Time In</strong></th>
						<th valign="top" onclick="javascript:sortDt('type');" width="10%"><strong>Time Out</strong></th>
						<th valign="top" onclick="javascript:sortDt('employee_name');" width="10%"><strong>Employee Name</strong></th>
						<th valign="top" onclick="javascript:sortDt('type');" width="10%"><strong>Project Site</strong></th>
						<!--<th valign="top" onclick="javascript:sortDt('type');" width="10%"><strong>Device No.</strong></th>-->
						<th valign="top" width="10%"></th>
					</tr>
				</thead>
				<tbody id="attendance_logs_dt_wrapper">
				</tbody>
			</table>
			<div class="paginator yui-skin-sam"></div>
		</div>
	</div>

	<div id="tabs-2">
		<div id="no_error"></div>
	</div>
	
	<div id="tabs-3">
		<div id="incomplete_logs"></div>
	</div>

	<div id="tabs-4">
		<div id="multiple_in"></div>
	</div>

	<div id="tabs-5">
		<div id="multiple_out"></div>
	</div>

	<div id="tabs-6">
		<div id="no_sched"></div>
	</div>

	<div id="tabs-7">
		<div id="conflict_sched"></div>
	</div>

	<div id="tabs-8">
		<div id="leave"></div>
	</div>

	<div id="tabs-9">
		<div id="rd"></div>
	</div>

	<div id="tabs-10">
		<div id="ob"></div>
	</div>

</div>
<?php include_once('includes/modal_forms.php'); ?>


<script>
	$(function() {
		$("#tabs").tabs();
	});

	$("#date").datepicker({
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