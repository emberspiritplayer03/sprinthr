<script>

$("#addCategoryForm").validationEngine({});

$('#addCategoryForm').ajaxForm({
			dataType: 'json',
			success:function(o) {
        if (o.is_saved) {
          $("#category_wrapper_form").dialog("destroy");
          disablePopUp();
          $dialog.dialog('destroy');
          load_category_dropdown();
          $("#category_wrapper_form").html('');
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
	<form name="addCategoryForm" id="addCategoryForm" method="post" action="<?php echo $category_form_action; ?>">
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">*Name</td>
            <td>: <input type="text" value="" name="activity_category_name" class="validate[required] text-input text" id="activity_category_name" /></td>
        </tr>
        <tr>
          <td class="field_label">Description</td>
          <td>: <input class="text-input text" type="text" name="activity_category_description" id="activity_category_description" /></td>
        </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="#" onclick="javascript:closeDialog('#category_wrapper_form','#addCategoryForm');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>