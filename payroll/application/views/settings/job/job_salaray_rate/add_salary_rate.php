<style>
h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
.text{width:250px;}
</style>
<script>
function callSuccessFunction(){

};
function callFailFunction(){alert("Error on SQL")}
$(document).ready(function() {
	$("#job_rate_form").validationEngine({
		ajaxSubmit: true,
		scroll: false,
		ajaxSubmitFile: base_url + 'settings/add_job_salary_rate',
		ajaxSubmitMessage: "",
		success : function() {load_job_salary_rate_list_dt();disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");},
		unbindEngine:true,
		failure : function() {}	
	});
});
</script>
<div class="formWrapper">
<form name="job_rate_form" id="job_rate_form" method="post" action="">
<h3 class="cinfo-header-form">Job Salary Rate</h3>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
    <td width="30%" valign="top" class="formControl">Job Level</td>
    <td width="70%" valign="top" class="formLabel">
    <input type="text" value="" name="job_level" class="validate[required] text-input text" id="job_level" />
    </td>
</tr>
<tr>
    <td width="30%" valign="top" class="formControl">Minimum Salary</td>
    <td width="70%" valign="top" class="formLabel">
    <input type="text" value="" name="minimum_salary" class="validate[required] text-input text" id="minimum_salary" />
    </td>
</tr>

<tr>
    <td width="30%" valign="top" class="formControl">Maximum Salary</td>
    <td width="70%" valign="top" class="formLabel">
    <input type="text" value="" name="maximum_salary" class="validate[required] text-input text" id="maximum_salary" />
    </td>
</tr>

<tr>
    <td width="30%" valign="top" class="formControl">Step Salary</td>
    <td width="70%" valign="top" class="formLabel">
    <input type="text" value="" name="step_salary" class="validate[required] text-input text" id="step_salary" />
    </td>
</tr>

</table>
<br />
<div align="right">
<input type="submit" value="Update" />
</div>
</form>
</div>