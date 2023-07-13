<script type="text/javascript">
$(function() {
		$("#date_created").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,
			changeYear: true});


$("#performance_form").validationEngine({scroll:false});
$('#performance_form').ajaxForm({
	success:function(o) {
		load_add_performance_confirmation(o); 
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

});
</script>

<div class="formwrap inner_form">
<form action="<?php echo url('settings/_insert_performance'); ?>" method="post"  name="performance_form" id="performance_form" >
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<h3 class="form_sectiontitle"><span>Add Performance</span></h3>
<div id="form_main" class="employee_form_summary">
    <div id="form_default">
        <h3 class="section_title">Performance Detail</h3>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" class="field_label">Title:</td>
            <td align="left" valign="top"><input type="text" value="" name="title" class="validate[required] text-input text" id="title" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Job:</td>
            <td align="left" valign="top"><select name="job_id" class="validate[required] text-input text select_option" id="job_id">
              <option value="">- Select Job - </option>
              <?php foreach($job as $key=>$value) { ?>
              <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
              <?php } ?>
            </select> 
              <small><em>(HR Examination, Production Exam)</em></small></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Description:</td>
            <td align="left" valign="top"><textarea name="description" id="description" cols="45" rows="5"></textarea></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Date Created:</td>
            <td align="left" valign="top"><input type="text" value="" name="date_created" class="validate[required] text-input text" id="date_created" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Created by:</td>
            <td align="left" valign="top"><input type="text" value="" name="created_by" class="validate[required] text-input text" id="created_by" /></td>
          </tr>
        </table>    
    </div>
   
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" class="field_label">&nbsp;</td>
            <td align="left" valign="top"><input type="submit" value="Add Performance" class="curve blue_button" />&nbsp;<a href="javascript:cancel_add_performance_form();">Cancel</a></td>
          </tr>
        </table>  
    </div>
</div>
</form>
</div>



</script>