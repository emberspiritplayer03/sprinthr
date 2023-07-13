// register

function checkEmail(email) {	
	 var validateEmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(validateEmail.test(email) == false){
		$("#errorHolder").hide();
	}else{
	$('#add_applicant_form input[type="submit"]').val('Validating');
	 $('#add_applicant_form input[type="submit"]').attr('disabled','disabled');
	 	
		$("#errorHolder").show();	
		$('#errorHolder').html('<div style="margin-top:4px;width:200px;" class=\"label label-success\"><i class="icon-info-sign icon-white"></i> Checking email availability</div>');
		$.post(base_url+'register/_check_email',{email:email},
			function(o){			
				$('#errorHolder').html(o.message);
				$('#add_applicant_form input[type="submit"]').removeAttr('disabled','disabled');	
				$('#add_applicant_form input[type="submit"]').val('Register');	
		},"json");		
	}
}

$(document).ready(function(){
     //$('#add_applicant_form input[type="submit"]').attr('disabled','disabled');
 });


