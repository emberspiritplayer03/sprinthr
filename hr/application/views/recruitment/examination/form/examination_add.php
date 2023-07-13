<script>
$(document).ready(function() {
$("#schedule_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true,minDate: 0, maxDate: '+1M +10D'});

	$("#applicant_examination_add_form").validationEngine({scroll:false});

	$('#applicant_examination_add_form').ajaxForm({
		success:function(o) {
				if(o==1) {
					dialogOkBox('Successfully Added',{});	
					load_applicant_examination_datatable('today');
					//window.location = "examination?add=show&rid="+applicant_id+"&hash="+hash;
					window.location = "examination";
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

<div id="formcontainer">
<div class="mtshad"></div>
<form id="applicant_examination_add_form"  action="<?php echo url('recruitment/_insert_applicant_examination'); ?>" method="post"  name="applicant_examination_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add Examination</h3>
<div id="form_main">
    <div id="form_default">
      <h3 class="section_title">Applicant Information</h3>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" class="field_label">Applicant:</td>
          <td align="left" valign="top">

            <input type="text" class="" name="applicant_id" id="applicant_id" />
    
         </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Examination:</td>
          <td align="left" valign="top">
            
            <select class="validate[required] select_option" name="title" id="title" onchange="javascript:loadExaminationDetails(this.value);">
              <option value="">-- Select Examination --</option>
               <?php foreach($examinations as $key=>$value) { ?>
              	<option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
              <?php } ?>
      			<option value="add_new">Add New Template...</option>
              </select>
            
            </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Description:</td>
          <td align="left" valign="top">
          <div id="position_dropdown_wrapper">
            <input name="description" type="text" class="text-input" id="description" readonly="readonly" />
          </div>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Passing Percentage:</td>
          <td align="left" valign="top">
          <div id="status_dropdown_wrapper">
          	<div class="input-append">
            <input type="text" class="validate[required,custom[integer] input-mini" name="passing_percentage" id="passing_percentage" />
            <span class="add-on" style="height:17px;">%</span>
            </div>
          </div>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Date Schedule:</td>
          <td align="left" valign="top"><input type="text" class="validate[required] text-input" name="schedule_date" id="schedule_date" /></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Scheduled By:</td>
          <td align="left" valign="top"><input type="text" class="validate[required] text-input" name="scheduled_by" id="scheduled_by" /></td>
        </tr>
      </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                    <input type="submit" value="Add New Schedule" class="curve blue_button" />
                    <a href="javascript:void(0)" onclick="javascript:cancel_add_applicant_examination_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div><!-- #formwrap -->

</form>
</div>
<?php 

$applicant_id = Utilities::decrypt($_GET['rid']);
$a = G_Applicant_Finder::findById($applicant_id);
?>

<script>

var t = new $.TextboxList('#applicant_id', {plugins: {
	autocomplete: {
		minLength: 3,
		onlyFromValues: true,
		queryRemote: true,
		remote: {url: base_url + 'recruitment/_autocomplete_load_applicant_name'}
	
	}
}});

$('#scheduled_by').textboxlist({unique: true,max:1, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'recruitment/_autocomplete_load_scheduled_by'}
}}});
</script>
<?php if($a) { ?>
<script>
t.add('Entry',<?php echo $a->id ?>, '<?php echo $a->lastname. ', '. $a->firstname; ?>');
</script>
<?php 
}?>
