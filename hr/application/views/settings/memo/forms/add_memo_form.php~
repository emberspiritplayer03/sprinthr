<script type="text/javascript">
$(function() {
	$("#date_created").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
		changeYear: true});
	$("#memo_form").validationEngine({scroll:false});
});

$(document).ready(function() {		
	$('#memo_form').ajaxForm({
		success:function(o) {
			if (o.is_added == 1) {
				hide_memo_form();
				load_memo_template_list_dt();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);				
				$("#message_container").html(o.message);
				$('#message_container').show();
			} else {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);										
				$("#message_container").html(o.message);
				$('#message_container').show();
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
<div class="formwrap inner_form">
<form action="<?php echo $action; ?>" method="post"  name="memo_form" id="memo_form" >
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" >
<h3 class="form_sectiontitle"><span>Add Memo</span></h3>
<div id="form_main" class="employee_form_summary">
    <div id="form_default">
        <h3 class="section_title">Memo Detail</h3>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" class="field_label">Title:</td>
            <td align="left" valign="top"><input type="text" value="" name="title" class="validate[required] text" id="title" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Content:</td>
            <td align="left" valign="top"><textarea name="content" id="content" class="validate[required] text" cols="45" rows="5"></textarea></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Date Created:</td>
            <td align="left" valign="top"><input type="text" value="" name="date_created" class="validate[required] text" id="date_created" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Created by:</td>
            <td align="left" valign="top"><input type="text" value="<?php echo $au_name; ?>" name="created_by" class="validate[required] text" id="created_by" /></td>
          </tr>
        </table>    
    </div>
   
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" class="field_label">&nbsp;</td>
            <td align="left" valign="top"><input type="submit" value="Add Memo" class="curve blue_button" />&nbsp;<a href="javascript:hide_memo_form();">Cancel</a></td>
          </tr>
        </table>  
    </div>
</div>
</form>
</div>



<!--</script>-->