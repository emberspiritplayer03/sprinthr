<script>
$("#addPositionForm").validationEngine({scroll:false});

$('#addPositionForm').ajaxForm({
			success:function(o) {
				
				$("#position_wrapper_form").dialog("destroy");
				disablePopUp();
				$dialog.dialog('destroy');
				load_position_dropdown();
				$("#position_wrapper_form").html('');
			},
			beforeSubmit:function() {
				showLoadingDialog('Saving...');	
			}
		});
</script>

<div class="formWrapper">
	<form name="addPositionForm" id="addPositionForm" method="post" action="<?php echo $add_position_action; ?>">
     
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="formControl">Title</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" style="width:308px;" value="" name="job_title" class="validate[required] text-input text" id="job_title" />    
            </td>
        </tr>     
    </table>
    <br />
    <div align="right">
    <input class="curve blue_button" type="submit" value="Save" />
    <a href="#" onclick="javascript:closePositionPopUp('#position_wrapper_form');">cancel</a>
  
    </div>
  </form>
</div>
