<script>
$("#philhealth_form #philhealth_date_from").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#philhealth_form #philhealth_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});		
		load_philhealth_report_total_pages($("#philhealth_form #philhealth_date_from").val(),$("#philhealth_form #philhealth_date_to").val());
    }
});

$("#philhealth_form #philhealth_date_to").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() {
		load_philhealth_report_total_pages($("#philhealth_form #philhealth_date_from").val(),$("#philhealth_form #philhealth_date_to").val());
	}
});

$("#philhealth_form #philhealth_submission_date").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true
});

function checkForm() {
	var date_from = $('#philhealth_form #date_from').val();
	var date_to = $('#philhealth_form #date_to').val();
	if (date_from == '' || date_to == '') {
		return false;	
	} else {
		return true;	
	}
}
</script>
<h2><?php echo $title;?></h2>

<!--<form id="philhealth_form" name="form1" onsubmit="return checkForm()" method="post" action="<?php //echo url($action); ?>">-->
<form id="philhealth_form" name="form1" onsubmit="return checkForm()" method="post" action="<?php echo url('benchmark_bio/philhealth'); ?>">
<div id="form_main" class="employee_form">
	<div id="form_default">
      <table width="100%">
        <tr>
          <td class="field_label">From:</td>
          <td><input class="text-input" type="text" name="philhealth_date_from" id="philhealth_date_from" /></td>
        </tr>
        <tr>
          <td class="field_label">To:</td>
          <td><input class="text-input" type="text" name="philhealth_date_to" id="philhealth_date_to" /></td>
        </tr>
        <tr>
          <td class="field_label">Submission Date:</td>
          <td><input class="text-input" type="text" name="philhealth_submission_date" id="philhealth_submission_date" /></td>
        </tr>    
         <tr>
          <td class="field_label" id="philhealth_print_page_text"></td>
          	
          </td>
          <td><div id="philhealth_total_pages"></div></td>
          
        </tr>
      </table>
	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
    	<table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Download Report" /></td>
          </tr>
        </table>
    </div>
</div><!-- #form_main.employee_form -->
</form>
