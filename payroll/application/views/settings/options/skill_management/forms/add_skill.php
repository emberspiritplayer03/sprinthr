<style>
	h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
	.text{width:250px;}
</style>
<script>
function callSuccessFunction(){
	load_add_new_skill();
};
function callFailFunction(){alert("Error on SQL")}
	$(document).ready(function() {		
		 $("#skillManagement").validationEngine({						
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/add_skill', // add_subdivision_type
			ajaxSubmitMessage: "",		
			success : function() {load_skill_management_dt();disablePopUp(); var $dialog = $('#action_form');$dialog.dialog("destroy");disablePopUp();},
			unbindEngine:true,
			failure : function() {}

		});
	});
</script>
<div class="formWrapper">
	<form name="skillManagement" id="skillManagement" method="post" action="">
    <input type="hidden" value="<?php echo $p_id; ?>" name="parent_id" id="parent_id" />    
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="formControl">Skill</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="skill" class="validate[required] text-input text" id="skill" />    
            </td>
        </tr>       
    </table>
    <br />
    <div align="right">
    <input type="submit" value="Save" />
    </div>
    </form>
</div>