<script>
$(function() {
$("#date_created").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#examination_details_form").validationEngine({scroll:false});
$('#examination_details_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#examination_details_edit_form_wrapper").hide();
			$("#examination_details_table_wrapper").show();
			loadExaminationDetailsSettings(<?php echo $details->id ?>);
			
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

});
</script>
<form id="examination_details_form" name="form1" method="post" action="<?php echo url('settings/_update_examination_details'); ?>" style="display:none">
<input type="hidden" name="examination_id" value="<?php echo Utilities::encrypt($details->id); ?>" />
<input type="hidden" name="company_structure_id" value="<?php echo $company_structure_id; ?>" />

  <table class="table_form" width="686" border="0" cellpadding="3" cellspacing="3">
    <tr>
      <td align="right" valign="top">Title:</td>
      <td valign="top"><input class="validate[required]" type="text" name="title" id="title" value="<?php echo $details->title; ?>"  /></td>
    </tr>
    <tr>
      <td align="right" valign="top">Description</td>
      <td valign="top"><input type="text" name="description" id="description" value="<?php echo $details->description; ?>"  /></td>
    </tr>
    <tr>
      <td align="right" valign="top">Passing Percentage</td>
      <td valign="top"><input type="text" class="validate[required,custom[integer]]" name="passing_percentage" id="passing_percentage" value="<?php echo $details->passing_percentage; ?>"  /></td>
    </tr>
    <tr>
      <td align="right" valign="top">Created by:</td>
      <td valign="top"><input type="text" class="validate[required]" name="created_by" id="created_by" value="<?php echo $details->created_by; ?>"  /></td>
    </tr>
    <tr>
      <td align="right" valign="top">Date Created:</td>
      <td valign="top"><input type="text" class="validate[required]" name="date_created" id="date_created" value="<?php echo $details->date_created; ?>"  /></td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td width="196" align="left" valign="top"><a href="#">delete examination</a></td>
      <td width="469" valign="top"><input name="button" type="submit" id="button" value="Update" /> 
        <a href="javascript:void(0);" onclick="javascript:loadExaminationDetailsTable();">Cancel</a></td>
    </tr>
  </table>
</form>
