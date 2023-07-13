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

<div id="form_main" class="inner_form popup_form">
	<form name="addPositionForm" id="addPositionForm" method="post" action="<?php echo $add_position_action; ?>">
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Title:</td>
            <td><input type="text" value="" name="job_title" class="validate[required] text-input text" id="job_title" /></td>
        </tr>
  	</table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="#" onclick="javascript:closePositionPopUp('#position_wrapper_form');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>
