<style>
	h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
	.text{width:250px;}
</style>
<script>
function callSuccessFunction(){
	load_branch_list(<?php ?>);
};
function callFailFunction(){alert("Error on SQL")}
	$(document).ready(function() {		
		 $("#addRelationship").validationEngine({						
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/update_employment_status',	
			ajaxSubmitMessage: "",		
			success : function() {load_employment_status_dt();disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");disablePopUp();},
			unbindEngine:true,
			failure : function() {}

		});
	});
</script>
<div class="formWrapper">
	<form name="addRelationship" id="addRelationship" method="post" action="">
    <input type="hidden" value="<?php echo $es->getId(); ?>" name="employment_status_id" id="employment_status_id" />    
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
        <tr>
          <td width="30%" valign="top" class="formControl">Code</td>
          <td width="70%" valign="top" class="formLabel">
          <input type="text" value="<?php echo $es->getCode(); ?>" name="code" class="validate[required] text-input text" id="code" />    
          </td>
        </tr>
        <tr>
          <td valign="top" class="formControl">Status</td>
          <td valign="top" class="formLabel"><input type="text" value="<?php echo $es->getStatus(); ?>" name="status" class="validate[required] text-input text" id="status" /></td>
        </tr>    
    </table>
    <br />
    <div align="right">
    <input type="submit" value="Save" />
    </div>
    </form>
</div>