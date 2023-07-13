$(function(){
	/*open: function() {
		$.get(base_url+'sync_data/_sync',{},
		    function(o){																				
				$("#sync_data_modal").html(o.message);		   										   
		},"json");	

	},*/
	$("#btn-sync-data").click(function(){
		$("#sync_data_modal").html(loading_image);
		$.get(base_url+'sync_data/_ajax_load_confirmation',{},
		    function(o){	
		    	$("#sync_data_modal").html(o);							   
		});	
		//$("#sync_data_modal").html("<b> Are you sure you want to sync data now?</b><br/><br/><div class='alert alert-info'> Note: Data synchronization may take few minutes.</div>");
		var $dialog = $("#sync_data_modal");
		$dialog.dialog({
                title: 'Sync Data',
                width: 450,
				height: 275,				
				resizable: false,
				modal:true,
				buttons: {
					'Yes' : function(){			
						disablePopUp();
	                   $dialog.dialog("destroy");
	                   $dialog.hide();	
						showLoadingDialog('Synchronizing data...');			
						$.get(base_url+'sync_data/_sync',{},
						    function(o){	
						    	closeTheDialog();
							   	showMessage(o.message);								   
						},"json");		
						
                    },
					'No' : function(){
						  disablePopUp();
		                   $dialog.dialog("destroy");
		                   $dialog.hide();	
                    }
                },
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                
            }).show();	
	});

	function showMessage(message) {
		$("#sync_data_modal").html(message);
		var $dialog = $("#sync_data_modal");
		$dialog.dialog({
                title: 'Result',
                width: 350,
				height: 120,				
				resizable: false,
				modal:true,
				
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();	   
                },
                
            }).show();	
	}

});

function closeTheDialog() {
	closeDialog('#' + DIALOG_CONTENT_HANDLER, '#add_request');
}

