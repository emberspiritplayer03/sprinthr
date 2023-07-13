<?php //print_r($job);
	
?>

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
			ajaxSubmitFile: base_url + 'settings/update_job_employment_status',
			ajaxSubmitMessage: "",
			success : function() {load_job_employment_status_list_dt();disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");},
			unbindEngine:true,
			failure : function() {}
	
		});
	});
</script>

<?php 
	//foreach($job as $content):
	 $spec_id = ($job->getJobSpecificationId()!=0) ? $job->getJobSpecificationId() : 0;
	 $job_id =  ($job->getId()!=0)? $job->getId():0;
	
	$g = G_Job_Specification_Finder::findById($spec_id);
?>

<?php $stat = G_Job_Employment_Status_Finder::findByJobId($job_id); ?>
       
<div class="formWrapper">
<form name="taskForm" id="taskForm" method="post" action="">

 <?php if($stat != ''){ 
	$job_status_id = $stat->getId();
	$stat_id = $stat->getEmploymentStatusId();
		if($stat_id != 0){
		$emp_status = G_Settings_Employment_Status_Finder::findById($stat_id); 
		$status = $emp_status->getStatus();
		}else{
		$status = "Not set";	 		
		}
	 }else{
	$status = "Not set";	 
	}
  ?>
 <input type="hidden" name="status_id" id="status_id" value="<?php echo $job_status_id; ?>" />
<input type="hidden" name="job_id" id="job_id" value="<?php echo $job_id; ?>" />
<h3 class="cinfo-header-form">Job Specification</h3>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
    <td width="30%" valign="top" class="formControl">Job Title</td>
    <td width="70%" valign="top" class="formLabel">
   	<?php echo $job->getTitle(); ?>
    </td>
</tr>

<tr>
  <td width="30%" valign="top" class="formControl">Change Status</td>
  <td width="70%" valign="top" class="formLabel">
    <!--<select name="status" id="status" >
     	<?php $s = G_Settings_Employment_Status_Finder::findAll();
				foreach($s as $content2):
				?>
        			<?php if($status==$content2->getStatus){?>
                	<option selected="selected" value="<?php echo $content2->getId();?>"><?php echo $content2->getStatus();?></option>
        			<?php }else{?>
					<option value="<?php echo $content2->getId();?>"><?php echo $content2->getStatus();?></option>
                	<?php }?>
				<?php  endforeach; ?>
        <option value="">Not set</option>
     </select>-->
    <input type="text" name="status" id="status" value="<?php $content2->getStatus(); ?>" /></td>
</tr>

<?php //endforeach; ?>
</table>
<br />
<div align="right">
<input type="submit" value="Update" />
</div>
</form>
</div>