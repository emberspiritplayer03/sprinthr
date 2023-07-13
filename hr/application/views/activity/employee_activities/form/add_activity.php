<script>

$("#addActivityForm").validationEngine({});

$('#addActivityForm').ajaxForm({
			dataType: 'json',
			success:function(o) {
        if (o.is_saved) {
          $("#activity_wrapper_form").dialog("destroy");
          disablePopUp();
          $dialog.dialog('destroy');
          load_activity_dropdown();
          $("#activity_wrapper_form").html('');
        }
        else {
					closeDialog('#' + DIALOG_CONTENT_HANDLER);
					dialogOkBox(o.message, {});
				}
				
			},
			beforeSubmit:function() {
				showLoadingDialog('Saving...');	
			}
		});
</script>
<div id="form_main" class="inner_form popup_form">
	<form name="addActivityForm" id="addActivityForm" method="post" action="<?php echo $activity_form_action; ?>">
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">*Name</td>
            <td>: <input type="text" value="" name="activity_skills_name" class="validate[required] text-input text" id="activity_skills_name" /></td>
        </tr>
        <tr>
          <td class="field_label">Description</td>
          <td>: <input class="text-input text" type="text" name="activity_skills_description" id="activity_skills_description" /></td>
        </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="#" onclick="javascript:closeDialog('#activity_wrapper_form','#addActivityForm');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>