<script>
function callSuccessFunction(){

};
function callFailFunction(){alert("Error on SQL")}
$(document).ready(function() {
	$("#job_employment_status_form").validationEngine({
		ajaxSubmit: true,
		scroll: false,
		ajaxSubmitFile: base_url + 'settings/add_job_employment_status',
		ajaxSubmitMessage: "",
		success : function() {load_job_employment_status_list_dt();disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");},
		unbindEngine:true,
		failure : function() {}	
	});
});
</script>
<div id="form_main" class="inner_form popup_form wider">
    <form name="job_employment_status_form" id="job_employment_status_form" method="post" action="">
    <!--<h3 class="section_title">Job Employment Status</h3>-->
    <div id="form_default">
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
        <td valign="top" class="field_label">Job:</td>
        <td valign="top"><select name="job_id" id="job_id" class="select_option_sched">
         <option value="">-- Please Select Job --</option>
        <?php foreach($job as $key =>$object) { ?>
        <option value="<?php echo $object->id; ?>"><?php echo $object->title; ?></option>
        <?php } ?>
         <option value=""><i>Add Job...</i></option>
        </select></td>
    </tr>
    <tr>
        <td valign="top" class="field_label">Employment Status:</td>
        <td valign="top"><!--<select name="employment_status_id" id="employment_status_id">
        <option value="">-- Please Select Employment Status --</option>
         <?php foreach($employment_status as $key =>$object) { ?>
        <option value="<?php echo $object->id; ?>"><?php echo $object->code; ?> - <?php echo $object->status; ?></option>
        <?php } ?>
           <option value=""><i>Add Employment Status...</i></option>
        </select>-->
          <input type="text" name="status" id="status" /></td>
    </tr>
    
    </table>
    </div>
    <div id="form_default" class="form_action_section" align="center">
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
    	<tr>
            <td valign="top" class="field_label">&nbsp;</td>
            <td valign="top"><input class="blue_button" type="submit" value="Add Job Employment Status" /></td>
        </tr>
    </table>    
    </div>
    </form>
</div>