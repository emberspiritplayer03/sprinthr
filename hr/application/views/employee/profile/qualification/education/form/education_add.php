<script>
$("#start_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#end_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

$("#education_add_form").validationEngine({scroll:false});
$('#education_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#education_wrapper").html('');
			loadPage("#education");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="education_add_form" name="form1" method="post" action="<?php echo url('employee/_update_education'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
  	   <td class="field_label">Institution / School:</td>
  	   <td><input type="text" class="validate[required] text-input" name="institute" id="institute" value="<?php echo $details->institute; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Course:</td>
  	   <td><input class="text-input" type="text" name="course" id="training_from" value="<?php echo $details->course; ?>" /></td>
    </tr>
  	<!--  <tr>
  	   <td class="field_label">Year:</td>
  	   <td><input class="text-input" type="text" name="year" id="training_to" value="<?php //echo  ucfirst($details->year); ?>" /></td>
    </tr> -->
  	 <tr>
  	   <td class="field_label">Date Attended:</td>
  	   <td><input type="text" class="validate[required] text-input" name="start_date" id="start_date" value="<?php echo $details->start_date; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Date Graduated:</td>
  	   <td><input type="text" class="validate[required] text-input" name="end_date" id="end_date" value="<?php echo $details->end_date; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">GPA Score:</td>
  	   <td><input type="text" class="validate[required] text-input" name="gpa_score" id="gpa_score" value="<?php echo $details->gpa_score; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Attainment:</td>
       <td><select class="select_option" name="attainment" id="attainment">
         <option value="vocational">Vocational</option>
         <option value="graduate">Graduate</option>
         <option value="undergraduate">Undergraduate</option>
       </select></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadEducationTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
