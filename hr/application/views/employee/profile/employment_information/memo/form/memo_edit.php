<script>
$(function(){ 
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

	$("#date_of_offense").datepicker({    
    dateFormat:'yy-mm-dd',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true,
    onSelect    :function() { 
	    }
	 }); 

}); 
</script>

<script type="text/javascript">
    $('#memo_id').change(function(event) {
		  var memo_id = $('#memo_id').val();
		  $('#memo_container').html("<a href='javascript:showMemoTemplate("+memo_id+");'>View Template</a>");
		  
        /*$.post(base_url+'employee/_get_memo_content',{memo_id:memo_id},
            function(o) {
                $('#memo_container').html(o);
            }
        );*/
    }); 	
</script>

<form id="memo_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_memo'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<input type="hidden" name="attachment" value="<?php echo $details->getAttachment(); ?>" />
<div id="form_default">
<?php 
/*  Utilities::displayArray($details);*/
?>
<table>
    <tr>
      <td class="field_label">Memo Template:</td>
      <td>
		<select id="memo_id" name="memo_id">
			<?php foreach($memo_template as $memo_data): ?>
		 		<option <?php echo $details->getMemoId() == $memo_data->getId() ? 'selected="selected"' : ""; ?> value="<?php echo $memo_data->getId(); ?>"><?php echo $memo_data->getTitle(); ?></option>
		   <?php endforeach; ?>
		</select>
		<!-- <a href='javascript:showMemoPTemplate(<?php //echo $details->id; ?>);'>View Previous Template</a>  --><div id="memo_container"><?php echo $details->getAttachment(); ?></div>
		<div id="memo_container"></div>
      </td>
    </tr>      	 
    <tr>
  	   <td class="field_label">Attachment:</td>
  	   <td><input class="text-input" type="file" name="filename" id="filename" /></td>
    </tr>
     <tr>
      <td class="field_label">Date of Offense:</td>
      <td>
        <input class="text-input" type="text" name="date_of_offense" id="date_of_offense" value="<?php echo $details->getDateOfOffense(); ?>" />
        <div id="memo_container"></div>
      </td>
    </tr>
    <tr>
      <td class="field_label">Offense Description:</td>
      <td>
        <textarea name="offense_description"><?php echo $details->getOffenseDescription(); ?></textarea>
        <div id="memo_container"></div>
      </td>
    </tr>
    <tr>
      <td class="field_label">Remarks:</td>
      <td>
        <input class="text-input" type="text" name="remarks" id="remarks" value="<?php echo $details->getRemarks() ?>" />
        <div id="memo_container"></div>
      </td>
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

<div id="view_dialog_wrapper_p"></div>

