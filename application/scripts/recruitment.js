var can_manage = "";

$(function() {
  $(this).mouseup(function() {
	  $(".dropcontent").hide();
	});
});

function loadOptions(id) {
	var box = $('#options_'+id);
	var button = $('#dropButton_'+id);

		
	if((button.hasClass('active')==false)) {
		$(".dropbutton").removeClass('active');
		box.toggle();
		button.toggleClass('active');
	}else {
		
		button.removeClass('active');
		box.hide();
	}
}



function remove_job_vacancy(id) {
	$("#confirmation").html(loading_message);
	$.post(base_url + 'recruitment/_load_delete_job_vacancy_confirmation',{id:id},function(o) {
		$("#confirmation").html(o)								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 'auto',				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();
				    
                },
                buttons: {
					'Yes' : function(){
						$.post(base_url+'recruitment/_delete_job_vacancy',{id:id},
						    function(o){																				
							if(o==1) {	
								load_job_vacancy_dt();					
								$dialog.dialog("close");								
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
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

function remove_applicant_history(id) {
	$("#confirmation").html(loading_message);
	$.post(base_url + 'recruitment/_load_delete_applicant_history_confirmation',{},function(o) {
		$("#confirmation").html(o)								 
	})
	 var $dialog = $("#confirmation");
		$dialog.dialog({
                title: 'Confirmation',
                width: 410,
				height: 'auto',				
				resizable: false,
				modal:true,
                close: function() {
   				   disablePopUp();
                   $dialog.dialog("destroy");
                   $dialog.hide();
				    
                },
                buttons: {
					'Yes' : function(){
						$.post(base_url+'recruitment/_delete_job_vacancy',{id:id},
						    function(o){																				
							if(o==1) {	
								load_job_vacancy_dt();					
								$dialog.dialog("close");								
								disablePopUp();			
							}else if(o==2){
								load_confirmation("You cannot remove this entry because it has active data attached to it!");							
							}else {
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

function open_job_vacancy(id)
{
	$.post(base_url+'recruitment/_open_job_vacancy',{id:id},function(){
		load_job_vacancy_dt();	
	});
}

function close_job_vacancy(id)
{
	$.post(base_url+'recruitment/_close_job_vacancy',{id:id},function(){load_job_vacancy_dt();});
}

function load_add_job_vacancy() {
	$("#add_job_vacancy_form_wrapper").show();		
}

function hide_add_job_vacancy() {
	$("#job_vacancy_form").validationEngine('hide');  	
	$("#add_job_vacancy_form_wrapper").hide();	
}

function load_add_candidate() {

	$("#add_candidate_button_wrapper").hide();
	$("#candidate_form_wrapper").show();
}

function load_job_vacancy_dt()
{
	element_id = 'job_vacancy_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
				var is_active = oRecord.getData("is_active");
				if(can_manage) {
					if(is_active=='0'){
						elCell.innerHTML = "<a class='btn btn-mini active' title='Open' href=\"javascript:open_job_vacancy("+ id +");\"><i class='icon-circle-arrow-left'></i> Open </a> <a class='btn btn-mini' title='Edit' href=\"javascript:editJobVacancy("+ id +");\"><i class='icon-pencil'></i> Edit</a>  <a class='btn btn-mini' title='Remove' href=\"javascript:remove_job_vacancy("+ id +");\"><i class='icon-trash'></i> Delete</a>"; 
					}else {
						elCell.innerHTML = "<a class='btn btn-mini' title='Close' href=\"javascript:close_job_vacancy("+ id +");\"><i class='icon-ok-circle'></i> Close </a> <a class='btn btn-mini' title='Edit' href=\"javascript:editJobVacancy("+ id +");\"><i class='icon-pencil'></i> Edit</a> <a class='btn btn-mini' title='Remove' href=\"javascript:remove_job_vacancy("+ id +");\"><i class='icon-trash'></i> Delete</a>"; 
					}
				} else {
					elCell.innerHTML = "---";	
				} 
	
			};

	var stats = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
				var is_active = oRecord.getData("is_active");
				if(can_manage) {
					if(is_active=='0'){
						elCell.innerHTML = "Close"; 
					}else {
						elCell.innerHTML = "Open"; 
					}
				} else {
					elCell.innerHTML = "---";	
				} 
			};		
			
	var test = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
			};						
			
		var columns = 	[
						 /*{key:"id",label:"Job Id",width:100,resizeable:true,sortable:true},*/
						 {key:"job_title",label:"Job Title",width:160,resizeable:true,sortable:true },
						 {key:"hiring_manager_name",label:"Hiring Manager",width:170,resizeable:true,sortable:true},
						 {key:"publication_date",label:"Publication Date",width:120,resizeable:true,sortable:true },
						 {key:"advertisement_end",label:"Advertisement End",width:120,resizeable:true,sortable:true },
						 {key:"is_active",label:"Status",width:80,resizeable:true,sortable:true,formatter:stats},
						 {key:"",label:"Actions",width:180,resizeable:true,sortable:true,formatter:action}
						 ];
		var fields =	['id','job_title','hiring_manager_name','is_active','publication_date','advertisement_end'];
		var height = 	'auto';
		var width = 	'100%';

		var controller = 'recruitment/_json_encode_job_vacancy_list?';		
		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
} 
function searchApplicant()
{
	var searched = $("#search").val();
	load_candidate_datatable(searched);
	load_total_result(searched);
}

function load_recently_imported_applicant(total_imported)
{
	load_candidate_datatable_recent_add();
	$("#total_records_wrapper").html("Total Record(s): "+ total_imported);
	//load_total_result(searched);
}

function load_total_result(searched) 
{
	$.post(base_url+ 'recruitment/_get_total_records',{searched:searched},
	function(o) {
		$("#total_records_wrapper").html(o);	
	});
}

function load_candidate_datatable_recent_add()
{
	element_id = 'candidate_datatable';
			
	var title = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					//elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
					var id = oRecord.getData("id");
					var photo = oRecord.getData("photo");
					var hash = oRecord.getData("hash");
					var applicant_name = oRecord.getData("applicant_name");
					var application_status_id = oRecord.getData("application_status_id");
					elCell.innerHTML = "<a class='dropbutton' href='profile?rid="+id+"&hash="+hash+"&status="+application_status_id+"#application_history'><img class='employee_image_view images_wrapper' src='"+photo+"' height='40' style='display:none' align='middle'  /><span class='employee_image_name'>"+applicant_name+"</span></a>"; 
			};
			
	var selection = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("wrapper_id");
				var options = oRecord.getData("options");

					//elCell.innerHTML = "<div>"+options+"</div>";
					elCell.innerHTML = '<div id="dropholder"><a onClick="javascript:loadOptions('+id+');" class="dropbutton" id="dropButton_'+id+'"><span>Options</span></a><div id="options_'+id+'" class="dropcontent candidate_option" style="display:none;">'+options+'</div></div>'
			};						
			
		var columns = 	[{key:"applicant_name",label:"Applicant Name",width:350,resizeable:true,sortable:true,formatter:title},
						 {key:"job_name",label:"Job Applied",width:180,resizeable:true,sortable:true},
						 {key:"applied_date_time",label:"Date Applied",width:100,resizeable:true,sortable:true},
						 {key:"application_status",label:"Status",width:170,resizeable:true,sortable:true},
						 {key:"options",label:"Action",width:100,resizeable:true,sortable:true,formatter:selection, className:'overflow'}
						 ];
		var fields =	['id','position_applied','photo','wrapper_id', 'applicant_name','options','application_status','application_status_id','applied_date_time','job_name','hiring_manager','hash'];
		var height = 	'100%';
		var width = 	'100%';
		
		var controller = 'recruitment/_json_encode_candidate_load_imported_applicant?';			
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
}

function load_candidate_datatable(searched)
{	
	element_id = 'candidate_datatable';
			
	var title = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					//elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
					var id = oRecord.getData("id");
					var photo = oRecord.getData("photo");
					var hash = oRecord.getData("hash");
					var applicant_name = oRecord.getData("applicant_name");
					var application_status_id = oRecord.getData("application_status_id");
					if(can_manage) {
						elCell.innerHTML = "<a class='dropbutton' href="+base_url+"recruitment/profile?rid="+id+"&hash="+hash+"&status="+application_status_id+"#application_history><img class='employee_image_view images_wrapper' src='"+photo+"' height='40' style='display:none' align='middle'  /><span class='employee_image_name'>"+applicant_name+"</span></a>"; 
					} else {
						elCell.innerHTML = "<img class='employee_image_view images_wrapper' src='"+photo+"' height='40' style='display:none' align='middle'  /><span class='employee_image_name'>"+applicant_name+"</span>"; 
					}

			};
			
	var selection = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("wrapper_id");
				var options = oRecord.getData("options");

					//elCell.innerHTML = "<div>"+options+"</div>";
					if(can_manage) {
						elCell.innerHTML = '<div id="dropholder"><a onClick="javascript:loadOptions('+id+');" class="dropbutton" id="dropButton_'+id+'"><span>Options</span></a><div id="options_'+id+'" class="dropcontent candidate_option" style="display:none;">'+options+'</div></div>'	
					} else  { elCell.innerHTML = "---"; }
			};						
			
		var columns = 	[{key:"applicant_name",label:"Applicant Name",width:350,resizeable:true,sortable:true,formatter:title},
						 {key:"job_name",label:"Job Applied",width:180,resizeable:true,sortable:true},
						 {key:"applied_date_time",label:"Date Applied",width:100,resizeable:true,sortable:true},
						 {key:"application_status",label:"Status",width:170,resizeable:true,sortable:true},
						 {key:"options",label:"Action",width:100,resizeable:true,sortable:true,formatter:selection, className:'overflow'}
						 ];
		var fields =	['id','position_applied','photo','wrapper_id', 'applicant_name','options','application_status','application_status_id','applied_date_time','job_name','hiring_manager','hash'];
		var height = 	'100%';
		var width = 	'100%';

				
		
		if(searched) {
			var controller = 'recruitment/_json_encode_candidate_list?search='+searched+'&';			
		}else {
			var controller = 'recruitment/_json_encode_candidate_list?';			
		}
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
} 

function load_view_all_candidate_datatable()
{
	addActiveState('btn_viewall','btn-small');	
	element_id = 'candidate_datatable';
			
	var title = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					//elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
					var id = oRecord.getData("id");
					var photo = oRecord.getData("photo");
					var hash = oRecord.getData("hash");
					var applicant_name = oRecord.getData("applicant_name");
					var application_status_id = oRecord.getData("application_status_id");
					if(can_manage) {	
							elCell.innerHTML = "<a class='dropbutton' href="+base_url+"recruitment/profile?rid="+id+"&hash="+hash+"&status="+application_status_id+"#application_history><img class='employee_image_view images_wrapper' src='"+photo+"' height='40' style='display:none' align='middle'  /><span class='employee_image_name'>"+applicant_name+"</span></a>";
					} else {
						elCell.innerHTML = "<img class='employee_image_view images_wrapper' src='"+photo+"' height='40' style='display:none' align='middle'  /><span class='employee_image_name'>"+applicant_name+"</span>"; 
					}

			};
			
	var selection = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("wrapper_id");
				var options = oRecord.getData("options");

					//elCell.innerHTML = "<div>"+options+"</div>";
					if(can_manage) {
						elCell.innerHTML = '<div id="dropholder"><a onClick="javascript:loadOptions('+id+');" class="dropbutton" id="dropButton_'+id+'"><span>Options</span></a><div id="options_'+id+'" class="dropcontent candidate_option" style="display:none;">'+options+'</div></div>'	
					} else  { elCell.innerHTML = "---"; }
			};						
			
		var columns = 	[{key:"applicant_name",label:"Applicant Name",width:350,resizeable:true,sortable:true,formatter:title},
						 {key:"job_name",label:"Job Applied",width:180,resizeable:true,sortable:true},
						 {key:"applied_date_time",label:"Date Applied",width:100,resizeable:true,sortable:true},
						 {key:"application_status",label:"Status",width:170,resizeable:true,sortable:true},
						 {key:"options",label:"Action",width:100,resizeable:true,sortable:true,formatter:selection, className:'overflow'}
						 ];
		var fields =	['id','position_applied','photo','wrapper_id', 'applicant_name','options','application_status','application_status_id','applied_date_time','job_name','hiring_manager','hash'];
		var height = 	'100%';
		var width = 	'100%';

				
		
		var controller = 'recruitment/_json_encode_view_all_candidate_list?';			
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
} 

function editJobVacancy(job_id) {
	_editJobVacancy(job_id, {
		onSaved: function(o) {
			load_job_vacancy_dt();
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			//dialogOkBox(o.message,{});
			dialogOkBox('Update Successfull',{});
		},
		onSaving: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Saving...');
		},
		onLoading: function() {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			showLoadingDialog('Loading...');
		},	
		onError: function(o) {
			closeDialog('#' + DIALOG_CONTENT_HANDLER);
			dialogOkBox(o.message,{});
		}
	});	
}

function _editJobVacancy(job_id, events) {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var title = 'Edit Job Vacany';
	var width = 600;
	var height = 'auto';
	
	if (typeof events.onLoading == "function") {
		events.onLoading();
	}	
	
	$.post(base_url + 'recruitment/ajax_edit_job_vacancy', {job_id:job_id}, function(data) {
		closeDialog(dialog_id);
		$(dialog_id).html(data);
		dialogGeneric(dialog_id, {
			title: title,
			resizable: false,
			width: width,
			height: height,
			modal: true
		});
		$('#edit_job_vacancy_form').validationEngine({scroll:false});		
		$('#edit_job_vacancy_form').ajaxForm({
			success:function(o) {
				if (typeof events.onSaved == "function") {
					events.onSaved(o);
				 }
			},
			beforeSubmit:function() {
				showLoadingDialog('Saving...');	
			}
		});

	});
}

function cancel_add_candidate_form() {
	$("#add_candidate_form").validationEngine('hide');  	
	$("#add_candidate_button_wrapper").show();
	$("#candidate_form_wrapper").hide();
}

function view_other_personal_information()
{
	$("#view_other_personal_information_button_wrapper").hide();
	$("#hide_other_personal_information_button_wrapper").show();
	$("#other_personal_information_form_wrapper").show();
}

function hide_other_personal_information()
{
	$("#view_other_personal_information_button_wrapper").show();
	$("#hide_other_personal_information_button_wrapper").hide();
	$("#other_personal_information_form_wrapper").hide();
}

function view_contact_details()
{
	$("#view_contact_details_button_wrapper").hide();
	$("#hide_contact_details_button_wrapper").show();
	$("#contact_information_form_wrapper").show();
}

function hide_contact_details()
{
	$("#view_contact_details_button_wrapper").show();
	$("#hide_contact_details_button_wrapper").hide();
	$("#contact_information_form_wrapper").hide();
}



function load_add_candidate_confirmation(applicant_id) {
	var applicant_id = applicant_id
	$("#confirmation").html(loading_message);
	$.post(base_url + 'recruitment/_load_add_candidate_confirmation',{},function(o) {
		$("#confirmation").html(o)								 
	})

	hash = $("#applicant_hash").val();
	 var $dialog = $("#confirmation");
		$dialog.dialog({
			title: 'Confirmation',
			width: 610,
			height: 'auto',				
			resizable: false,
			modal:true,
			close: function() {
			   disablePopUp();
			   $dialog.dialog("destroy");
			   $dialog.hide();	
			   load_candidate_datatable(); 
			   window.location = "candidate"; 
			},
			buttons: {
				'View Profile' : function(){						
						load_candidate_datatable();
						//window.location = "recruitment/profile?rid="+applicant_id+"&hash="+hash+"&status=0#application_history";
						window.location = "profile?rid="+applicant_id+"&hash="+hash+"&status=0#application_history";
				},
				'Add Another Candidate' : function(){						
						load_candidate_datatable();
						window.location = "candidate?add_candidate=true";
				},
				'View Candidate List' : function(){
						load_candidate_datatable();					
						disablePopUp();
						$dialog.dialog("close");
						window.location = "candidate";
				},
				'Take an Exam' : function(){
						load_candidate_datatable();					
						disablePopUp();
						$dialog.dialog("close");
						window.location = "examination?add=show&rid="+applicant_id+"&hash="+hash;
				}
			}
		}).show();
}


function checkForAddBranch() 
{
	clear_form_error();

	branch_id = $("#branch_id").val();	
	if(branch_id=='add') {
			load_branch_dropdown();
			if($("#branch_wrapper_form").html()!='') {
				dialogGeneric("#branch_wrapper_form",{height:380,width:550,form_id:'#addBranch'});	
			}else {
				$.post(base_url+"recruitment/_load_add_branch_form",{},
				function(o){
					$("#branch_wrapper_form").html(o);
					dialogGeneric("#branch_wrapper_form",{height:380,width:550,form_id:'#addBranch'});	
				});
			}
	}else {
		if(branch_id!='') {
			load_department_dropdown(branch_id);	
		}	
	}
}

function load_department_dropdown()
{
	branch_id = $("#branch_id").val();
	$("#department_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'recruitment/_load_department_dropdown',{branch_id:branch_id},
	function(o){
		$("#department_dropdown_wrapper").html(o);
	});
}

function load_branch_dropdown() 
{
	$("#branch_dropdown_wrapper").html(loading_image+' loading...');
	$.post(base_url+'recruitment/_load_branch_dropdown',{branch_id:branch_id},
	function(o){
		$("#branch_dropdown_wrapper").html(o);
	});
}

function checkForAddPosition()
{
	clear_form_error();
	
	var position_id = $("#position_id").val();	

	if(position_id=='add') {
		load_position_dropdown();			
		if($("#position_wrapper_form").html()!='') {
				dialogGeneric("#position_wrapper_form",{height:'auto',width:'auto',form_id:'#addPosition'});	
		}else {
				/*$.post(base_url+"recruitment/_load_add_position_form",{},
				function(o){
					$("#position_wrapper_form").html(o);
					dialogGeneric("#position_wrapper_form",{height:'auto',width:'auto',title:'Add Position'});	
				});*/
		}
	}else {
		$("#status_dropdown_wrapper").html(loading_image+' loading...');
		$.post(base_url+'recruitment/_load_employee_status_dropdown',{position_id:position_id},
		function(o){
			$("#status_dropdown_wrapper").html(o);	
		});	
	}
}

function loadMinimumMaximumRate()
{
	var job_salary_rate_id = $("#job_salary_rate_id").val();

	if(job_salary_rate_id!='') {
		$("#minimum_rate_label").html(loading_image+' loading...');
		$("#maximum_rate_label").html(loading_image+' loading...');
		$.post(base_url+'recruitment/_load_minimum_rate',{job_salary_rate_id:job_salary_rate_id},function(o){$("#minimum_rate_label").html(o);});	
		$.post(base_url+'recruitment/_load_maximum_rate',{job_salary_rate_id:job_salary_rate_id},function(o){$("#maximum_rate_label").html(o);});	
	}else {
		$("#minimum_salary_rate").html('');
		$("#maximum_salary_rate").html('');
	}
	
}

function checkForAddStatus() 
{
	clear_form_error();
	
	var employment_status_id = $("#employment_status_id").val();
	var position_id = $("#position_id").val();

	if(employment_status_id=='add') {
		load_status_dropdown();			
		if($("#status_wrapper_form").html()!='') {
				dialogGeneric("#status_wrapper_form",{height:'auto',width:'auto',form_id:'#addStatus'});	
		}else {
			$.post(base_url+"employee/_load_add_status_form",{position_id:position_id},
			function(o){
				$("#status_wrapper_form").html(o);
				dialogGeneric("#status_wrapper_form",{height:'auto',width:'auto',title:'Add Employment Status'});	
			});
		}
		
	
	}
}

function checkForAddDepartment()
{
	clear_form_error();
	
	department_id = $("#department_id").val();	
	branch_id = $("#branch_id").val();
	
	if(branch_id=='') {
		dialogOkBox('Please Choose a Branch First',{icon:'alert'});
	}	

}

function clear_form_error() {
	$("#addBranch").validationEngine('hide');  	
	$("#employee_form").validationEngine('hide'); 
	$("#addPosition").validationEngine('hide');  
	$("#addStatus").validationEngine('hide');  	
}


function importApplicant()
{
	 var $dialog = $("#import_applicant_wrapper");
		$dialog.dialog({
                title: 'Import Applicant',
                width: 350,
				height: 'auto',				
				resizable: false,
				modal:true
            }).show();
}

function closeImportDialog()
{
	$("#import_applicant_wrapper").dialog('destroy');
}

function loadImageView()
{
	addActiveState('btn_imageview','btn-small');
	$(".images_wrapper").show();	
}

function loadListView()
{
	addActiveState('btn_listview','btn-small');
	$(".images_wrapper").hide();
}


//for quick dynamic search
function loadCategory()
{
	$.fn.setCursorToTextEnd = function() {
        $initialVal = this.val();
        this.val($initialVal + ' ');
        this.val($initialVal);
    };

if($("#category").val()=='Hired Date:' || $("#category").val()=='Date Applied:') {
	$("#datepicker").show();
}

if($("#category").val()=='Applied Position:') {
	$("#position_option").show();
}else {
	$("#position_option").hide();
}

if($("#category").val()=='Status:') {
	$("#status_option").show();
}else {
	$("#status_option").hide();
}


	if($("#search").val()=='') {
		$("#search").val($("#search").val()+$("#category").val());
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	
	}else {
		$("#search").val($("#search").val()+","+$("#category").val());		
		$("#search").focus();
		$("#search").setCursorToTextEnd();
	}
	$("#category")[0].selectedIndex = 0;
}

function loadStatusOption()
{
	$("#search").val($("#search").val()+$("#status").val());$
	$("#search").focus();
	$("#search").setCursorToTextEnd();
	
	$("#status_option").hide();
}

function loadPositionOption()
{
	$("#search").val($("#search").val()+$("#position_applied").val());$
	$("#search").focus();
	$("#search").setCursorToTextEnd();
	
	$("#position_option").hide();
	$("#position_applied")[0].selectedIndex = 0;
}

function show_load_import_button()
{
	$.post(base_url+'recruitment/_load_imported_applicant',{},function(o){
		$("#imported_applicant_button").html(o);
		
	});	
}

function loadImportedApplicants(searched)
{
	element_id = 'candidate_datatable';
			
	var title = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					//elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
					var id = oRecord.getData("id");
					var photo = oRecord.getData("photo");
					var hash = oRecord.getData("hash");
					var applicant_name = oRecord.getData("applicant_name");
					var application_status_id = oRecord.getData("application_status_id");
					elCell.innerHTML = "<a class='dropbutton' href='profile?rid="+id+"&hash="+hash+"&status="+application_status_id+"#application_history'><img class='employee_image_view images_wrapper' src='"+photo+"' height='40' style='display:none' align='middle'  /><span class='employee_image_name'>"+applicant_name+"</span></a>"; 
			};
			
	var selection = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("wrapper_id");
				var options = oRecord.getData("options");

					//elCell.innerHTML = "<div>"+options+"</div>";
					elCell.innerHTML = '<div id="dropholder"><a onClick="javascript:loadOptions('+id+');" class="dropbutton" id="dropButton_'+id+'"><span>Options</span></a><div id="options_'+id+'" class="dropcontent candidate_option" style="display:none;">'+options+'</div></div>'
			};						
			
		var columns = 	[{key:"applicant_name",label:"Applicant Name",width:350,resizeable:true,sortable:true,formatter:title},
						 {key:"job_name",label:"Job Applied",width:180,resizeable:true,sortable:true},
						 {key:"applied_date_time",label:"Date Applied",width:100,resizeable:true,sortable:true},
						 {key:"application_status",label:"Status",width:170,resizeable:true,sortable:true},
						 {key:"options",label:"Action",width:100,resizeable:true,sortable:true,formatter:selection, className:'overflow'}
						 ];
		var fields =	['id','position_applied','photo','wrapper_id', 'applicant_name','options','application_status','application_status_id','applied_date_time','job_name','hiring_manager','hash'];
		var height = 	'100%';
		var width = 	'100%';

				
		
		if(searched) {
			var controller = 'recruitment/_json_encode_candidate_list?load_imported='+searched+'&';			
		}else {
			var controller = 'recruitment/_json_encode_candidate_list?';			
		}
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
}

function clearApplicationHistoryInlineErrorForm()
{
	$("#applicant_reject_add_form").validationEngine('hide');
	$("#work_experience_add_form").validationEngine('hide');	
}


function loadDeleteApplicationHistoryDialog(h_id)
{
	clearApplicationHistoryInlineErrorForm();

	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#application_history_delete_wrapper");
	var $dialog = $(dialog_id).html(icon+message);
	$dialog.dialog({
		title: "Confirmation",
		resizable: false,
		width: 390,
		height: 'auto',
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Yes' : function(){
					$dialog.dialog("close");
					disablePopUp();
					showLoadingDialog('Deleting...');
					
					$.post(base_url+'recruitment/_delete_application_history',{h_id:h_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						$("#application_history_wrapper").html('');
						loadPage("#application_history");
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function addActiveState(obj_id,class_name)
{
	$('.' + class_name).removeClass('active');
	$("#" + obj_id).addClass("active");
}

function closeDialogBox(dialog_id,form_id) {
	closeDialog(dialog_id);
	$(form_id).validationEngine("hide");
}

function createJobVacancyXML() {
	var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
	var width = 350 ;
	var height = 180
	var title = 'Notice';
	var message = 'Are you sure you want to create <b>Job Vacancy XML file</b>?';
	
	blockPopUp();
	$(dialog_id).html(message);
	$dialog = $(dialog_id);
	$dialog.dialog({
		title: 'Notice',
		resizable: false,
		width: width,
		height: height,
		modal: true,
		close: function() {
			$dialog.dialog("destroy");
			$dialog.hide();
			disablePopUp();
		},		
		buttons: {
			'Yes' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
				$.post(base_url+'recruitment/_ajax_create_job_vacancy_xml',{},
					function(o){													
					dialogOkBox(o.message,{});				
				},"json");		
			},
			'No' : function(){
				$dialog.dialog("destroy");
				$dialog.hide();
				disablePopUp();
			}				
		}
	}).show().parent().find('.ui-dialog-titlebar-close').hide();
}

