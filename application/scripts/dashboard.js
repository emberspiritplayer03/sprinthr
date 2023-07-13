function loadAttendanceLog()
{
	date = $("#date").val();
	loadAttendanceList(date)
}

function loadAttendanceList(date)
{
 element_id = 'attendance_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("e_id");
				var hash = oRecord.getData("hash");
	
				elCell.innerHTML = "<center> <a href='employee/profile?eid="+id+"&hash="+hash+"#personal_details'>Display</a>"; 
		};
			
	var test = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
		};						
			
		var columns = 	[
						 {key:"date",label:"Date",width:60,resizeable:true,sortable:true},
						
						 {key:"employee_name",label:"Name",width:140,resizeable:true,sortable:true},
						
						 {key:"time",label:"Time",width:48,resizeable:true,sortable:true},
						 {key:"type",label:"Type",width:48,resizeable:true,sortable:true}
						 
						 ];
		var fields =	['id','date','employee_code','employee_name','time','type'];
		var height = 	'auto';
		var width = 	'100%';

		var controller = 'dashboard/_json_encode_attendance_list?date='+date+'&';		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();	
}

function recruitmentSummaryAction()
{
	var r_action = $("#recruitment_action_holder").val();
	if(r_action == 'tabular'){
		loadTabularTotalApplicantByYear();
	}else{
		loadGraphicalTotalApplicantByYear();
	}
}

function loadTabularTotalApplicantByYear()
{
	year = $("#year").val();
	$("#recruitment_action_holder").val("tabular");
	$("#employee_by_year_wrapper").html(loading_image);
	$.post(base_url+'dashboard/_load_tabular_total_applicant_by_year',{year:year},
	function(o){
		$("#employee_by_year_wrapper").html(o);
	});
}

function loadGraphicalTotalApplicantByYear()
{
	year = $("#year").val();
	$("#recruitment_action_holder").val("graphical");
	$("#employee_by_year_wrapper").html(loading_image);
	$.post(base_url+'dashboard/_load_graphical_total_applicant_by_year',{year:year},
	function(o){
		$("#employee_by_year_wrapper").html(o);
	});
}

function loadTotalEmployeesByYear()
{
	year = $("#year").val();
	$.post(base_url+'dashboard/_load_total_employee_by_year',{year:year},
	function(o){
		$("#employee_by_year_wrapper").html(o);
	});
}

function loadEmployeeSummaryByDateRange(date_from,date_to)
{	
	$.post(base_url+'dashboard/_load_employee_summary_by_date_range',{date_from:date_from,date_to:date_to},
	function(o){
		$("#employee_summary_by_date_range_wrapper").html(o);
	});
}