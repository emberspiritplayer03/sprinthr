<script type="text/javascript">
$(function() {
	$("#date_created").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
		changeYear: true});
	$("#memo_edit_form").validationEngine({scroll:false});
});

$(document).ready(function() {		
	$('#memo_edit_form').ajaxForm({
		success:function(o) {
			if (o.is_added == 1) {
				//hide_memo_form();
				load_memo_template_list_dt();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);				
				$("#message_container").html(o.message);
				$('#message_container').show();
				disablePopUp();
				var $dialog = $('#action_form');
				$dialog.dialog("destroy");
			} else {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);										
				$("#message_container").html(o.message);
				$('#message_container').show();
				disablePopUp();
				var $dialog = $('#action_form');
				$dialog.dialog("destroy");				
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Updating...');
			return true;
		}
	});		
});
</script>




<form action="<?php echo $action; ?>" method="post"  name="memo_edit_form" id="memo_edit_form" >
<input type="hidden" id="memo_id" name="memo_id" value="<?php echo $memo_id; ?>" >
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" >
<div id="form_main" class="employee_form_summary">
    <div id="form_default">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" class="field_label">Title:</td>
            <td align="left" valign="top"><input type="text" value="<?php echo $memo_info->getTitle();?>" name="title" class="validate[required] text" id="title" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Content:</td>
            <td align="left" valign="top">
            <textarea name="content" id="content" class="validate[required] text" cols="45" rows="5"><?php echo $memo_info->getContent();?></textarea>
            </td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Date Created:</td>
            <td align="left" valign="top"><input type="text" name="date_created" value="<?php echo $memo_info->getDateCreated(); ?>" class="validate[required] text" id="date_created" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Created by:</td>
            <td align="left" valign="top"><input type="text" value="<?php echo $memo_info->getCreatedBy(); ?>" name="created_by" class="validate[required] text" id="created_by" /></td>
          </tr>
        </table>    
    </div>
   
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" valign="top"><input type="submit" value="Update Memo" class="curve blue_button" /></td>
          </tr>
        </table>  
    </div>
</div>
</form>
<!--</script>-->