<script>
$("#contribution_form #contribution_date_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#contribution_form #contribution_date_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#contribution_form #contribution_payout_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
function checkForm() {
	var date_from = $('#contribution_form #date_from').val();
	var date_to = $('#contribution_form #date_to').val();
	var payout_date = $('#contribution_form #payout_date').val();
	if (date_from == '' || date_to == '' || payout_date == '') {
		return false;	
	} else {
		return true;	
	}
}
</script>
<h2><?php echo $title;?></h2>
<form id="contribution_form" name="form1" onsubmit="return checkForm()" method="post" action="<?php echo url($action); ?>">
<div id="form_main" class="employee_form">
	<div id="form_default">
      <table width="100%">
        <tr>
          <td class="field_label">From:</td>
          <td><input class="text-input" type="text" name="date_from" id="contribution_date_from" /></td>
        </tr>
        <tr>
          <td class="field_label">To:</td>
          <td><input class="text-input" type="text" name="date_to" id="contribution_date_to" /></td>
        </tr>
        <tr>
          <td class="field_label">Payout Date:</td>
          <td><input class="text-input" type="text" name="payout_date" id="contribution_payout_date" /></td>
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
