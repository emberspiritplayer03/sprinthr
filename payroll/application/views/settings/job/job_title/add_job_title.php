<?php //print_r($spec);
	  //echo $spec->getId;	

?>
<script>
function callSuccessFunction(){
	load_my_pending_tasks();
};
function callFailFunction(){alert("Error on SQL")}
	$(document).ready(function() {
		$("#job_title_form").validationEngine({
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/add_job_title',
			ajaxSubmitMessage: "",
			success : function() {
				load_job_list_dt();
				disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");},
			unbindEngine:true,
			failure : function() {}
	});
});
</script>

<div class="formWrapper">
<form name="job_title_form" id="job_title_form" method="post" action="">
<h3 class="cinfo-header-form">Job Title</h3>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
    <td width="30%" valign="top" class="formControl">Title</td>
    <td width="70%" valign="top" class="formLabel">
    <input type="text" style="width:308px;" value="" name="title" class="validate[required] text-input text" id="title" />
    </td>
</tr>
<tr>
    <td width="30%" valign="top" class="formControl">Job Specification</td>
    <td width="70%" valign="top" class="formLabel">
   		   <select name="job_specification_id" id="job_specification_id" style="width:200px;">
              	<?php foreach($spec as $content): ?>								     
				    <option value="<?php echo $content->getId(); ?>" ><?php echo $content->getName(); ?></option> 				
				 <?php  endforeach; ?>     
                 <option onClick="javascript:load_add_job_specification();" >Others</option>            
        	</select>
    </td>
</tr>

<tr>
    <td width="30%" valign="top" class="formControl">Job Specification</td>
    <td width="70%" valign="top" class="formLabel">
    <select class="validate[required] text-input text" style="width:200px;" name="is_active" id="is_active"  >
              	<option value="1" >Active</option>
                <option value="2" >Inactive</option>               
        	</select>
    </td>
</tr>

</table>
<br />
<div align="right">
<input type="submit" value="Update" />
</div>
</form>
</div>

