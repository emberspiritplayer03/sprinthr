<script type="text/javascript">
$(function() {
		$("#date_created").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
			changeYear: true});


$("#examination_form").validationEngine({scroll:false});
$('#examination_form').ajaxForm({
	success:function(o) {
		load_add_examination_confirmation(o); 
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

});
</script>
<div class="formwrap inner_form">
<form action="<?php echo url('settings/_insert_examination'); ?>" method="post"  name="examination_form" id="examination_form" >
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<h3 class="form_sectiontitle"><span>Add Examination</span></h3>
<div id="form_main">
    <div id="form_default">
        <h3 class="section_title">Examination Detail</h3>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" class="field_title">Exam Title:</td>
            <td align="left" valign="top"><input name="title" type="text" class="validate[required] text-input text" id="title" value="" /> 
              <small><em>(HR Examination, Production Exam)</em></small></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Description:</td>
            <td align="left" valign="top"><textarea name="description" id="description" cols="45" rows="5"></textarea></td>
          </tr>    
          <tr>
            <td align="left" valign="top" class="field_title">Passing:</td>
            <td align="left" valign="top"><input type="text" value="" name="passing_percentage" class="validate[required,custom[integer]] text-input text" id="passing_percentage" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Date Created:</td>
            <td align="left" valign="top"><input type="text" value="" name="date_created" class="validate[required] text-input text" id="date_created" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_title">Created by:</td>
            <td align="left" valign="top"><input type="text" value="" name="created_by" class="validate[required] text-input text" id="created_by" /></td>
          </tr>
        </table>    
    </div>
   
    <div id="form_default" class="form_action_section">
        <input type="submit" value="Add Examination" class="curve blue_button" />
        <a href="javascript:cancel_add_examination_form();">Cancel</a>
    </div>
</div>
</form>
</div>



</script>