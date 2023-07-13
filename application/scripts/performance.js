function load_add_performance() {

	$("#add_performance_button_wrapper").hide();
	$("#performance_form_wrapper").show();
}

function loadGoto() {
	//alert('test');
	  $("#dropcontent").toggle();
	 
     // $("#dropButton_"+id).toggleClass('active');
}


function load_performance_datatable()
{
	addActiveState('btn_viewall','btn-small');	
	element_id = 'performance_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					//elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
					var id = oRecord.getData("id");
	
					elCell.innerHTML = "<a href='performance_details?performance_id="+id+"'>Display</a>"; 
			};						
			
		var columns = 	[
						 {key:"title",label:"Title",width:100,resizeable:true,sortable:true},									
						 {key:"job_name",label:"Job Name",width:90,resizeable:true,sortable:true},
						 {key:"description",label:"Description",width:90,resizeable:true,sortable:true},
						 {key:"date_created",label:"Date Created",width:90,resizeable:true,sortable:true},
						 {key:"created_by",label:"Created By",width:90,resizeable:true,sortable:true},
						 {key:"created_by",label:"Action",width:50,resizeable:true,sortable:true,formatter:action}
						 ];
		var fields =	['id','title','job_name','description','date_created','created_by'];
		var height = 	'auto';
		var width = 	'100%';
		
		var controller = 'settings/_json_encode_performance_list?';		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
} 

function load_archive_performance_datatable()
{
	addActiveState('btn_viewallarchives','btn-small');	
	element_id = 'performance_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					//elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
					var id = oRecord.getData("id");
	
					elCell.innerHTML = "<a href='javascript:void(0);' onclick='javascript:restorePerformanceTemplate(\"" + id + "\")'>Restore</a>"; 
			};						
			
		var columns = 	[
						 {key:"title",label:"Title",width:100,resizeable:true,sortable:true},									
						 {key:"job_name",label:"Job Name",width:90,resizeable:true,sortable:true},
						 {key:"description",label:"Description",width:90,resizeable:true,sortable:true},
						 {key:"date_created",label:"Date Created",width:90,resizeable:true,sortable:true},
						 {key:"created_by",label:"Created By",width:90,resizeable:true,sortable:true},
						 {key:"created_by",label:"Action",width:50,resizeable:true,sortable:true,formatter:action}
						 ];
		var fields =	['id','title','job_name','description','date_created','created_by'];
		var height = 	'auto';
		var width = 	'100%';
		
		var controller = 'settings/_json_encode_is_archive_performance_list?';		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
} 

function loadPerformanceDetailsForm() {
	$("#performance_details_form").show();
	$("#performance_details_table_wrapper").hide();
	$("#performance_details_edit_form_wrapper").show();
}

function loadPerformanceDetailsTable() {
	clearPerformanceDetailsInlineErrorForm();
	$("#performance_details_form").hide();
	$("#performance_details_table_wrapper").show();
}

function clearPerformanceDetailsInlineErrorForm()
{
	$("#performance_details_form").validationEngine('hide');
}

function cancel_add_performance_form() {
	$("#performance_form").validationEngine('hide');
	$("#add_performance_button_wrapper").show();
	$("#performance_form_wrapper").hide();
}

function load_add_performance_confirmation(performance_id) {
	$("#confirmation").html(loading_message);
	$.post(base_url + 'settings/_load_add_performance_confirmation',{},function(o) {
		$("#confirmation").html(o)								 
	})
	
	encrypt = performance_id;
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 207,				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	 
				   window.location = "performance_template"; 
                },
                buttons: {
					'View Performance Details' : function(){
							load_performance_datatable();					
						  	disablePopUp();
						  	$dialog.dialog("close");
							window.location = "performance_details?performance_id="+encrypt;
                    },
					'Cancel' : function(){
							load_performance_datatable();					
						  	disablePopUp();
						  	$dialog.dialog("close");
                    }
                }
            }).show();
}

function loadPerformanceDetailsSettings(id)
{
	var performance_id = id;	
	$.post(base_url+'settings/_load_performance_details',{performance_id:performance_id},
	function(o){
		$("#performance_details_table_wrapper").html(o);
	});	
}

function loadKpiAddForm()
{
	
	$("#kpi_add_form_wrapper").show();
	$("#kpi_table_wrapper").hide();
	$("#kpi_edit_form_wrapper").hide();
	$("#kpi_add_button_wrapper").hide();

}

function clearKpiInlineErrorForm(id)
{
	$("#kpi_edit_form_"+id).validationEngine('hide');
	$("#kpi_add_form_"+id).validationEngine('hide');	
}

function loadKpiTable() {
	clearKpiInlineErrorForm();
	$("#kpi_add_form_wrapper").hide();
	$("#kpi_edit_form_wrapper").html('');
	$("#kpi_table_wrapper").show();
	$("#kpi_add_button_wrapper").show();
}


function loadPerformanceKpi(id)
{
	var performance_id = id;
	loadKpiTable();
	$("#kpi_table_wrapper").html(loading_message);	
	
	$.post(base_url+'settings/_load_performance_kpi',{performance_id:performance_id},
	function(o){
		$("#kpi_table_wrapper").html(o);		
	});
	
	$.post(base_url+'settings/_load_add_form_kpi',{performance_id:performance_id},
	function(o){
		$("#kpi_add_form_wrapper").html(o);		
	});
	
	
}

function displayKpiEditForm(id) 
{	
	clearKpiInlineErrorForm(id);
	var kpi_id = id;
	
		var dialog_id = $("#kpi_edit_form_dialog_"+id);
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: "Confirmation",
			resizable: true,
			width: 430,
			height: "auto",
			modal: true,
			close: function() {
					   $dialog.dialog("destroy");
					   $dialog.hide();
						disablePopUp();
					}//,
			//buttons: {
					//'Hide' : function(){
						//$dialog.dialog("close");
						//disablePopUp();
						
					//}
				//}
			}).show();		

}

function loadKpiDeleteDialog(id,performance_id)
{
	clearKpiInlineErrorForm(id);
	var kpi_id = id;
	var performance_id = performance_id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#kpi_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 200,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'settings/_delete_kpi',{kpi_id:kpi_id,performance_id:performance_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						loadPerformanceKpi(performance_id);
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}


function moveKpiUp(kpi_id,performance_id) {	
	$.post(base_url+'settings/_kpi_move_up',{kpi_id:kpi_id},
	function(o){
		loadPerformanceKpi(performance_id);
	});	
}

function displayDelete(id) {
	$("#"+id).show();
}

function hideDelete(id) {
	$(".delete_kpi_nav").hide();
}

function load_add_employee_performance()
{
	$("#employee_performance_add_form_wrapper").show();
	$("#add_employee_performance_button_wrapper").hide();
}

function cancel_add_employee_performance_form()
{
	$("#employee_performance_add_form_wrapper").hide();
	$("#add_employee_performance_button_wrapper").show();
}

function load_employee_performance_datatable(searched)
{
	element_id = 'employee_performance_datatable';
			
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
				var status = oRecord.getData("status");
				var hash = oRecord.getData("hash");
				if(status=='pending') {
					elCell.innerHTML = "<a class='btn btn-mini' href='performance/performance_details?performance="+hash+"'>"+status+"</a>"; 
				}else {
					elCell.innerHTML = "<a class='btn btn-mini' href='performance/performance_summary?performance="+hash+"'><i class='icon-ok'></i> "+status+"</a>"; 
				}
					
			};						
			
		var columns = 	[
						
						 {key:"employee_name",label:"Employee Name",width:175,resizeable:true,sortable:true},
						 {key:"position",label:"Position",width:115,resizeable:true,sortable:true},
						 {key:"period_from",label:"Period From",width:170,resizeable:true,sortable:true},
						 {key:"period_to",label:"Period To",width:170,resizeable:true,sortable:true},
						 {key:"review_by",label:"Reviewer",width:120,resizeable:true,sortable:true},
						 {key:"status",label:"Status",width:110,resizeable:true,sortable:true,formatter:action}
						 ];
		var fields =	['id','employee_name','position','period_from','period_to','review_by','status','hash'];
		var height = 	'auto';
		var width = 	'100%';

		if(searched) {			
			var controller = 'performance/_json_encode_advance_search_performance_list?search='+searched+'&';				
		}else{					
			var controller = 'performance/_json_encode_employee_performance_list?';		
		}
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
}

//for quick dynamic search
function loadCategory()
{
	
	$.fn.setCursorToTextEnd = function() {
        $initialVal = this.val();
        this.val($initialVal + ' ');
        this.val($initialVal);
    };

	if($("#category").val()=='Hired Date:' || $("#category").val() == 'Period:' || $("#category").val()=='Terminated Date:' || $("#category").val()=='End of Contract:' || $("#category").val()=='Birthdate:') {
		$("#from_to").show();
	}
	
	if($("#category").val()=='Department:'  ) {
		$("#department_option").show();
	}else {
		$("#department_option").hide();
	}
	
	if($("#category").val()=='Position:'  ) {
		$("#position_option").show();
	}else {
		$("#position_option").hide();
	}
	
	if($("#category").val()=='Employment Status:'  ) {
		$("#employment_status_option").show();
	}else {
		$("#employment_status_option").hide();
	}
	
	if($("#search").val()=='') {
		$("#search").val($("#search").val()+$("#category").val());
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	}else {
		$("#search").val($("#search").val()+","+$("#category").val());		
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	}
	$("#category")[0].selectedIndex = 0;
	
}

function loadPosition() {
	
		$("#search").val($("#search").val()+$("#position").val());
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	$("#position_option").hide();
}

function loadPeriodFromTo() {	
		var period_from_to = $("#period_from").val() + " to " + $("#period_to").val();
		$("#search").val($("#search").val()+period_from_to);
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	$("#from_to").hide();
}


function searchEmployee() {	
	var searched = $("#search").val();	
	load_employee_performance_datatable(searched);
	//load_total_search(searched);
}

function hideApplicantSummary() {
	$("#employee_search_container").hide();	
}

function addActiveState(obj_id,class_name)
{
	$('.' + class_name).removeClass('active');
	$("#" + obj_id).addClass("active");
}
