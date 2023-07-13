<script>
function callSuccessFunction(){
	load_membership_type_list(<?php ?>);
};
function callFailFunction(){alert("Error on SQL")}
	$(document).ready(function() {		
		 $("#addMembership").validationEngine({						
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/add_membership_type',	
			ajaxSubmitMessage: "",		
			success : function() {load_membership_type_dt();var $dialog = $('#action_form');$dialog.dialog("destroy");disablePopUp();},
			unbindEngine:true,
			failure : function() {}

		});
	});
</script>
<div class="formWrapper">
	<form name="addMembership" id="addMembership" method="post" action="">   
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="formControl">Type</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="type" class="validate[required] text-input text" id="type" />    
            </td>
        </tr>          
    </table>
    <br />
    <div align="right">
    <input type="submit" value="Save" />
    </div>
    </form>
</div>