function load_add_memo() {
	load_memo_form();
	$('#message_container').hide();
	$("#add_memo_button_wrapper").hide();
	$("#memo_form_wrapper").show();				
}

function hide_memo_form() {
	$("#memo_form").validationEngine('hide');
	$("#add_memo_button_wrapper").show();
	$("#memo_form_wrapper").hide();
}

function load_memo_form() {
	$('#memoFormsAjax').html(' <span style = "font-size:10px;position:relative;top:-11px;">Loading...</span>');
	$.post(base_url + 'settings/_load_memo_template_form',{},
		function(o){			
			$('#memoFormsAjax').html(o);		
		});		
}

function load_memo_template_list_dt() {
	switchActiveClass('btn_viewallarchives','btn_viewall');
	$.get(base_url + 'settings/_load_memo_template_list_dt',{},function(o) {
		$('#memo_template_list_dt_wrapper').html(o);		
	});
}

function load_archive_memo_template_list_dt() {
	switchActiveClass('btn_viewall','btn_viewallarchives');	
	$.get(base_url + 'settings/_load_archive_memo_template_list_dt',{},function(o) {
		$('#memo_template_list_dt_wrapper').html(o);		
	});
}

function deleteMemoTemplate(memo_id) {
	$("#confirmation").html('Loading...');
	$.post(base_url + 'settings/_load_delete_memo_template_confirmation',{memo_id:memo_id},function(o) {
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
						$.post(base_url+'settings/delete_memo',{memo_id:memo_id},
						function(o){																				
							if(o == 1) {								
								$dialog.dialog("close");		
								$("#message_container").html('Successfully Deleted Memo');
								$('#message_container').show();														
								load_memo_template_list_dt();	
								disablePopUp();							
							} else {
								alert("invalid");	
								$dialog.dialog("close");
								disablePopUp();								
							}					
							   										   
						});
                    },
					'No' : function(){
						  disablePopUp();
						  $dialog.dialog("close");
                    }
                }
            }).show();	
}

function editMemoTemplate(memo_id) {
	
	$("#confirmation").html('Loading...');
	var memo_id = memo_id;
	$.post(base_url + 'settings/edit_memo',{memo_id:memo_id},function(o) {
		$("#action_form").html(o);								 
	})
	
	var $dialog = $('#action_form');
	$dialog.dialog("destroy");
	 
	var $dialog = $('#action_form');
	$dialog.dialog({
		title: 'Edit Memo Template',
		resizable: false,
		position: [330,100],
		width: 650,
		//height: 250,
		modal: false,
		close: function() {
				   disablePopUp();
				   $dialog.dialog("destroy");
				   $dialog.hide($.validationEngine.closePrompt('.formError',true));	
				   //load_my_messages_list();				
				}	
		}		
		).show();			
	

}


