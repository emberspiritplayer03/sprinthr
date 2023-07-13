
<style>
h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
.text{width:250px;}
</style>
<script type="text/javascript">
	$(function() {
		$("#birthdate").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
			changeYear: true,maxDate: '-16Y'});
	});


/*function callSuccessFunction(o){

	load_my_pending_tasks(<?php ?>);
};*/

function callFailFunction(){alert("Error on SQL")}
	$(document).ready(function() {
		$("#employee_form").validationEngine({
			ajaxSubmit: true,
			scroll: true,
			ajaxSubmitFile: base_url + 'employee/add_employee',
			ajaxSubmitMessage: "",
			success : function() {
				
				load_add_employee_confirmation(); },
			unbindEngine:true,
			failure : function() {}
	});
});


/*jQuery(document).ready(function(){
                $("#job_title_form").validationEngine({
					ajaxFormValidation: false

                  //  onAjaxFormComplete: base_url + 'recruitment/add_candidate',
				 // ajaxSubmit: true,
				  //ajaxSubmitFile: base_url + 'recruitment/add_candidate',
                });
            });
*/
</script>

<div class="formWrapper">

<form action="" method="post" enctype="multipart/form-data" name="employee_form" id="employee_form" >
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />



<table width="100" border="0" cellpadding="3" cellspacing="0">
<tr>
  <td colspan="4" valign="top" class="formControl"><h3 class="cinfo-header-form" >Add Employee</h3></td>
  </tr>
<tr>
    <td width="19%" valign="top" class="formControl"><strong>Desired Position</strong></td>
    <td colspan="3" valign="top" class="formLabel">
      <input type="text"  value="" name="job_id" class="validate[required] curve" id="job_id" />
    </td>
    </tr>
<tr>
  <td valign="top" class="formControl"><strong>Branch Name</strong></td>
  <td colspan="3" valign="top" class="formLabel">
  <select name="branch_id" id="branch_id" class="curve" onchange="javascript:selectBranch();">
    <option value="">--select branch--</option>
  
   
  <?php foreach($branch as $key=>$val) { ?>
     <option value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
   
  <?php } ?>
    <option value="add">..Add Branch..</option>
  </select></td>
</tr>
<tr>
  <td valign="top" class="formControl"><strong>Employee Code</strong></td>
  <td colspan="3" valign="top" class="formLabel"><span class="formLabel">
    <input type="text"  value="" name="employee_code" class="validate[required] curve" id="employee_code" />
  </span></td>
  </tr>
<tr>
  <td colspan="4" valign="top" class="formControl"><strong>Personal Information</strong></td>
  </tr>
<tr>
  <td valign="top" class="formLabel"><input name="lastname" type="text" class="validate[required] text-input text curve" id="lastname" style="width:200px;" value="" />
    <br />
    <em>Lastname</em></td>
  <td width="19%" valign="top" class="formLabel"><input type="text" style="width:200px;" value="" name="firstname" class="validate[required] text-input text curve" id="firstname" />
    <br />
    <em>Firstname</em></td>
  <td width="43%" valign="top" class="formLabel"><input type="text" style="width:200px;" value="" name="middlename" class="validate[required] text-input text curve" id="middlename" />
    <br />
    <em>Middlename </em></td>
  <td width="19%" valign="top" class="formLabel">&nbsp;</td>
  </tr>

<tr>
  <td align="center" valign="top" class="formLabel">&nbsp;</td>
  <td align="center" valign="top" class="formLabel">&nbsp;</td>
  <td align="center" valign="top" class="formLabel">&nbsp;</td>
  <td valign="top" class="formLabel">&nbsp;</td>
</tr>
<tr>
  <td colspan="3" align="center" valign="top" class="formLabel"><input type="submit" value="Add New Employee" class="curve" />
    
    
    <a href="javascript:cancel_add_employee_form();">Cancel</a></td>
  <td valign="top" class="formLabel">&nbsp;</td>
</tr>

</table>
<br />
<div align="right"></div>
</form>
</div>


<script>
$('#job_id').textboxlist({unique: true,max:1, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'recruitment/_autocomplete_load_job_name'}
}}});

</script>