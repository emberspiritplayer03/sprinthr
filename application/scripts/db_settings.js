// JavaScript Document
function load_table_default_values_confirmation() {
	blockPopUp();
	$("#confirmation").html('Loading...');
	$("#confirmation").html('Proceed with loading default values?');		
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
						$.post(base_url+'settings/load_default_values',{},
						    function(o){																				
							if (o.is_success == 1) {
								closeDialog('#confirmation');					
								$("#error_container").html(o.message);																
							} else {
								closeDialog('#confirmation');					
								$("#error_container").html(o.message);
							}		
							   										   
						},"json");		
						
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();
}

function _createRecommendedTables() {
	$.post(base_url+'settings/_load_create_recommended_tables',{},function(o) {
		
	});
}
