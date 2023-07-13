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
		 $("#editLocation").validationEngine({						
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/update_location',	
			ajaxSubmitMessage: "",		
			success : function() {load_location_dt(); disablePopUp(); var $dialog = $('#action_form');$dialog.dialog("destroy");disablePopUp();},
			unbindEngine:true,
			failure : function() {}

		});
	});
function generateCode()
{
	var location = document.getElementById("location").value;
	var code     = location.substr(0,3)
	document.getElementById("code").value = code.toUpperCase();	
}
</script>
<div class="formWrapper">
	<form name="editLocation" id="editLocation" method="post" action="">
    <input type="hidden" value="<?php echo $l->getId() ?>" id="location_id" name="location_id" />   
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="formControl">Location Name</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo $l->getLocation(); ?>" name="location" onchange="javascript:generateCode();" class="validate[required] text-input text" id="location" />    
            </td>
        </tr>  
        <tr>
            <td width="30%" valign="top" class="formControl">Location Code</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo $l->getCode(); ?>" name="code" class="validate[required] text-input text" id="code" />    
            </td>
        </tr>    
    </table>
    <br />
    <div align="right">
    <input type="submit" value="Save" />
    </div>
    </form>
</div>