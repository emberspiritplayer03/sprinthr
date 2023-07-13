<script>
$("#addStatus").validationEngine({scroll:false});

$('#addStatus').ajaxForm({
			success:function(o) {
				
				$("#status_wrapper_form").dialog("destroy");
				disablePopUp();
				$dialog.dialog('destroy');
				load_status_dropdown();
				$("#status_wrapper_form").html('');
			},
			beforeSubmit:function() {
				
				showLoadingDialog('Saving...');	
			}
		});
</script>
</script>
<div id="form_main" class="inner_form popup_form">
	<form name="addStatus" id="addStatus" method="post" action="<?php echo $add_status_action; ?>">
   <input type="hidden" name="position_id" id="position_id_form" value="<?php echo $position_id; ?>" />
    <div id="form_default">
    <table width="100%"> 
        <tr>
          <td class="field_label">Code:</td>
          <td>
          <input type="text" value="" name="code" class="validate[required] text-input text" id="code" />    
          </td>
        </tr>
        <tr>
          <td class="field_label">Status:</td>
          <td><input type="text" value="" name="status" class="validate[required] text-input text" id="status" /></td>
        </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="#" onclick="javascript:closeStatusPopUp('#status_wrapper_form');">Cancel</a></td>
          </tr>
      </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>