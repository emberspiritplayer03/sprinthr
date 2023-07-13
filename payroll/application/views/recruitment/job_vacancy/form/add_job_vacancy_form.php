<script>
$(function() {
	$("#job_vacancy_form").validationEngine({scroll:false});
	$("#publication_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
	$("#advertisement_end").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

})

$('#job_vacancy_form').ajaxForm({
	success:function(o) {
		if(o==1){
			 dialogOkBox('Successfully Added',{});
			 load_job_vacancy_dt();
			 
			$.post(base_url+'recruitment/_load_add_job_vacancy_form',{},function(o){
				 $("#add_job_vacancy_form_wrapper").html(o);
			});
			$("#add_job_vacancy_form_wrapper").hide();
			
		}else {
			 dialogOkBox('Invalid Input.',{})
		}
		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});


</script>

<div id="formcontainer">
<div class="mtshad"></div>
<form id="job_vacancy_form"  action="<?php echo url('recruitment/_add_job_vacancy'); ?>" method="post"  name="job_vacancy_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<input type="hidden" id="hiring_manager_id" name="hiring_manager_id" value="<?php echo $hiring_manager_id; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add Job Vacancy</h3>
<div id="form_main">
	<h3 class="section_title"><span>Job Vacancy</span> Vacancy Form</h3>
    <div id="form_default">
      <table width="100%" border="0" cellpadding="3" cellspacing="0">
        <tr>
          <td class="field_label">Job:</td>
          <td><select class="validate[required]" name="job_id" id="job_id">
            <option value="">- select job - </option>
            <?php foreach ($positions as $key=>$value) { ?>
            <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td class="field_label">Hiring Manager:</td>
          <td><input type="text" value="" name="hiring_manager_name" class="validate[required] text-input text" id="hiring_manager_name" /></td>
        </tr>
        <tr>
          <td class="field_label">Publication Date:</td>
          <td><input type="text" value="" name="publication_date" class="validate[required] text-input text" id="publication_date" /></td>
        </tr>
        <tr>
          <td class="field_label">Advertisement End:</td>
          <td><input type="text" value="" name="advertisement_end" class="validate[required] text-input text" id="advertisement_end" /></td>
        </tr>
      </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input class="blue_button curve" type="submit" value="Add" /> <a href="javascript:void(0)" onclick="javascript:hide_add_job_vacancy();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div><!-- #formwrap -->

</form>
</div>

<script>
$(function() {

	$("#hiring_manager_name").autocomplete({
		source:  base_url + 'recruitment/_autocomplete_load_employee_name',
		select: function( event, ui ) {
					$( "#hiring_manager_name" ).val( ui.item.label );
					$( "#hiring_manager_id" ).val( ui.item.id );
					return false;
				}
	});
});
</script>

