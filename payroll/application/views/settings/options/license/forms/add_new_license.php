<script>
function callSuccessFunction(){
	load_branch_list(<?php ?>);
};
function callFailFunction(){alert("Error on SQL")}
	$(document).ready(function() {		
		 $("#addLicense").validationEngine({						
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/add_license',	
			ajaxSubmitMessage: "",		
			success : function() {load_license_dt();disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");disablePopUp();},
			unbindEngine:true,
			failure : function() {}

		});
	});
</script>
<div class="formWrapper">
	<form name="addLicense" id="addLicense" method="post" action="">    
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="formControl">License Type</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="license_type" class="validate[required] text-input text" id="license_type" />    
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Description</td>
            <td width="70%" valign="top" class="formLabel">
            	<textarea class="text" style="height:150px; width:249px;" id="description" name="description"></textarea>                
            </td>
        </tr>    
    </table>
    <br />
    <div align="right">
    <input type="submit" value="Save" />
    </div>
    </form>
</div>