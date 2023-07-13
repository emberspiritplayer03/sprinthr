<script>
$(document).ready(function() {
	$("#edit_publication_date").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
			$("#advertisement_end").datepicker('option',{minDate:$(this).datepicker('getDate')});
		}		
	});
	$("#edit_advertisement_end").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true
	});

	$("#hiring_manager_name_edit").autocomplete({
		source:  base_url + 'recruitment/_autocomplete_load_employee_name',
		select: function( event, ui ) {
					$( "#hiring_manager_name_edit" ).val( ui.item.label );
					$( "#hiring_manager_id_edit" ).val( ui.item.id );
					return false;
				}
	});	
	
});

</script>


<div id="form_main" class="inner_form popup_form wider">
<form id="edit_job_vacancy_form"  action="<?php echo url('recruitment/_update_job_vacancy'); ?>" method="post"  name="edit_job_vacancy_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="eid" name ="eid" value="<?php echo Utilities::encrypt($jv->getId()); ?>" />
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<input type="hidden" id="hiring_manager_id_edit" name="hiring_manager_id" value="<?php echo $jv->getHiringManagerId(); ?>" />
    <div id="form_default">      
        <table width="100%" border="0" cellpadding="3" cellspacing="0">
        <tr>
          <td class="field_label">Job:</td>
          <td><select class="validate[required]" name="job_id" id="job_id">
            <option value="">- select job - </option>
            <?php foreach ($positions as $key=>$value) { ?>
            <option <?php echo ($jv->getJobId() == $value->id ? 'selected="selected"' : ''); ?> value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td class="field_label">Description:</td>
          <td><textarea name="job_description" class="validate[required] text" id="job_description" style="height:160px;"><?php echo $jv->getJobDescription(); ?></textarea></td>
        </tr>
        <tr>
          <td class="field_label">Hiring Manager:</td>
          <td><input type="text" value="<?php echo $jv->getHiringManagerName(); ?>" name="hiring_manager_name" class="validate[required] text-input text" id="hiring_manager_name_edit" /></td>
        </tr>
        <tr>
          <td class="field_label">Publication Date:</td>
          <td><input type="text" value="<?php echo $jv->getPublicationDate(); ?>" name="publication_date" class="validate[required] text-input text" id="edit_publication_date" /></td>
        </tr>
        <tr>
          <td class="field_label">Advertisement End:</td>
          <td><input type="text" value="<?php echo $jv->getAdvertisementEnd(); ?>" name="advertisement_end" class="validate[required] text-input text" id="edit_advertisement_end" /></td>
        </tr>
      </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_loan');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</form>
</div><!-- #form_main -->
