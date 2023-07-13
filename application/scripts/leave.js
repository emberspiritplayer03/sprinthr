function importLeaveCredits() {
    _importLeaveCredits({
        onLoading: function() {
            showLoadingDialog('Loading...');
        },
        onImported: function(o) {
            closeTheDialog();
            closeDialog('#_new_dialog_');            
            showOkDialog(o.message);
        },
        onImporting: function() {
            closeDialog('#' + DIALOG_CONTENT_HANDLER);
            $("body").append("<div id='_new_dialog_'></div>");
            dialogGeneric('#_new_dialog_',{width:200, height:120, message:'<div align="center" style="margin-top:20px">' + loading_image + ' Importing...</center></div>', title:'Status'});
        },
        onError: function(o) {
            closeDialog('#_new_dialog_');
            $('#_new_dialog_').remove();
            showOkDialog(o.message);
        }
    });
}

function closeTheDialog() {
    closeDialog('#' + DIALOG_CONTENT_HANDLER, '#edit_attendance');
}

function enableDisableWithSelected(form)
{
	var check = countChecked();		
	if(check > 0){
		$("#chkAction").removeAttr('disabled');
	}else{
		$("#chkAction").attr('disabled',true);
	}
}

function archivesEnableDisableWithSelected(form)
{	
	var check = countChecked(form);		
	if(check > 0){
		if(form == 1){
			$("#chkAction").removeAttr('disabled');
		}else{
			$("#chkActionSub").removeAttr('disabled');
		}
	}else{
		if(form == 1){
			$("#chkAction").attr('disabled',true);
		}else{
			$("#chkActionSub").attr('disabled',true);
		}
	}
}

$(function(){
	function hashCheck(){
        var hash = window.location.hash;
		loadPage(hash);
    }
    hashCheck();	
});

function hashClick(hash,options)
{	

	var hash = hash;
	var eid = options.eid || '';
	var id = options.id || '';
	

	if(eid!='' || id!='') {
		$("#eid").val(eid);
		$("#id").val(id);
		hash ='#employee_leave';		
	}
	loadPage(hash); 	
}

function loadPage(hash) 
{
	hide_all_canvass();
	var eid = $("#eid").val();
	var id = $("#id").val();
	if(hash=='#list' || hash=='' ) {		
		displayPage({canvass:'#list_wrapper',parameter:'leave/_load_list'});
	}else if (hash=='#employee_leave') {
		$("#employee_leave_profile_wrapper").html('');
		if( $("#list_wrapper").html()=='' ) {
			displayPage({canvass:'#list_wrapper',parameter:'leave/_load_list'});
			$("#list_wrapper").hide();
		}
		displayPage({canvass:'#employee_leave_profile_wrapper',parameter:'leave/_load_employee_leave?lid='+id+'&eid='+eid});
	}
}

function hide_all_canvass()
{
	$("#list_wrapper").hide();	
	$("#employee_leave_profile_wrapper").hide();	
}

function load_employee_leave_datatable(searched)
{
	element_id = 'employee_leave_datatable';

		//leave/leave_profile?lid="+id+"&eid="+employee_id+"&hash="+hash+"

		var columns = 	[
						 {key:"date_applied",label:"Date Filed",width:130,resizeable:true,sortable:true},
						 {key:"employee_name",label:"Employee Name",width:150,resizeable:true,sortable:true},
						 {key:"leave_type",label:"Leave Type",width:120,resizeable:true,sortable:true},
						 {key:"date_start",label:"Date Start",width:140,resizeable:true,sortable:true},
						 {key:"date_end",label:"Date End",width:140,resizeable:true,sortable:true},
						  {key:"is_approved",label:"Status",width:60,resizeable:true,sortable:true},
						  {key:"action",label:"Action",width:45,resizeable:true,sortable:true}
						 ];
		var fields =	['id','employee_id','date_applied','employee_name','is_approved','hash','leave_type','date_start','date_end','action'];
		var height = 	'auto';
		var width = 	'100%';

		if(searched) {
			var controller = 'leave/_json_encode_employee_leave_list?search='+searched+'&';			
		}else {
			var controller = 'leave/_json_encode_employee_leave_list?';			
		}
		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();		
}

function searchLeave() {
	var searched = $("#search").val();
	load_employee_leave_datatable(searched);
	//load_total_search(searched);
}

function load_total_search(searched) {
	$.post(base_url+'leave/_get_total_result',{searched:searched},
	function(o){
		$("#total_result_wrapper").html(o);	
	});	
}

function loadLeaveListDatatable()
{
	$("#list_wrapper").show();	
	$("#employee_leave_profile_wrapper").hide();		
}

function load_add_leave()
{
	$("#employee_leave_form_wrapper").show();
	$("#add_employee_leave_button_wrapper").hide();
	createFormToken();
}

function cancel_add_employee_leave_form()
{
	clearFormError();
	$("#employee_leave_form_wrapper").hide();
	$("#add_employee_leave_button_wrapper").show();
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function computeDays(start,end) {	
	var start = new Date(start);	
	var end = new Date(end);
	var diff = new Date(end - start);
	var days = diff/1000/60/60/24;
	
	if(days>=0) {
		var length = days+1;  //=> 8.525845775462964
	}else {
		var length = 0;
	}
	
	return length;
}

function computeDaysWithHalfDay(start,end,addHalfDay,deductHalfDay) {	
	var start = new Date(start);	
	var end = new Date(end);
	var diff = new Date(end - start);
	var days = diff/1000/60/60/24;
	
	if(days>=0) {
		var length = days+1;  //=> 8.525845775462964
	}else {
		var length = 0;
	}
	
	//Halfday	
	var total = 0;
	if($('#' + addHalfDay).is(':checked')){total = total - 0.5;}
	if($('#' + deductHalfDay).is(':checked')){total = total - 0.5;}
	
	length = length + total;
	
	return length;
}

function wrapperComputeDaysWithHalfDay(addHalfDay,deductHalfDay,outputId) {
	var output  = computeDaysWithHalfDay($("#date_start").val(),$("#date_end").val(),addHalfDay,deductHalfDay);					
	$("#" + outputId).val(output);

}

function wrapperEditComputeDaysWithHalfDay(addHalfDay,deductHalfDay,outputId) {
	var output  = computeDaysWithHalfDay($("#edit_date_start").val(),$("#edit_date_end").val(),addHalfDay,deductHalfDay);					
	$("#" + outputId).val(output);

}

function wrapperComputeDaysWithHalfDay(addHalfDay,deductHalfDay,outputId) {
	var output  = computeDaysWithHalfDay($("#date_start").val(),$("#date_end").val(),addHalfDay,deductHalfDay);					
	$("#" + outputId).val(output);

}


function applyHalfDay(addHalfday, deductHalfDay) {
	var addH  = $('#' + addHalfday).attr('checked')?true:false;
	var dedH  = $('#' + deductHalfDay).attr('checked')?true:false;
	var total = 0;
	if(addH = true){total = total + 0.5;}
	if(dedH = true){total = total - 0.5;}
	
	return total; 

}

function load_show_specific_schedule() {
	var start_date 		= $('#date_start').val();
	var end_date 		= $('#date_end').val();
	var h_employee_id 	= $('#employee_id').val();
	
	if(start_date != "" && h_employee_id != ""){
		$('#show_specific_schedule_wrapper').html(loading_image);
		$.post(base_url + 'leave/load_get_specific_schedule',{start_date:start_date,end_date:end_date,h_employee_id:h_employee_id},function(o) {		
			$('#show_specific_schedule_wrapper').html(o);
		});
	}
}

function load_show_employee_leave_available() {	
	var h_employee_id 	= $('#employee_id').val();
	
	if(h_employee_id != ""){
		$('#show_leave_available_wrapper').html(loading_image);
		$.post(base_url + 'leave/_load_get_employee_leave_available',{h_employee_id:h_employee_id},function(o) {		
			$('#show_leave_available_wrapper').html(o);
		});
	}
}

function load_show_employee_request_approvers() {
	var h_employee_id 	= $('#employee_id').val();
	
	if(h_employee_id != ""){
		$('#show_request_approvers_wrapper').html(loading_image);
		$.post(base_url + 'leave/_load_get_employee_request_approvers',{h_employee_id:h_employee_id},function(o) {		
			$('#show_request_approvers_wrapper').html(o);
		});
	}
}

function editComputeDays() {
	var start = new Date($("#edit_date_start").val());	
	var end = new Date($("#edit_date_end").val());
	var diff = new Date(end - start);
	var days = diff/1000/60/60/24;
	
	if(days>=0) {
		var length = days+1;
		$("#edit_number_of_days").val(length); //=> 8.525845775462964
	}else {
		$("#edit_number_of_days").val('0');	
	}
}


function computeDaysLeaveProfile() {
	var start = new Date($("#date_start").val());
	var end = new Date($("#date_end").val());

	var diff = new Date(end - start);
	var days = diff/1000/60/60/24;

	if(days>=0) {
		var length = days+1;
		$("#number_of_days").val(length); //=> 8.525845775462964
	}else {
		$("#number_of_days").html('0');	
	}
}

function clearFormError()
{
	$("#employee_leave_form").validationEngine('hide'); 	
}

function importLeave(){
	 var $dialog = $("#import_leave_wrapper");
	$dialog.dialog({
			title: 'Import Leave',
			width: 350,
			height: 'auto',				
			resizable: false,
			modal:true
		}).show();
}

function closeImportDialog()
{
	$("#import_leave_wrapper").dialog('destroy');
}

//for quick dynamic search
function loadCategory()
{
	
	$.fn.setCursorToTextEnd = function() {
        $initialVal = this.val();
        this.val($initialVal + ' ');
        this.val($initialVal);
    };

if($("#category").val()=='Hired Date:' || $("#category").val()=='Terminated Date:' || $("#category").val()=='End of Contract:') {
	$("#datepicker").show();
}

	if($("#search").val()=='') {
		$("#search").val($("#search").val()+$("#category").val());$
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	}else {
		$("#search").val($("#search").val()+","+$("#category").val());		
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	}
}

function load_leave_list_dt(dept_id, frequency_id = 1) {
	var dept_id = $('#cmb_dept_id').val();
	$.post(base_url + 'leave/_load_leave_list_dt',{dept_id:dept_id,frequency_id:frequency_id},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_approved_leave_list_dt(dept_id) {	
	$.post(base_url + 'leave/_load_approved_leave_list_dt',{dept_id:dept_id},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_within_date_range_approved_leave_list_dt(dept_id,from_date,to_date, frequency_id = 1) {	
	$.get(base_url + 'leave/_load_approved_leave_list_dt',{dept_id:dept_id,from_date:from_date,to_date:to_date,frequency_id:frequency_id},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_employees_incentive_leave_dt() {
	var year  = $("#cmb-year").val();	
	var month = $("#cmb-month").val();
	$("#incentive_leave_list_dt_wrapper").html(loading_image);	
	$.get(base_url + 'leave/_load_incentive_leave_list_dt',{year:year, month:month},function(o) {
		$('#incentive_leave_list_dt_wrapper').html(o);		
	});	
}

function load_disapproved_leave_list_dt(dept_id) {	
	$.post(base_url + 'leave/_load_disapproved_leave_list_dt',{dept_id:dept_id},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_within_date_range_disapproved_leave_list_dt(dept_id,from_date,to_date, frequency_id = 1) {	
	$.get(base_url + 'leave/_load_disapproved_leave_list_dt',{dept_id:dept_id,from_date:from_date,to_date:to_date,frequency_id:frequency_id},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_leave_list_archives_dt(dept_id) {	
	$.post(base_url + 'leave/_load_leave_list_archives_dt',{dept_id:dept_id},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_leave_type_archives_dt() {	
	$.post(base_url + 'leave/_load_leave_type_archives_dt',{},function(o) {
		$('#leave_type_dt_wrapper').html(o);		
	});	
}

function load_employee_leave_available_dt(employee_id) {	
	$.post(base_url + 'leave/_load_employee_leave_available_dt',{employee_id:employee_id},function(o) {
		$('#employee_leave_available_dt_wrapper').html(o);		
	});	
}

function load_employee_leave_list_dt(employee_id) {	
	$.post(base_url + 'leave/_load_employee_leave_list_dt',{employee_id:employee_id},function(o) {
		$('#employee_leave_list_dt_wrapper').html(o);		
	});	
}

function load_leave_history_dt(dept_id) {	
	$.post(base_url + 'leave/_load_leave_history_dt',{dept_id:dept_id},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_employee_leave_credit_dt(dept_id) {	
	$.post(base_url + 'leave/_load_employee_leave_credit_dt',{dept_id:dept_id},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function load_leave_type_list_dt() {	
	$.get(base_url + 'leave/_load_leave_type_list_dt',{},function(o) {
		$('#leave_list_dt_wrapper').html(o);		
	});	
}

function back_to_leave_list() {
	$('#employee_list_dt_wrapper').show();
	$('#leave_details_wrapper').hide();
	hide_leave_main_nav();	
	//hide_request_leave_form();
	$('#request_leave_form_wrapper').hide();
	$('#import_leave_button_wrapper').show();
	clearFormError();
}

function hide_leave_main_nav() {
	$('#request_leave_button').hide();
	$('#leave_back').hide();	
	$('#import_leave_button_wrapper').show();
}

function show_leave_main_nav() {
	$('#request_leave_button').show();
	$('#leave_back').show();	
	$('#import_leave_button_wrapper').hide();
}

function load_show_leave_details(h_employee_id) {
	$("#leave_details_wrapper").html(loading_image);	
	$.post(base_url + 'leave/_load_show_leave_details',{h_employee_id:h_employee_id},function(o){ 
		$('#leave_details_wrapper').html(o);
	});
	$('#leave_details_wrapper').show();
}

function load_show_leave_credit_details(h_employee_id) {
	$("#leave_details_wrapper").html(loading_image);	
	$.post(base_url + 'leave/_load_show_leave_credit_details',{h_employee_id:h_employee_id},function(o){ 
		$('#leave_details_wrapper').html(o);
	});
	$('#leave_details_wrapper').show();
}

function load_get_employee_leave_credits(leave_type,h_employee_id) {	
	$.post(base_url + 'leave/_load_get_employee_leave_credits',{leave_type:leave_type,h_employee_id:h_employee_id},
	function(o){ 
		$("#leave_alloted").val(o.alloted);
		$("#leave_available").val(o.available);
		$("#leave_credit").val(o.default_credit);
	},"json");
	
}

function load_hide_leave_details() {	
	$('#leave_details_wrapper').hide();
}


function addLeaveRequestForm(h_employee_id) {
	_addLeaveRequestForm(h_employee_id, {
		onSaved: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function editLeaveRequestForm(c_leave_id) {
	_editLeaveRequestForm(c_leave_id, {
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function viewLeaveRequestApprovers(eid) {
	_viewLeaveRequestApprovers(eid, {
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function editLeaveType(c_leave_id) {
	_editLeaveType(c_leave_id, {
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});

            var query = window.location.search;
            $.get(base_url + 'leave/type'+ query, {ajax:1}, function(html_data){
                $('#main').html(html_data)
            });
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function addLeaveCredit(employee_id) {
	_addLeaveCredit(employee_id, {
		onSaved: function(o) {			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function hide_request_leave_form() {	
	$('#request_leave_button').show();
	$('#request_leave_form_wrapper').hide();
	clearFormError();
	
}

function show_request_leave_form(frequency_id = 1) {
	$('#request_leave_button').hide();
	$('#request_leave_form_wrapper').show();
	$("#request_leave_form_wrapper").html(loading_image);	
	var start_cutoff = $("#from_period").val();
	var end_cutoff   = $("#to_period").val();
	$.get(base_url+'leave/ajax_add_new_leave_request',{start_cutoff:start_cutoff,end_cutoff:end_cutoff, frequency_id},
	function(o){
		$("#request_leave_form_wrapper").html(o);	
	});	
	
}

function show_add_leave_type_form() {
	$('#request_leave_button').hide();
	$('#request_leave_form_wrapper').show();
	$("#request_leave_form_wrapper").html(loading_image);	
	$.get(base_url+'leave/ajax_add_new_leave_type',{},
	function(o){
		$("#request_leave_form_wrapper").html(o);	
	});	
	
}

function archiveLeaveRequest(h_id) {
	_archiveLeaveRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function approveLeaveRequest(h_id) {
	_approveLeaveRequest(h_id, {
		onYes: function(o) {
			if( o.is_success ) {	
				load_leave_list_dt($("#cmb_dept_id").val());
			}			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);	
			dialogOkBox(o.message,{});				
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function disApproveLeaveRequest(h_id) {
	_disApproveLeaveRequest(h_id, {
		onYes: function(o) {
			if( o.is_success ) {	
				load_leave_list_dt($("#cmb_dept_id").val());
			}			
			closeDialog('#' + DIALOG_CONTENT_HANDLER);	
			dialogOkBox(o.message,{});				
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function pendingLeaveWithSelectedAction(status) {
	if(status){	
		_pendingLeaveWithSelectedAction(status,{
			onYes: function() {	
				$("#chkAction").val("");							
			}, 
			onNo: function(){
				$("#chkAction").val("");
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			} 
		});			
	}
}

function archiveWithSelectedAction(status) {
	if(status){	
		_archiveWithSelectedAction(status,{
			onYes: function() {	
				$("#chkAction").val("");	
				$("#chkActionSub").val("");							
			}, 
			onNo: function(){
				$("#chkAction").val("");
				$("#chkActionSub").val("");				
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			} 
		});			
	}
}

function leaveTypeWithSelectedAction(status) {
	if(status){	
		_leaveTypeWithSelectedAction(status,{
			onYes: function() {	
				$("#chkAction").val("");						
			}, 
			onNo: function(){
				$("#chkAction").val("");
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
			} 
		});			
	}
}

function archiveLeaveRequestWithSelectedAction(status) {	
	_archiveLeaveRequestWithSelectedAction(status,{
		onYes: function() {			
			//closeDialog('#' + DIALOG_CONTENT_HANDLER);				
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function archiveLeaveType(h_id) {
	_archiveLeaveType(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
            var query = window.location.search;
            $.get(base_url + 'leave/type'+ query, {ajax:1}, function(html_data){
                $('#main').html(html_data)
            });
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreLeaveRequest(h_id) {
	_restoreLeaveRequest(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function restoreLeaveType(h_id) {
	_restoreLeaveType(h_id, {
		onYes: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function createFormToken() {
	$.post(base_url + 'leave/_load_token',{},function(o){
		$('#token').val(o.token);
	},'json');
}

function clear_import_error_notifs() {
	$('#error_notifs').hide();
	$.post(base_url + 'leave/_load_clear_import_error',{},function(o){});	
}

function revertLeaveRequest(h_id) {
	_revertLeaveRequest(h_id, {
		onYes: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);	
			dialogOkBox(o.message,{});
			if(o.is_success) {	
				//load_approved_leave_list_dt($("#cmb_dept_id").val());
				
			}
			location.reload();
							
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

function processIncentiveLeave(month, year) {
	_processIncentiveLeave(month, year, {
		onYes: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);			
			dialogOkBox(o.message,{});			
		}, 
		onNo: function(){
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
		} 
	});	
}

