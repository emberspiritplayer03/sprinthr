<script>
$(document).ready(function() {	
	$('#addSubdivisionType').validationEngine({scroll:false});	
});

function checkForm()
{
	if ($('#addSubdivisionType').validationEngine({returnIsValid: true })) {		
		$('#addSubdivisionType').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {								
					load_subdivision_type_dt();							
					closeDialog('#' + DIALOG_CONTENT_HANDLER);	
					dialogOkBox(o.message,{});						
				} else {
					
				}
			},
			dataType:'json',
			beforeSubmit: function() {
				showLoadingDialog('Saving...');
			}
		});		
		return true;			
	}else{return false;}
}
</script>
<div id="form_main" class="inner_form popup_form">
	<form name="addSubdivisionType" id="addSubdivisionType" onsubmit="javascript:checkForm();"  method="post" action="<?php echo url('settings/add_subdivision_type'); ?>">
    <input type="hidden" value="<?php echo $p_id; ?>" name="parent_id" id="parent_id" />    
    <div id="form_default">
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Type:</td>
            <td>
                <input type="text" value="" name="type" class="validate[required] text" id="type" />    
            </td>
        </tr>       
    </table>
    </div>
    <div id="form_default" class="form_action_section">
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">&nbsp;</td>
            <td>
            	<input type="submit" class="blue_button" value="Save" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#addSubdivisionType');">Cancel</a>
            </td>
        </tr>          
    </table>
    </div>
    </form>
</div>