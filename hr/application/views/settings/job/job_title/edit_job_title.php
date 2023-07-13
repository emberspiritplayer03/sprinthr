<?php //print_r($job_info);
	  //echo "test";
	  //echo $spec->getId;	
	$active = $job_info->getIsActive();
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
			ajaxSubmitFile: base_url + 'settings/update_job_title',
			ajaxSubmitMessage: "",
			success : function() {load_job_list_dt();disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");},
			unbindEngine:true,
			failure : function() {}
		});
	});
</script>

<div id="form_main" class="inner_form popup_form wider">
<form name="job_title_form" id="job_title_form" method="post" action="">
<input type="hidden" name="id" id="id" value="<?php echo $job_info->getId(); ?>" />
<!--<h3 class="section_title">Job Title</h3>-->
<div id="form_default">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
    <td valign="top" class="field_label">Title:</td>
    <td valign="top">
     <input type="text" name="title" class="validate[required] text" id="title" value="<?php echo $job_info->getTitle(); ?>"  />
    </td>
</tr>
<tr>
    <td valign="top" class="field_label">Job Specification:</td>
    <td valign="top">
   		    <select name="job_specification_id" id="job_specification_id" class="select_option_mini">
              	<?php foreach($spec as $content): ?>	
                      <?php  if($job_info->getJobSpecificationId == $content->getId ){ ?>                   							     
				    	<option selected value="<?php echo $content->getId(); ?>" ><?php echo $content->getName(); ?></option> 				
                      <?php }else{  ?>
                        <option selected value="<?php echo $content->getId(); ?>" ><?php echo $content->getName(); ?></option> 				
                   	  <?php  } ?>
                    
				 <?php  endforeach; ?>     
                 <option onClick="javascript:load_add_job_specification();" >Others</option>            
        	</select>
    </td>
</tr>

<!-- <tr>
    <td valign="top" class="field_label">Job Specification</td>
    <td valign="top">
    <select class="validate[required] text select_option_mini" name="is_active" id="is_active">
		<?php //if($active == 1){ ?> 	
        <option selected value="1" >Active</option>
        <option  value="2" >Inactive</option>               
        <?php  //}else{ ?>
        <option value="1" >Active</option>
        <option selected value="2" >Inactive</option>      
        <?php //} ?>
    </select>
    </td>
</tr> -->

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

