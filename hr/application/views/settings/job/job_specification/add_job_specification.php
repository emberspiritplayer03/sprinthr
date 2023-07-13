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
<div id="form_main" class="inner_form popup_form">
    <form name="taskForm" id="taskForm" method="post" action="">
    <!--<h3 class="section_title">Job Specification</h3>-->
    <div id="form_default">
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
        <td valign="top" class="field_label">Name:</td>
        <td valign="top">
        <input type="text" value="" name="name" class="validate[required] text" id="name" />
        </td>
    </tr>
    <tr>
        <td valign="top" class="field_label">Description:</td>
        <td valign="top">
        <textarea style="width:250px; min-width:250px;" name="description" class="validate[required] text" id="description" ></textarea>    
        </td>
    </tr>
    
    <tr>
        <td valign="top" class="field_label">Duties:</td>
        <td valign="top">
        <textarea style="width:250px; min-width:250px;" name="duties" class="validate[required] text" id="duties" ></textarea>     
        </td>
    </tr>
    
    </table>
    </div>
    <div id="form_default" class="form_action_section" align="center">
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
    	<tr>
            <td valign="top" class="field_label">&nbsp;</td>
            <td valign="top"><input class="blue_button" type="submit" value="Update" /></td>
        </tr>
    </table>    
    </div>
</form>
</div>