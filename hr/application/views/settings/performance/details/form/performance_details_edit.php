<script>
$(function() {
$("#date_created").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#performance_details_form").validationEngine({scroll:false});
$('#performance_details_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#performance_details_edit_form_wrapper").hide();
			$("#performance_details_table_wrapper").show();
			loadPerformanceDetailsSettings(<?php echo $details->id ?>);
			
			
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
<div id="performance_details_form" style="display:none;">
<h2>Performance Template</h2>
<div class="sectionarea">
<div id="form_main" class="employee_form">    
<form name="form1" method="post" action="<?php echo url('settings/_update_performance_details'); ?>">
<input type="hidden" name="performance_id" value="<?php echo Utilities::encrypt($details->id); ?>" />
<input type="hidden" name="company_structure_id" value="<?php echo $company_structure_id; ?>" />
	<div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Title</td>
                <td><input type="text" class="text-input" name="title" id="title" value="<?php echo $details->title; ?>"  /></td>
            </tr>
            <tr>
                <td class="field_label">Job:</td>
                <td><select  name="job_id" class="validate[required] text-input text select_option" id="job_id">
                <option value="">- Select Job - </option>
                <?php 
                foreach($job as $key=>$value) { 
                if($value->id==$details->job_id) {
                $selected = "selected='selected'";
                }else {
                $selected = "";
                }
                ?>
                
                <option <?php echo $selected; ?> value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
                <?php } ?>
                </select></td>
            </tr>
            <tr>
                <td class="field_label">Description</td>
                <td><input type="text" class="text-input" name="description" id="description" value="<?php echo $details->description; ?>"  /></td>
            </tr>
            <tr>
                <td class="field_label">Created by:</td>
                <td><input type="text" class="validate[required] text-input" name="created_by" id="created_by" value="<?php echo $details->created_by; ?>"  /></td>
            </tr>
            <tr>
                <td class="field_label">Date Created:</td>
                <td><input type="text" class="validate[required] text-input" name="date_created" id="date_created" value="<?php echo $details->date_created; ?>"  /></td>
            </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table>
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input class="blue_button" name="button" type="submit" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadPerformanceDetailsTable();">Cancel</a></td>
            </tr>
        </table>
    </div>
</form>
</div>
</div>
</div>
