<script>
$("#sss_form #date_from").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		load_total_max_records_page();
    }
});
$("#sss_form #date_to").datepicker({
	dateFormat:'yy-mm-dd',
	changeMonth:true,
	changeYear:true,
	showOtherMonths:true,
	onSelect	:function() { 
		load_total_max_records_page();
    }
});
$("#sss_form #submission_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
function checkForm() {
	var date_from = $('#sss_form #date_from').val();
	var date_to = $('#sss_form #date_to').val();
	var submission_date = $('#sss_form #submission_date').val();
	var page_number = $('#sss_form #page_number').val();
	if (date_from == '' || date_to == '' || submission_date == '' || page_number == '') {
		return false;	
	} else {
			
	}
}

function load_total_max_records_page() {
	var date_from = $('#date_from').val();
	var date_to   = $('#date_to').val();
	
	if(date_from != "" && date_to != "") {
		$('#print_records_per_page_wrapper').show();
		$('#print_page_label').show();
		$('#print_records_per_page_wrapper').html("<span style='font-size:11px;'>Calculating total number of pages...</span>");
		$.post(base_url + 'reports/_load_total_max_records_page',{date_from:date_from,date_to:date_to},function(o) {
			$('#print_records_per_page_wrapper').html(o);
		});
	}
}
</script>
<h2><?php echo $title;?></h2>

<form id="sss_form" name="form1" onsubmit="return checkForm()" method="post" action="<?php echo url($action); ?>">
<div id="form_main" class="employee_form">
	<div id="form_default">
      <table width="100%">
        <tr>
          <td class="field_label">From:</td>
          <td><input class="text-input" type="text" name="date_from" id="date_from" onchange="alert(1);" /></td>
        </tr>
        <tr>
          <td class="field_label">To:</td>
          <td><input class="text-input" type="text" name="date_to" id="date_to" /></td>
        </tr>
        <tr>
          <td class="field_label">Submission Date:</td>
          <td><input class="text-input" type="text" name="submission_date" id="submission_date" /></td>
        </tr>
        <tr>
          <td class="field_label"><span id="print_page_label" style="display:none;">Print Page:</span></td>
          <td>
          	 <div id="print_records_per_page_wrapper" style="display:none;">
          		<input type="text" value="Please specify date range" disabled="disabled" />
             </div>
         </td>
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
