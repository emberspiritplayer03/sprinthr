<script>
$("#memo_date_created").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#memo_edit_form").validationEngine({scroll:false});
$('#memo_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#memo_notes_wrapper").html('');
			loadPage("#memo_notes");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="memo_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_memo'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<input type="hidden" name="attachment" value="<?php echo $details->getAttachment(); ?>" />
<div id="form_default">
<table>
  	 <tr>
  	   <td class="field_label">Title:</td>
  	   <td><input type="text" class="validate[required] text-input" name="title" id="title" value="<?php echo $details->title; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Memo:</td>
  	   <td><label for="memo"></label>
  	     <textarea class="validate[required]" name="memo" id="memo"><?php echo  ucfirst($details->memo); ?></textarea></td>
    </tr>  	
  	 <tr>
  	   <td class="field_label">Date Created:</td>
  	   <td><input class="validate[required] text-input" type="text" name="date_created" id="memo_date_created" value="<?php echo  ucfirst($details->date_created); ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Created By:</td>
  	   <td><input class="text-input" name="created_by" type="text" id="created_by" value="<?php echo  ucfirst($details->created_by); ?>" /></td>
    </tr>
     <tr>
  	   <td class="field_label">Attachment:</td>
  	   <td><input class="text-input" type="file" name="filename" id="filename" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadMemoDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete Memo</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadMemoTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
