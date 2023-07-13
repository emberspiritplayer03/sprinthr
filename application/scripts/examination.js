var can_manage = "";
function load_add_examination() {

	$("#add_examination_button_wrapper").hide();
	$("#examination_form_wrapper").show();
}

function load_examination_datatable()
{
	element_id = 'examination_datatable';
			
	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
		elCell.innerHTML = "<a href='examination_details?examination_id="+id+"'>Display</a>"; 
	};						
			
		var columns = 	[
						
						 {key:"title",label:"Title",width:100,resizeable:true,sortable:true},
						 {key:"description",label:"Description",width:150,resizeable:true,sortable:true},
						 {key:"passing_percentage",label:"Passing Percentage",width:130,resizeable:true,sortable:true},
						 {key:"time_duration",label:"Duration (D:H:M)",width:130,resizeable:true,sortable:true},
						 {key:"options",label:"Action",width:50,resizeable:true,sortable:true,formatter:action}
						 ];
		var fields =	['id','title','description','passing_percentage','time_duration'];
		var height = 	'auto';
		var width = 	'100%';

		var controller = 'settings/_json_encode_examination_list?';		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);
		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
} 

function cancel_add_examination_form() {
	$("#examination_form").validationEngine('hide');
	$("#add_examination_button_wrapper").show();
	$("#examination_form_wrapper").hide();
}

function clear_add_applicant_examination_form()
{
	$.post(base_url + 'recruitment/_load_add_applicant_examination_form',{},function(o) {
		$("#applicant_examination_add_form_wrapper").html(o)								 
	})
}

function load_add_examination_confirmation(examination_id) {
	$("#confirmation").html(loading_message);
	$.post(base_url + 'settings/_load_add_examination_confirmation',{},function(o) {
		$("#confirmation").html(o)								 
	})
	
	encrypt = examination_id;
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
				   window.location = "examination_template"; 
                },
                buttons: {
					'View Exam Details' : function(){
							load_examination_datatable();					
						  	disablePopUp();
						  	$dialog.dialog("close");
							window.location = "examination_details?examination_id="+encrypt;
                    },
					'Cancel' : function(){
							load_examination_datatable();					
						  	disablePopUp();
						  	$dialog.dialog("close");
                    }
                }
            }).show();
}

//examination details for Recruitment Examination
function loadExaminationDetails(value) 
{
	if(value == 'add_new'){
		$("#title").val("");
		location.href = base_url + 'settings/examination_template'; 
	}else{
		var examination_id	 = $("#title").val();
		$("#description").val('loading...');
		$.post(base_url+'recruitment/_load_examination_description',{examination_id:examination_id},
		function(o){
			$("#description").val(o);
		});	
		
		$("#passing_percentage").val('loading...');
		$.post(base_url+'recruitment/_load_examination_percentage',{examination_id:examination_id},
		function(o){
			$("#passing_percentage").val(o);
		});	
	}

}
//examination details for Recruitment Examination

function loadExaminationDetailsSettings(id)
{
	var examination_id = id;	
	$.post(base_url+'settings/_load_examination_details',{examination_id:examination_id},
	function(o){
		$("#examination_details_table_wrapper").html(o);
	});	
}


function loadExaminationDetailsForm() {
	$("#examination_details_form").show();
	$("#examination_details_table_wrapper").hide();
	$("#examination_details_edit_form_wrapper").show();
}

function clearExaminationDetailsInlineErrorForm()
{
	$("#examination_details_form").validationEngine('hide');
}

function loadExaminationDetailsTable() {
	clearExaminationDetailsInlineErrorForm();
	$("#examination_details_form").hide();
	$("#examination_details_table_wrapper").show();
}


//end of examination detail

//question 

function loadQuestionAddForm() {
	
	$("#question_add_form_wrapper").show();
	$("#question_table_wrapper").hide();
	$("#question_edit_form_wrapper").hide();
	$("#question_add_button_wrapper").hide();
}

function loadQuickChoicesForm()
{
	var type = $("#type").val();
	if(type=='choices')
	{
		$("#quick_choices_table").show();	
	}else {
		$("#quick_choices_table").hide();	
	}
}

function hideQuestionEditForm(id)
{
	$(".question_edit_form").validationEngine('hide');
	$(".question_edit_form_"+id).validationEngine('hide');
	$("#question_edit_form_wrapper_"+id).hide();
	$("#question_table_wrapper_"+id).show();
}

function displayQuestionEditForm(id) 
{	
	clearQuestionInlineErrorForm(id);
	var question_id = id;
	
		var dialog_id = $("#question_edit_form_dialog_"+id);
		var $dialog = $(dialog_id);
		$dialog.dialog({
			title: "Confirmation",
			resizable: true,
			width: 450,
			height: 'auto',
			modal: true,
			close: function() {
					   $dialog.dialog("destroy");
					   $dialog.hide();
						disablePopUp();
					},
			buttons: {
					'Hide' : function(){
						$dialog.dialog("close");
						disablePopUp();
						
					}
				}
			}).show();		

}

function clearQuestionInlineErrorForm(id)
{
	$("#question_edit_form_"+id).validationEngine('hide');
	$("#question_add_form_"+id).validationEngine('hide');	
}

function loadQuestionTable() {
	clearQuestionInlineErrorForm();
	$("#question_add_form_wrapper").hide();
	$("#question_edit_form_wrapper").html('');
	$("#question_table_wrapper").show();
	$("#question_add_button_wrapper").show();
}


function loadQuestionDeleteDialog(id,examination_id)
{
	clearQuestionInlineErrorForm(id);
	var question_id = id;
	var examination_id = examination_id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#question_delete_wrapper");
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
					
					$.post(base_url+'settings/_delete_question',{question_id:question_id,examination_id:examination_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						loadExamQuestions(examination_id);
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadChoiceDeleteDialog(id,question_id)
{

	var choice_id = id;
	var question_id = question_id;
	var icon = '<br><div class="confirmation-trash"><div> ';
	var message = "Are you sure do you want to delete?";
	
	var dialog_id = $("#question_delete_wrapper");
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
					
					$.post(base_url+'settings/_delete_choice',{choice_id:choice_id},
					function(o){
						dialogOkBox('Successfully Deleted',{});
						loadQuestionChoices(question_id);
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function loadExamQuestions(id)
{
	var examination_id = id;
	loadQuestionTable();
	$("#question_table_wrapper").html(loading_message);	
	
	$.post(base_url+'settings/_load_exam_questions',{examination_id:examination_id},
	function(o){
		$("#question_table_wrapper").html(o);		
	});
	
	$.post(base_url+'settings/_load_add_form_questions',{examination_id:examination_id},
	function(o){
		$("#question_add_form_wrapper").html(o);		
	});
	
	
}

function loadQuestionChoices(id)
{
	var question_id = id;

	$("#choices_wrapper_"+question_id).html(loading_message);	
	
	$.post(base_url+'settings/_load_question_choices',{question_id:question_id},
	function(o){
		$("#choices_wrapper_"+question_id).html(o);		
	});
}

function addChoice(question_id)
{
	if($("#choice_"+question_id).val()!='') {
		choice = $("#choice_"+question_id).val();
		$.post(base_url+'settings/_insert_choice',{question_id:question_id,choice:choice},
		function(o){
			if(o>0) {
				dialogOkBox('Successfully Added',{});
				loadQuestionChoices(question_id);
			}else if(o==-1){
				dialogOkBox('Already Inserted the value',{});
			}else {
				dialogOkBox('Duplicate of Correct Answer',{});
			}
			
		});
		
	}else{
		dialogOkBox('Fill in the blank',{});
	}
	
}



//end of question
// examination datatable for applicant

function load_applicant_examination_datatable(date)
{
	element_id = 'applicant_examination_datatable';
			
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					var hash = oRecord.getData("hash");
					var applicant_name = oRecord.getData("applicant_name");
					var status = oRecord.getData("status");
				if(can_manage) {
					if(status=='For Checking') {
						elCell.innerHTML = "<a href='verify_examination?examination="+hash+"'>"+applicant_name+"</a>"; 	
					}else if(status=="Pending") {
						elCell.innerHTML = "<a href='examination_details?examination="+hash+"'>"+applicant_name+"</a>"; 		
					}else {
						elCell.innerHTML = "<a href='examination_summary?examination="+hash+"'>"+applicant_name+"</a>"; 		
					}
				} else {
					elCell.innerHTML = "---";
				}
					
			};						
			
		var columns = 	[
						 {key:"applicant_name",label:"Applicant Name",width:130,resizeable:true,sortable:false,formatter:action},
						 {key:"title",label:"Exam",width:130,resizeable:true,sortable:false},
						 {key:"exam_code",label:"Exam Code",width:70,resizeable:true,sortable:false},
						 {key:"schedule_date",label:"Schedule",width:110,resizeable:true,sortable:false},
						 {key:"passing_percentage",label:"Passing Percentage",width:120,resizeable:true,sortable:false},
						 {key:"status",label:"Status",width:80,resizeable:true,sortable:false},
						 {key:"result",label:"Result",width:70,resizeable:true,sortable:false},
						 {key:"scheduled_by",label:"Scheduled By",width:120,resizeable:true,sortable:false}
						 ];
		var fields =	['id','applicant_name','exam_code','passing_percentage','title','schedule_date','status','result','scheduled_by','hash'];
		var height = 	'100%';
		var width = 	'100%';

		var controller = 'recruitment/_json_encode_applicant_examination_list?date='+date+'&';		
		
		var datatable = new createDataTable(element_id,controller, columns, fields, height, width);

		datatable.rowPerPage(10);
		datatable.pageLinkLabel('First','Previous','Next','Last');
		datatable.show();
} 

function loadExamViewDialog()
{
	//alert(applicant_id);
	var hash = $("#hash").val();
	var applicant_id = $("#applicant_id").val();
	var status = $("#applicant_status").val();
	//message = "<br>Where do you want to?";
	var dialog_id = $("#examination_summary_wrapper");
	var $dialog = $(dialog_id);
	$dialog.dialog({
		title: "Choose Option",
		resizable: false,
		width: 390,
		height: 0,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
				'Applicant Profile' : function(){
					$dialog.dialog("close");
					disablePopUp();
					location.href = base_url + 'recruitment/profile?rid='+applicant_id+'&hash='+hash+'&status='+status+'#examination';
				},
				'Examination List' : function(){
					$dialog.dialog("close");
					disablePopUp();
					location.href = base_url + 'recruitment/examination';
				}
			}
		}).show();
}

function loadCancelExaminationDialog(examination_id)
{		
	var examination_id = examination_id;
	var icon = '<br><div class="confirmation-alert"><div> ';
	var message = "Are you sure do you want to cancel this examination?";
	
	var dialog_id = $("#examination_cancel_wrapper");
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
					showLoadingDialog('Cancelling...');
					
					$.post(base_url+'recruitment/_cancel_examination',{examination_id:examination_id},
					function(o){
						dialogOkBox('Examination was successfully cancelled',{});
						window.location = "examination";
						
					});
				},
				'No' : function(){
					$dialog.dialog("close");
					disablePopUp();
					
				}
			}
		}).show();		
}

function cancel_add_applicant_examination_form()
{
	$("#applicant_examination_add_form").validationEngine("hide");	
	$("#applicant_examination_add_form_wrapper").hide();
	$("#applicant_examination_button").show();
}

function load_add_applicant_examination()
{
	$("#applicant_examination_add_form_wrapper").show();
	$("#applicant_examination_button").hide();
}

function displayDelete(id) {
	$("#"+id).show();
}

function hideDelete(id) {
	$(".delete_question_nav").hide();
}

function displayChoiceDelete(id) {

	$("#option_"+id).show();	
}

function hideChoiceDelete(id) {
	$(".delete_choice_nav").hide();
}

function moveQuestionUp(question_id,exam_id) {	
	$.post(base_url+'settings/_question_move_up',{question_id:question_id},
	function(o){
		loadExamQuestions(exam_id);
	});	
}

function moveChoiceUp(choice_id,question_id) {
	$.post(base_url+'settings/_choice_move_up',{choice_id:choice_id},
	function(o){
		loadQuestionChoices(question_id);
	});	
}

function displayCheck(question_id)
{
	$("#cross_div_"+question_id).hide();
	$("#check_div_"+question_id).show();
	
	$('#btn_correct_'+question_id).addClass('active');
	$('#btn_incorrect_'+question_id).removeClass('active');
	
	$("#label_correct_"+question_id+ " active ").append();
	
	//$("#image_"+question_id).html($("#check_div_"+question_id).show());	
}

function displayCross(question_id)
{
	$("#check_div_"+question_id).hide();
	$("#cross_div_"+question_id).show()
	
	$('#btn_correct_'+question_id).removeClass('active');
	$('#btn_incorrect_'+question_id).addClass('active');
	
	//$("#image_"+question_id).html($("#cross_div_"+question_id).show());
}

