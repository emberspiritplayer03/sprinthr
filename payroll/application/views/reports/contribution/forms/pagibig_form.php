<script>
$("#pagibig_form #pagibig_date_from").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		$("#pagibig_form #pagibig_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
		load_report_total_pages($("#pagibig_form #pagibig_date_from").val(),$("#pagibig_form #pagibig_date_to").val());
    }
});
$("#pagibig_form #pagibig_date_to").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() {
		load_report_total_pages($("#pagibig_form #pagibig_date_from").val(),$("#pagibig_form #pagibig_date_to").val());
	}
});
$("#pagibig_form #pagibig_submission_date").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true
});
function checkForm() {
	var date_from = $('#pagibig_form #pagibig_date_from').val();
	var date_to = $('#pagibig_form #pagibig_date_to').val();
	var submission_date = $('#pagibig_form #pagibig_submission_date').val();
	var total_pages = $("#limit_start").val();
	if (date_from == '' || date_to == '' || submission_date == '' || total_pages == 0) {
		return false;	
	} else {
		return true;	
	}
}
</script>
<h2><?php echo $title;?></h2>
<!--<form id="pagibig_form" name="form1" onsubmit="return checkForm()" method="post" action="<?php //echo url($action); ?>">-->
<form id="pagibig_form" name="form1" onsubmit="return checkForm()" method="post" action="<?php echo url('benchmark_bio/pagibig'); ?>">
<div id="form_main" class="employee_form">
	<div id="form_default">
      <table width="100%">
        <tr>
          <td class="field_label">From:</td>
          <td><input class="text-input" type="text" name="pagibig_date_from" id="pagibig_date_from" /></td>
        </tr>
        <tr>
          <td class="field_label">To:</td>
          <td><input class="text-input" type="text" name="pagibig_date_to" id="pagibig_date_to" /></td>
        </tr>
        <tr>
          <td class="field_label">Submission Date:</td>
          <td><input class="text-input" type="text" name="pagibig_submission_date" id="pagibig_submission_date" /></td>
        </tr>        
        <tr>
          <td class="field_label" id="print_page_text"></td>
          	
          </td>
          <td><div id="pagibig_total_pages"></div></td>
          
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

