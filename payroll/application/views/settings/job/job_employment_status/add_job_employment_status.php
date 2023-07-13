<style>
h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
.text{width:250px;}
</style>
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
<div class="formWrapper">
<form name="job_employment_status_form" id="job_employment_status_form" method="post" action="">
<h3 class="cinfo-header-form">Job Employment Status</h3>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
    <td width="30%" valign="top" class="formControl">Job</td>
    <td width="70%" valign="top" class="formLabel"><select name="job_id" id="job_id">
     <option value="">-- Please Select Job --</option>
    <?php foreach($job as $key =>$object) { ?>
    <option value="<?php echo $object->id; ?>"><?php echo $object->title; ?></option>
    <?php } ?>
     <option value=""><i>Add Job...</i></option>
    </select></td>
</tr>
<tr>
    <td width="30%" valign="top" class="formControl">Employment Status</td>
    <td width="70%" valign="top" class="formLabel"><!--<select name="employment_status_id" id="employment_status_id">
    <option value="">-- Please Select Employment Status --</option>
     <?php foreach($employment_status as $key =>$object) { ?>
    <option value="<?php echo $object->id; ?>"><?php echo $object->code; ?> - <?php echo $object->status; ?></option>
    <?php } ?>
       <option value=""><i>Add Employment Status...</i></option>
    </select>-->
      <input type="text" name="status" id="status" /></td>
</tr>

</table>
<br />
<div align="right">
<input type="submit" value="Add Job Employment Status" />
</div>
</form>
</div>