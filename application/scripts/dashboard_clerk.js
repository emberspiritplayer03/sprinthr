function load_employee_attendance_list_dt() {
	$.get(base_url + 'dashboard/_load_employee_attendance_list_dt' ,{},function(o) {
		$('#employee_attendance_list_dt_wrapper').html(o);
	});
}

function load_employee_attendance_filter_by_date() {
	var from 	= $('#from').val();
	var to		= $('#to').val();
	
	if(from != "" && to != ""){
		load_employee_attendance_list_dt();
	}
}

function load_recent_request() {
	var request = $('#request').val();
	if(request == 1) {
		load_request_ot();
	} else if(request == 2) {
		load_request_leave();
	} else if(request == 3) {
		load_request_rest_day_schedule();
	}  else if(request == 4) {
		load_request_change_schedule();
	}  else if(request == 5) {
		load_request_undertime();
	}  else if(request == 6) {
		load_request_make_up_schedule();
	}  else if(request == 7) {
		load_request_ob_schedule();
	} 
}

function load_request_ot() {
	$.get(base_url + 'dashboard/_load_top_recent_ot_request' ,{},function(o) {
		$('#recent_request_list_wrapper').html(o);
	});
}

function load_request_leave() {
	$.get(base_url + 'dashboard/_load_top_recent_leave_request' ,{},function(o) {
		$('#recent_request_list_wrapper').html(o);
	});
}

function load_request_rest_day_schedule() {
	$.get(base_url + 'dashboard/_load_top_recent_rest_day_request' ,{},function(o) {
		$('#recent_request_list_wrapper').html(o);
	});
}

function load_request_change_schedule() {
	$.get(base_url + 'dashboard/_load_top_recent_change_schedule' ,{},function(o) {
		$('#recent_request_list_wrapper').html(o);
	});
}

function load_request_undertime() {
	$.get(base_url + 'dashboard/_load_top_recent_undertime_request' ,{},function(o) {
		$('#recent_request_list_wrapper').html(o);
	});
}

function load_request_make_up_schedule() {
	$.get(base_url + 'dashboard/_load_top_recent_make_up_schedule' ,{},function(o) {
		$('#recent_request_list_wrapper').html(o);
	});
}

function load_request_ob_schedule() {
	$.get(base_url + 'dashboard/_load_top_recent_ob_request' ,{},function(o) {
		$('#recent_request_list_wrapper').html(o);
	});
}


function load_approved_request() {
	var request = $('#approved_request').val();
	if(request == 1) {
		load_approved_request_ot();
	} else if(request == 2) {
		load_approved_request_leave();
	}
}

function load_approved_request_ot() {
	$.get(base_url + 'dashboard/_load_top_recent_approved_ot_request' ,{},function(o) {
		$('#recent_approved_request_list_wrapper').html(o);
	});
}

function load_approved_request_leave() {
	$.get(base_url + 'dashboard/_load_top_recent_approved_leave_request' ,{},function(o) {
		$('#recent_approved_request_list_wrapper').html(o);
	});
}



