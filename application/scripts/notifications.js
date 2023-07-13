/* Declaration */
var auto_load_notification;

$(function(){
	//_count_new_notifications();
});

/*
 * Activate autoload of notification with 60 seconds interval = 60000ms
 * when the user is active in the browser.
 */
$(window).focus(function(){	
	clearInterval(auto_load_notification);
	auto_load_notification = setInterval(function(){_count_new_notifications()}, 100000);		
});

/*
 * Deactivate autoload of notification when the user leave the browser - Idle
 * This will avoid resource overload. 
 */
$(window).blur(function(){
	clearInterval(auto_load_notification);
});

function _count_new_notifications() {
	//$.active returns the number of active Ajax requests.
	if($.active == 0) {
		$.get(base_url+'notifications/_count_new_notifications',{},
		function(o){	
			if(o){
				if(o.new_notifications > 0)
					$('#noti_count').html(o.new_notifications);
				else{
					$('#noti_count').html('');
				}
			}	
		},"json");	
	}
	
}

function loadNotificationList(){
	$('#notification_list_wrapper').html(loading_image + ' Loading...');
    $.get(base_url + 'notifications/_load_notification_list',{},
        function(o){
            $('#notification_list_wrapper').html(o);
    });
}

function loadViewNotificationItemList(notification_id, from, to){
	$('#view_notification_item_list_wrapper').html(loading_image + ' Loading...');
    $.post(base_url + 'notifications/_load_view_notification_item_list',{notification_id:notification_id, from:from, to:to},
        function(o){
            $('#view_notification_item_list_wrapper').html(o);
    });
}

function loadPayrollViewNotificationItemList(notification_id, from, to){
	$('#view_notification_item_list_wrapper2').html(loading_image + ' Loading...');
    $.post(base_url + 'payroll_register/_load_view_notification_item_list',{notification_id:notification_id, from:from, to:to},
        function(o){
            $('#view_notification_item_list_wrapper2').html(o);
    });
}

function loadViewNotificationItemListByMonth(notification_id, cutoff_01, cutoff_02){
	$('#view_notification_item_list_wrapper2').html(loading_image + ' Loading...');
    $.post(base_url + 'payroll_register/_load_view_notification_item_list',{notification_id:notification_id, cutoff_01:cutoff_01, cutoff_02:cutoff_02},
        function(o){
            $('#view_notification_item_list_wrapper2').html(o);
    });
}

function updateLeaveCreditNotification(employee_credits_list) {
	/*$("#leave_credit_notication").html(loading_image);
	$.post(base_url + 'notifications/_leave_credit_update_notication',{},function(o) {
		$("#leave_credit_notication").html(o);							 
	})*/
	
	var html_head = "<p><strong>Leave credit of the following employee has been updated</strong></p>";
	
	$("#leave_credit_notication").html(html_head + employee_credits_list);	
	
	var $dialog = $('#leave_credit_notication');
	 $dialog.dialog("destroy");
	 
	var $dialog = $('#leave_credit_notication');
	$dialog.dialog({
		title: 'Leave Credit Notication',
		resizable: false,
		position: [480,50],
		width: 400,
		modal: false,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   //load_my_messages_list();				
				}	
					
		}).dialogExtend({
        "maximize" : false,
		"minimize"  : true,
        "dblclick" : "maximize",
        "icons" : { "maximize" : "ui-icon-arrow-4-diag" }
      }).show();		
}






function createEvaluationNotif(eid,events){

_createEvaluationNotif(eid, {
			onSaved: function(o) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				
				//load_view_all_employee_eval_datatable();

				showOkDialog(o.message);

				
				window.location.reload();
				//$('#employee_eval_datatable').dataTable().ajax.reload();
				//alert(o);			
				
			},
			onSaving: function() {
				showLoadingDialog('Saving...');
			},
			onLoading: function() {
				$('#message_container').hide();
				showLoadingDialog('Loading...');
			},

			onError: function(o) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				showOkDialog(o.message);
			}
		});	

}


function _createEvaluationNotif(evid, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Create Employee Evaluation';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'evaluation/ajax_create_employee_evaluation_notification', {evid:evid}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true,
			form_id: '#edit_evaluation_form'
		});

		$('#edit_evaluation_form').ajaxForm({
			success:function(o) {
				if (o.is_saved) {
					if (typeof events.onSaved == "function") {
						events.onSaved(o);
					}
				} else {
					if (typeof events.onError == "function") {
						events.onError(o);
					}
				}
			},
			dataType:'json',
			beforeSubmit: function(events) {
				
				var date = document.getElementById("evaldate").value;
				var date2 = document.getElementById("nextevaldate").value;
				var eDate = new Date(date); //dd-mm-YYYY
				var nDate = new Date(date2); 

				if(eDate >= nDate){
					alert('next evaluation date must be greater than evaluation date');
					return false;
				}
				else{

					if (typeof events.onSaving == "function") {
					events.onSaving();
					}
					return true;
				}
			}
			});			
		
		});	
}


function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}