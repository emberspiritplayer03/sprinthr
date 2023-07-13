<script>
//function callSuccessFunction(){
//	load_branch_list(<?php ?>);
//};
//function callFailFunction(){alert("Error on SQL")}
//	$(document).ready(function() {		
//		 $("#addLicense").validationEngine({						
//			ajaxSubmit: true,
//			scroll: false,
//			ajaxSubmitFile: base_url + 'settings/add_license',	
//			ajaxSubmitMessage: "",		
//			success : function() {load_license_dt();disablePopUp();var $dialog = $('#action_form');$dialog.dialog("destroy");disablePopUp();},
//			unbindEngine:true,
//			failure : function() {}
//
//		});
//	});
</script>
<div id="form_main" class="inner_form popup_form">
	<form name="addLicense" id="addLicense" method="post" action="<?php echo url('settings/add_license'); ?>">  
    <div id="form_default">   
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">License Type:</td>
            <td>
                <input type="text" value="" name="license_type" class="validate[required] text" id="license_type" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Description:</td>
            <td>
            	<textarea style="min-width:220px; width:220px;" class="text" id="description" name="description"></textarea>                
            </td>
        </tr>    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">&nbsp;</td>
            <td><input type="submit" class="blue_button" value="Save" /></td>
        </tr>          
    </table>
    </div>
    </form>
</div>