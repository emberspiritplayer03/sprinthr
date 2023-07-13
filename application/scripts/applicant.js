function load_application_list_dt() {
	$.get(base_url + 'applicant/_load_application_list_dt',{},function(o) {
		$('#applicant_list_dt_wrapper').html(o);		
	});
}

function removeApplication(application_id) {
	blockPopUp();
	$("#confirmation").html('Loading...');
	
	$.post(base_url + 'applicant/_load_cancel_application_confirmation',{application_id:application_id},function(o) {
		$("#confirmation").html(o);								 
	})
	
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
                },
                buttons: {
					'Yes' : function(){						
						$.post(base_url+'applicant/delete_application',{application_id:application_id},
						    function(o){																				
							if(o==1) {								
								$dialog.dialog("close");								
								load_application_list_dt();	
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this branch because it has active members");							
							}else {
								load_application_list_dt();		
								$dialog.dialog("close");
								disablePopUp();								
							}							   
						});	
						
                    },
					'No' : function(){
						  load_application_list_dt();	
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

function popupViewApplicationDetails(url) {
	popupWindow = window.open(
		base_url + 'applicant/application_details?aid=' + url,'popUpWindow','height=500,width=500,left=10,top=10,resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes')	
}

function viewApplicationDetails() {
	$("#applicantion_view_details_wrapper").show();	
}

function hideApplicationDetails() {
	$("#applicantion_view_details_wrapper").hide();
}

function loadPhotoDialog(applicant_id)
{
	var employee_id = applicant_id;
	$("#photo_wrapper").html(loading_message);
	dialogGeneric('#photo_wrapper',{title:'Photo'});
	$.post(base_url+'applicant/_load_photo',{employee_id:employee_id},
	function(o){
		$("#photo_wrapper").html(o);
		dialogGeneric('#photo_wrapper',{title:'Photo',height:'auto'});
		
	});	
}

function closePhotoDialog()
{
	closeDialog("#photo_wrapper",'');
}

function getApplicationStatus(status) {
	if(status == 0) {
		document.write('Application Submitted');
	}
	if(status == 1) {
		document.write('Interview');
	}
	if(status == 2) {
		document.write('Offered a Job');
	}
	if(status == 3) {
		document.write('Declined Offer');
	}
	if(status == 4) {
		document.write('Reject');
	}
	if(status == 5) {
		document.write('Hired');
	}
}

