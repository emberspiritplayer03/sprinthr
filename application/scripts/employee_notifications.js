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
	auto_load_notification = setInterval(function(){_count_new_employee_notifications()}, 5000);		
});

/*
 * Deactivate autoload of notification when the user leave the browser - Idle
 * This will avoid resource overload. 
 */
$(window).blur(function(){
	clearInterval(auto_load_notification);
});

function _count_new_employee_notifications() {
	//$.active returns the number of active Ajax requests.
	if($.active == 0) {
		$.get(base_url+'dashboard/_count_new_employee_notifications',{},
		function(o){	
			if(o){
				if(o.new_notifications > 0) {
					$('#noti_count').html(o.new_notifications);
					$('#btn-notification-counter').html(o.new_notifications);
				}else{
					$('#noti_count').html('');
				}

				if(o.overtime_for_approval > 0) {
					$('#ot-counter').html( '(' + o.overtime_for_approval + ')' );
				}else{
					$('#ot-counter').html('');
				}

				if(o.leave_for_approval > 0) {
					$('#leave-counter').html( '(' + o.leave_for_approval + ')' );
				}else{
					$('#leave-counter').html('');
				}

				if(o.ob_for_approval > 0) {
					$('#ob-counter').html( '(' + o.ob_for_approval + ')' );
				}else{
					$('#ob-counter').html('');
				}

			}	
		},"json");	
	}
	
}
