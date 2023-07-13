<style>
.alert-danger h4, .alert-error h4 {
    color: #B94A48;
	font-size:17.5px;
}
.alert-error img{
	margin-right:5px;
}
</style>
<script>
$(document).ready(function() {
	$("#applicant_login_form").validationEngine({scroll:false});
	$('#applicant_login_form').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {
				$("#token").val(o.token);
				window.location = base_url+'applicant<?php echo $url_param; ?>';
			} else {
				$("#token").val(o.token);
				closeDialog('#' + DIALOG_CONTENT_HANDLER);
				showAlertBox();	
				$("#error_message").html(o.message);	
				$("#username").val("");
				$("#password").val("");
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Validating...');
		}
	});	
});	

function hideAlertBox(){
	$("#error_message").fadeOut(800);
}

function showAlertBox(){
	$("#error_message").fadeIn(800);
}
</script>

<div id="login_wrapper">
    <div id="login_container">
        <div class="login_content">
      <?php 
      	if (!$company_structure_id || !$username || !$applicant_id ) { 
            include_once("forms/_applicant_login.php");
      	} else {
				include_once("forms/_applicant_panel.php");				
	   	} 
		 ?>
		
        </div>
    </div>
</div>