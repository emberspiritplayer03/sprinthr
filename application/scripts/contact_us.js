var sending_message = "<div class=\"alert\"><strong>Sending message...</strong></div>"
$(document).ready(function(){
	$('#sprint_contact_form').validationEngine({scroll:false});	
	
	$('#sprint_contact_form').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {
			
			} else {							
			}
			
			$('#g_submit_btn').removeAttr('disabled');
			$('#your_name').removeAttr('disabled');
			$('#email_address').removeAttr('disabled');
			$('#company_name').removeAttr('disabled');
			$('#your_message').removeAttr('disabled');
			$('#g_inquiry_option').removeAttr('disabled');		
			
			$(".form_submit_message_container").fadeOut();
			$(".form_submit_message_container").fadeIn();
			$(".form_submit_message_container").html(o.message);
		},
		dataType:'json',
		beforeSubmit: function() {
			$(".form_submit_message_container").fadeOut();
			$(".form_submit_message_container").fadeIn();
			$(".form_submit_message_container").html(sending_message);
			$('#g_submit_btn').attr('disabled', 'disabled');		
			$('#your_name').attr('disabled', 'disabled');				
			$('#email_address').attr('disabled', 'disabled');				
			$('#company_name').attr('disabled', 'disabled');				
			$('#your_message').attr('disabled', 'disabled');				
			$('#g_inquiry_option').attr('disabled', 'disabled');				
			
		}
	});
});