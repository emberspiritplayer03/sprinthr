<style>
h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
.text{width:250px;}
</style>
<script>
function callSuccessFunction(){
	load_my_pending_tasks();
};
function callFailFunction(){alert("Error on SQL")}
	$(document).ready(function() {
		$("#taskForm").validationEngine({
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/add_job_specification',
			ajaxSubmitMessage: "",
			success : function() {load_job_specification_list_dt();disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");},
			unbindEngine:true,
			failure : function() {}
	});
});
</script>
<div class="formWrapper">
<form name="taskForm" id="taskForm" method="post" action="">
<h3 class="cinfo-header-form">Job Specification</h3>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
    <td width="30%" valign="top" class="formControl">Name</td>
    <td width="70%" valign="top" class="formLabel">
    <input type="text" value="" name="name" class="validate[required] text-input text" id="name" />
    </td>
</tr>
<tr>
    <td width="30%" valign="top" class="formControl">Description</td>
    <td width="70%" valign="top" class="formLabel">
    <textarea style="width:249px; height:60px;" name="description" class="validate[required] text-input text" id="description" ></textarea>    
    </td>
</tr>

<tr>
    <td width="30%" valign="top" class="formControl">Duties</td>
    <td width="70%" valign="top" class="formLabel">
    <textarea style="width:249px; height:60px;" name="duties" class="validate[required] text-input text" id="duties" ></textarea>     
    </td>
</tr>

</table>
<br />
<div align="right">
<input type="submit" value="Update" />
</div>
</form>
</div>