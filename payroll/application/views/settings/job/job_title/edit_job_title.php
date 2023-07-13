<?php //print_r($job_info);
	  //echo "test";
	  //echo $spec->getId;	
	$active = $job_info->getIsActive();
?>
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

<div class="formWrapper">
<form name="job_title_form" id="job_title_form" method="post" action="">
<input type="hidden" name="id" id="id" value="<?php echo $job_info->getId(); ?>" />
<h3 class="cinfo-header-form">Job Title</h3>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
    <td width="30%" valign="top" class="formControl">Title</td>
    <td width="70%" valign="top" class="formLabel">
     <input type="text" name="title" style="width:308px;" class="validate[required] text-input text" id="title" value="<?php echo $job_info->getTitle(); ?>"  />
    </td>
</tr>
<tr>
    <td width="30%" valign="top" class="formControl">Job Specification</td>
    <td width="70%" valign="top" class="formLabel">
   		    <select name="job_specification_id" id="job_specification_id" style="width:200px;">
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

<tr>
    <td width="30%" valign="top" class="formControl">Job Specification</td>
    <td width="70%" valign="top" class="formLabel">
    <select class="validate[required] text-input text" name="is_active" id="is_active" style="width:200px;"  >
          		<?php if($active == 1){ ?> 	
              	<option selected value="1" >Active</option>
                <option  value="2" >Inactive</option>               
                <?php  }else{ ?>
                <option value="1" >Active</option>
                <option selected value="2" >Inactive</option>      
                <?php } ?>
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

