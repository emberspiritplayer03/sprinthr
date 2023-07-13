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
		 $("#editPayPeriod").validationEngine({						
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/update_pay_period',	
			ajaxSubmitMessage: "",		
			success : function() {load_pay_period_dt(); disablePopUp(); var $dialog = $('#action_form');$dialog.dialog("destroy");disablePopUp();},
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
	<form name="editPayPeriod" id="editPayPeriod" method="post" action="">   
     <input type="hidden" value="<?php echo $pp->getId() ?>" id="pay_period_id" name="pay_period_id" />   
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="formControl">Pay Period Code</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo $pp->getPayPeriodCode(); ?>" name="pay_period_code" class="validate[required] text-input text" id="pay_period_code" />    
            </td>
        </tr>  
        <tr>
            <td width="30%" valign="top" class="formControl">Pay Period Name</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="<?php echo $pp->getPayPeriodName(); ?>" name="pay_period_name" class="validate[required] text-input text" id="pay_period_name" />    
            </td>
        </tr>
        <tr>
          <td valign="top" class="formControl">Cut Off</td>
          <td valign="top" class="formLabel"><input type="text" value="<?php echo $pp->getCutOff(); ?>" name="cut_off" class="validate[required] text-input text" id="cut_off" /></td>
        </tr>
        <tr>
          <td valign="top" class="formControl">Is Default</td>
          <td valign="top" class="formLabel"><input type="text" value="<?php echo $pp->getIsDefault(); ?>" name="is_default" class="validate[required] text-input text" id="is_default" /></td>
        </tr>    
    </table>
    <br />
    <div align="right">
    <input type="submit" value="Update" />
    </div>
    </form>
</div>