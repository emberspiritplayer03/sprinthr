<script>
$(function() {
$("input#autocomplete").autocomplete({
    source:  base_url + 'source/_autocomplete',
	select: function( event, ui ) {
				$( "#autocomplete" ).val( ui.item.label );
				$( "#project-id" ).val( ui.item.id );
				return false;
			}
	});
});

function getValue() {
	alert($("#autocomplete").val());
}
</script>

Autocomplete
<input id="autocomplete" class="curve" />
<input id="project-id" type="text" />
<a href="javascript:getValue();">Value</a>
<div class="formWrapper">
  <form id="myForm" method="post" class="validationForm" action="<?php echo url('debtor/register_debtor');?>">
<table width="542" border="0" cellpadding="3" cellspacing="0">
  <tr>
    <td width="185" valign="top" class="formLabel">Username</td>
    <td width="345" valign="top" class="formControl"><input type="text" class="textbox" name="username" id="username" /></td>
  </tr>
  <tr>
    <td valign="top" class="formLabel">Last Name</td>
    <td valign="top" class="formControl"><input type="text" class="textbox" name="last_name" id="last_name" /></td>
  </tr>
  <tr>
    <td valign="top" class="formLabel">Address</td>
    <td valign="top" class="formControl"><textarea name="address" cols="30" rows="5" style="height:50px" id="address"></textarea></td>
  </tr>
  <tr>
    <td valign="top" class="formLabel">Date Started</td>
    <td valign="top" class="formControl"><input type="text" class="textbox" name="date_started" id="date_started" /></td>
  </tr>
  <tr>
    <td valign="top"></td>
    <td valign="top"><span class="button"><span><button id="button" name="button" type="submit">Compute</button></span></span></td>
  </tr>    
</table>
</form>
</div>

<script language="javascript" type="application/javascript">

$(document).ready(function() {
	
	   $('#date_started').calendar();
	   $.validator.addMethod("checkAvailability",function(value,element){
	 var x= $.ajax({
		url: base_url+'source/json_username_check',
		type: 'POST',
		async: false,
		data: "username=" + value + "&checking=true",
	 }).responseText;

	var s = new String(x);
	x=s.trim();

	 if(x=="true") return true;
	 else return false;
	},"Sorry, this user name is not available");


	   
});


	var validator = $("#myForm").validate({
		rules: {
			username: {
				required:true,
				checkAvailability:true
			},
			last_name: {required:true},
			address: {required:true}
		},
		messages: {
			first_name: {required:"first name is required"}
		},
		submitHandler: function() {
			$('#myForm').ajaxSubmit({
				success:function(o) {						
					alert('hello world');
				}
			});
		},
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
		//errorElement: "div"
	});
</script>


<div class="yui-skin-sam">
	<div id="testingDatatable"></div>
</div>

<div class="yui-skin-sam">

<form id="formTakeExam"  class="validationForm" method="post" action="<?php echo url('index/_insert');?>">
<input type="hidden" name="result" value="pending" />
<input type="hidden" name="exam_code" value="<?php echo substr(md5(date("Y-m-d H:i:s")),0,7); ?>" />
  <table width="500" border="0" cellpadding="6" cellspacing="3">
    <tr>
      <td width="148">Applicant Name</td>
      <td width="295"><input type="text" name="user_id" id="user_id" /></td>
      <td width="35">&nbsp;</td>
    </tr>
    <tr>
      <td>Date Picker</td>
      <td><input type="text" name="date_exam" id="date_exam" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Date Time Picker</td>
      <td><input type="text" name="examiner_id" id="datetime" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Date Range Picker</td>
      <td> <input name="daterange" type="text" id="rangeA" value="" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><input type="submit" value="Set Exam" /></td>
      <td><input type="button" name="button2" id="button2" value="Button" onclick="javascript:sampleDialog();" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><div id="exam_schedule_datatable"></div></td>
    </tr>
  </table>
</form>
</div>
<div id="examSummaryWrapper">
	<div id="examResultSummary"></div>
</div>
<script language="javascript">

$(function() {
	 $('#rangeA').daterangepicker({arrows:true,dateFormat: 'yy-mm-dd'}); 
    $("#date_exam").datepicker(
		{ dateFormat: 'yy-mm-dd',minDate: '+1d' }
		);
		
	
});


$('#user_id').textboxlist({unique: true, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'source/_get_names_autocomplete'}
}}});



viewExamSchedules();

function test() {
 alert('hello');	
}
function viewExamSchedules()
{
	element_id = 'exam_schedule_datatable';
	var action = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					elCell.innerHTML = "<center> <a href=\"javascript:deleteExamQuestion("+ id +");\">Remove</a>  <a href=\"javascript:viewExamResult("+ id +");\">Display</a>"; 
	
			};
			
	var test = function(elCell, oRecord, oColumn, oData) { 
				var id = oRecord.getData("id");
					elCell.innerHTML = "<a onclick='test()'>" + oData + '</a>'; 
	
			};						
			
		var columns = 	[
						 {key:"applicant_name",label:"Applicant Name",width:110,resizeable:true,sortable:true},
						 {key:"firstname",label:"Exam Name",width:100,resizeable:true,sortable:true, formatter:test},
						 {key:"lastname",label:"Exam Date",width:80,resizeable:true,sortable:true},
						 {key:"examiner_name",label:"Proctor",width:100,resizeable:true,sortable:true},
						 {key:"result",label:"Result",width:70,resizeable:true,sortable:true},
						 {key:"exam_code",label:"Exam Code",width:100,resizeable:true,sortable:true},
						 {key:"action",label:"Action",width:100,resizeable:true,sortable:true, formatter:action}];
		var fields =	['id','firstname','lastname','date_exam', 'examiner_name','result','exam_code'];
		var height = 	'200px';
		var width = 	'900px';

		var controller = 'source/_load_user_datatable2?';		
		
		
		var ExamQuestion = new createDataTable2(element_id,controller, columns, fields, height, width);
		ExamQuestion.rowPerPage(10);
		ExamQuestion.pageLinkLabel('First','Previous','Next','Last');
		ExamQuestion.show();
}
  
	
function sampleDialog() {
	
	var $dialog = $("#examSummaryWrapper");
	$dialog.dialog({
		title: "Exam Summary",
		resizable: true,
		width: 390,
		height: 340,
		modal: true,
		close: function() {
				   $dialog.dialog("destroy");
				   $dialog.hide();
					disablePopUp();
				},
		buttons: {
							'Ok' : function(){
								$dialog.dialog("close");
								disablePopUp();
								//location.href = base_url + 'examination/take_an_exam/'+exam_code;
								
							},
							'Yes' : function(){
								$dialog.dialog("close");
								disablePopUp();
								//location.href = base_url + 'examination/take_an_exam/'+exam_code;
								
							}
						}
					}).show();	
}


jQuery.validator.addMethod(
  "selectNone",
  function(value, element) {
    if (element.value == "none")
    {
      return false;
    }
    else return true;
  },
  "Please select an option."
);

	var validator = $("#formTakeExam").validate({
		rules: {
			user_id: {required:true},
			date_exam: {required:true},
			examiner: {required:true},
			exam_template_id: {selectNone:true}
			
		},
		messages: {
			
		},
		submitHandler: function() {
			
			$("#formTakeExam").ajaxSubmit({
							success:function(o) {
								viewExamSchedules();
							}
						});
			
			
		},
		errorElement: "div"
	});
	

</script> 


