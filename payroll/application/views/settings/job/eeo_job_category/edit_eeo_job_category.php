<style>
h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
.text{width:250px;}
</style>
<script>
function callSuccessFunction(){
	
};
function callFailFunction(){alert("Error on SQL")}

$(document).ready(function() {
	$("#taskForm").validationEngine({
		ajaxSubmit: true,
		scroll: false,
		ajaxSubmitFile: base_url + 'settings/update_eeo_job_category',
		ajaxSubmitMessage: "",
		success : function() {load_job_eeo_job_list_dt();var $dialog = $('#action_form');$dialog.dialog("destroy");  disablePopUp();},
		unbindEngine:true,
		failure : function() {}	
	});
});
</script>
<div class="formWrapper">
<form name="taskForm" id="taskForm" method="post" action="">
<input type="hidden" name="id" id="id" value="<?php echo $eeo_info->getId(); ?>" />
<h3 class="cinfo-header-form">EEO Job Category</h3>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
    <td width="30%" valign="top" class="formControl">Category Name</td>
    <td width="70%" valign="top" class="formLabel">
    <input type="text" value="<?php echo $eeo_info->getCategoryName(); ?>" name="category_name" class="validate[required] text-input text" id="category_name" />
    </td>
</tr>


</table>
<br />
<div align="right">
<input type="submit" value="Update" />
</div>
</form>
</div>