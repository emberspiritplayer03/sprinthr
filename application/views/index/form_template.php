<?php 
$insert = 'source/_insert';
$formId = 'myForm';
$datatable_id = 'test';
$controller = 'source/_load_user_datatable2';

?>


<script language="javascript" type="application/javascript">

	$(document).ready(function() {
	   $('#date').datepicker();
	 });
	 


	
</script>

<form id="<?php echo $formId; ?>" class="validationForm" name="form1" method="post" action="<?php echo url($insert); ?>">
  <table width="100%" border="0" cellpadding="4" cellspacing="2">
    <tr>
      <td width="25%">&nbsp;</td>
      <td width="63%">&nbsp;</td>
      <td width="12%">&nbsp;</td>
    </tr>
    <tr>
      <td>Firstname</td>
      <td><input class="text" type="text" name="firstname" id="firstname" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Lastname</td>
      <td><input class="text" type="text" name="lastname" id="lastname" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Age</td>
      <td><input class="text" type="text" name="age" id="age" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Gender</td>
      <td><select class="text" name="gender" id="gender">
        <option value="none">--select gender--</option>
        <option value="male">male</option>
        <option value="female">femaile</option>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Date</td>
      <td><input class="text" type="text" name="date" id="date" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Insert" /></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<div id="dialogConfirm" style="display:none">
<br />
Do you want to create new service request?
</div>
<script>

$('#user_id').textboxlist({unique: true, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'source/_get_names_autocomplete'}
}}});

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

jQuery.validator.addMethod("accept", function(value, element, param) { return value.match(new RegExp("^" + param + "$")); },"Invalid input");
/*firstname: {required:true,accept: "[a-zA-Z]+"},
			lastname: {required:true,accept: "[a-zA-Z]+"},
			middlename: {required:true,accept: "[a-zA-Z]+"},*/
			
var validator = $('<?php echo "#".$formId; ?>').validate({
		rules: {
			firstname: {required:true,accept:"[a-zA-Z]+"},
			lastname: {required:true},
			date:{required:true},
			gender:{selectNone:true},
			age:{accept:"[0-9]+"}
		},
		messages: {
			firstname: {required:"first name is required"},
			age:{accept: "Numbers only"}
		},
		submitHandler: function() {
			dialogYesNoForm('#dialogConfirm','<?php echo "#".$formId; ?>','Message','Attention','200','430px'); 
		},
		//errorElement: "div"
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});


//datatable//
</script>

<form id="form1" name="form1" method="post" action="">
  Search:
    <select name="field" id="field">
      <option value="u.firstname">Firstname</option>
      <option value="u.lastname">Lastname</option>
      <option value="u.middlename">Middlename</option>

    </select>
  <input type="text" name="search" id="search" />
  <input type="button" name="button" id="employeeSearchButton" value="Search" onclick="javascript:searchEmployee();" />
</form>
<div class="yui-skin-sam">
<div id="<?php echo $datatable_id; ?>"></div>
</div>

<script>

dataTableList('','','<?php echo $datatable_id; ?>','<?php echo $controller; ?>');
function searchEmployee()
{

	var searched = $("#search").val();
	var field = $("#field").val();

	dataTableList(searched,field,'<?php echo $datatable_id; ?>','<?php echo $controller; ?>');
	
}

function dataTableList(searched,field_name,datatable,load_datatable) {

	var action = function(elCell, oRecord, oColumn, oData) { 
		var id = oRecord.getData("id");
			elCell.innerHTML = "<a href='<?php echo url('hr/view_employee_details/'); ?>"+id+"'>View Details</a>"; 	
		
	};
			
		var columns = 	[						
						{key:"employee_id", label:"Employee ID", width:80, resizeable:true, sortable:true},
						{key:"firstname", label:"Firstname", width:110, resizeable:true, sortable:true},
						{key:"lastname", label:"Lastname", width:110, resizeable:true, sortable:true},
						{key:"middlename", label:"Middlename", width:110, resizeable:true, sortable:true},
						{key:"action", label:"Action", width:75, resizeable:true, sortable:false, formatter:action }
						];		
		var fields =	['id', 'branch_name','department_name','employee_id','firstname','lastname','middlename','action'];
		var height = 	'300px';
		var width = 	'850px';
		if(searched==undefined)
		{	
			var controller = load_datatable+'?';		
		}else
		{
			var controller = load_datatable+'?search='+ searched + '&fieldname='+field_name +'&' ;
		}
		
		var employeeDT = new createDataTable2(datatable,controller, columns, fields, height, width);
		employeeDT.rowPerPage(15);
		employeeDT.pageLinkLabel('First','Previous','Next','Last');
		employeeDT.show();
}
</script>

function _get_customer_names_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		if ($q != '') {
		$sql = "
				SELECT u.id, CONCAT(u.account_number,'<br>' ,u.firstname, ' ',u.lastname) as name
				FROM customer u
				WHERE u.firstname LIKE '%{$q}%' OR u.lastname LIKE '%{$q}%' OR u.account_number LIKE '%{$q}%'
				";
			$records = Model::runSql($sql, true);
			foreach ($records as $record) {
				$response[] = array($record['id'], $record['name'], null);
			}
		}
		if(count($response)==0)
		{
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);	
	}
