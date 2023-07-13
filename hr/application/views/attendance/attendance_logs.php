<style>
.ui-autocomplete-input{width:100%;}
.dt_limit {width:20%;}
.logs-delete-btn{margin-left:10px;}

.dtr-error-list a.active{
	background-color: #0081c2;
    color: #ffffff;
}
.group-2{
	display:grid;
	grid-template-columns: 1fr 1fr;
}	
</style>
<script>	
	$(function() {
		
		$("#btn-add-attendance-log").click(function(){
			addAttendanceLog();
		});

		$("#autocomplete").hide();
		$("#all_emp").show();
		load_attendance_logs_list_dt();		
		//$("table").tablesorter();
		
		$('#kdt').fixheadertable({ 
			//height     : 200, 
			zebra      : true,
			sortable    : true,			
			minColWidth : 50, 
			resizeCol   : true,			
			zebraClass : 'ui-state-active' // default
		});
		
		var emp_selected = new $.TextboxList('#emp_selected', {unique: true,plugins: {
			autocomplete: {
				minLength: 1,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'attendance/ajax_get_employees_autocomplete'}
			
			}
		}});
		
		$('ul.textboxlist-bits').attr("title","Type employee name to see suggestions.");
		$('ul.textboxlist-bits').tipsy({gravity: 's'});	

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
			}
			else {
				$('.dtr-error-list a').removeClass('active');
				target.addClass('active');
				$('.cancel-filter').show();
			}

			$('#error_filter').val(filter_type);

			load_attendance_logs_list_dt();
		});
		
	});
	
	function gotoPage(displayStart,paginatorIndex){		
		var limit      = $("#dt_limit").val();
		var orderBy    = $("#orderBy").val();
		var sortColumn = $("#colName").val();
		
		var date_from  = $("#s_from").val();
		var date_to    = $("#s_to").val();
		var error_type = $("#s_error_type").val();		
		var emp_sel	   = $("#s_emp_selected").val();	

		var filter     = $("#error_filter").val();
		
		$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
		$.post(base_url + 'attendance/_load_attendance_logs_dt',{sortColumn:sortColumn,orderBy:orderBy,displayStart:displayStart,limit:limit,paginatorIndex:paginatorIndex,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel,filter:filter},
		function(o){
			$('#loading_wrapper').html('');
			$('#attendance_logs_dt_wrapper').html(o.table);
			$('.paginator').html(o.paginator)
		},"json");
	}
	
	function sortDt(sortColumn){		
		var limit   = $("#dt_limit").val();		
		var orderBy = $("#orderBy").val();
		
		var date_from  = $("#s_from").val();
		var date_to    = $("#s_to").val();
		var error_type = $("#s_error_type").val();
		var emp_sel	   = $("#s_emp_selected").val();	
		
		if(orderBy == 'ASC'){
			$("#orderBy").val("DESC");
			orderBy = 'DESC';
		}else{
			$("#orderBy").val("ASC");
			orderBy = 'ASC';
		}
				
		$('#loading_wrapper').html('<div id="dt_processing">' + loading_image + '</div>');
		$.post(base_url + 'attendance/_load_attendance_logs_dt',{limit:limit,sortColumn:sortColumn,orderBy:orderBy,date_from:date_from,date_to:date_to,error_type:error_type,emp_sel:emp_sel},
		function(o){
			$("#colName").val(sortColumn);			
			$('#attendance_logs_dt_wrapper').html(o.table);
			$('#loading_wrapper').html('');
			$('.paginator').html(o.paginator)
		},"json");
	}

	function checkUncheck() {
		if ($('input[name="dtrChk[]"]:checked').length > 0) {
			$('#chkAction').attr('disabled', false);

			if ($('input[name="dtrChk[]"]:checked').length == $('input[name="dtrChk[]"]').length) {
				$('#chkAll').attr('checked', true);
			}
		}
		else {
			$('#chkAction').attr('disabled', true);
		}
	}

	function chkAll() {
		if ($('#chkAll:checked').length) {
			$('#chkAction').attr('disabled', false);
			$('input[name="dtrChk[]"]').attr('checked', true);
		}
		else {
			$('#chkAction').attr('disabled', true);
			$('input[name="dtrChk[]"]').attr('checked', false);
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
        <?php if(!empty($_GET['hpid_n'])) { ?>
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
        <!--Name <input type="text" name="employees_autocomplete" id="employees_autocomplete" />-->
        &nbsp;From:&nbsp;<input type="text" name="from" id="from" class="input-small" style="min-width:8px;" value="<?php echo $from;?>" />
        &nbsp;To:&nbsp;<input type="text" name="to" class="input-small" style="min-width:8px;" id="to" value="<?php echo $to;?>" />
        <!--&nbsp;Error:&nbsp;<select name="error_type" class="select_option_sched" id="error_type">
            <option <?php echo ($error_type == G_Attendance_Log::LOGS) ? 'selected="selected"' : '' ;?> value="<?php echo G_Attendance_Log::LOGS; ?>">-</option>
            <option <?php echo ($error_type == G_Attendance_Log::INCOMPLETE_SWIPE) ? 'selected="selected"' : '' ;?> value="<?php echo G_Attendance_Log::INCOMPLETE_SWIPE; ?>"><?php echo G_Attendance_Log::INCOMPLETE_SWIPE; ?></option>
            <option <?php echo ($error_type == G_Attendance_Log::MULTIPLE_SWIPE) ? 'selected="selected"' : '' ;?> value="<?php echo G_Attendance_Log::MULTIPLE_SWIPE; ?>"><?php echo G_Attendance_Log::MULTIPLE_SWIPE; ?></option>    
        </select>  -->
        <button class="blue_button" onclick="javascript:load_attendance_logs_list_dt();"><i class="icon-search icon-white"></i> Search</button>
        <div style="float:right">
		<?php echo $error_notification; ?>
		</div>
		<div class="clear"></div>
        
    </div>
</div>
<!--<a href="<?php //echo url("attendance/attendance_logs?from={$from}&to={$to}&error_type={$error_type}&download=1");?>">Download Result</a>-->
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
        
        <a class="gray_button float-right" href="javascript:void(0);" onclick="javascript:download_attendance_log();"><i class="icon-excel icon-custom"></i> Download Result</a>
        <?php echo $btn_import_dtr; ?>
        <?php echo $btn_add_attendance_log; ?>
        <?php echo $btn_sync_attendance_log; ?>
    </div>

     <div class="group-2">
		<div class="dt_top_nav">
			<select name="chkAction" id="chkAction" onchange="javascript:withSelectedLogs(this.value);" disabled="disabled">
				<option value="">With Selected:</option>            
				<option value="update">Batch Update</option>      
				 <option value="delete">Delete</option>            
			</select>
		</div>
		<div class="dt_top_nav" style="margin-left:auto; margin-top:6px;">
			<label for="">Filter by:
			<select name="filterByDevice" id="filterByDevice" onchange="javascript:load_attendance_logs_list_dt();"> 
				<option value="">--Select Device--</option>   
				<option value="--no_device--">--no_device--</option>   
				<?php foreach($devices as $device): ?>
					<option value="<?=$device['machine_no']?>"><?=$device['machine_no'].' - '.$device['device_name']?></option>    
				<?php endforeach;?>
			</select>
			</label>
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
          	<th valign="top" onclick="javascript:sortDt('employee_code');" width="10%"><strong>Employee Code</strong></th>
            <th valign="top" onclick="javascript:sortDt('employee_name');" width="10%"><strong>Employee Name</strong></th>
            <th valign="top" onclick="javascript:sortDt('date');" width="10%"><strong>Date</strong></th>
            <th valign="top" onclick="javascript:sortDt('time');" width="10%"><strong>Time</strong></th>
            <th valign="top" onclick="javascript:sortDt('type');" width="10%"><strong>Type</strong></th>
             <th valign="top" onclick="javascript:sortDt('type');" width="10%"><strong>Device No.</strong></th>
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
            	<th valign="top" onclick="javascript:sortDt('type');" width="10%"></th>  
            <?php } ?>          
          </tr>     
        </thead>
        <tbody id="attendance_logs_dt_wrapper">   
        </tbody>
    </table>
    <div class="paginator yui-skin-sam"></div>
</div>    


<script>
$("#from").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true
});
$("#to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

/*$('#employees_autocomplete').textboxlist({unique: true, plugins: {autocomplete: {
	minLength: 1,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
}}});*/
</script>