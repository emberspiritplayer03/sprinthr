<form id="add_deduction_form" method="post" action="<?php echo $action;?>">
<input type="hidden" name="employee_id" value="<?php echo $employee_id;?>" />
<input type="hidden" name="from" value="<?php echo $from;?>" />
<input type="hidden" name="to" value="<?php echo $to;?>" />
<div id="form_main" class="inner_form popup_form">
<div id="form_default">
    <table width="100%">
    <!--    <tr>
      <td width="51">Type</td>
      <td width="246"><select onchange="onDeductionTypeChange(this.value)" name="deduction_type" id="deduction_type">
        <option value="2" selected="selected">Advance</option>
        <option value="3">Loan</option>
        <option value="4">Tax</option>
      </select>
      </td>
    </tr>-->
    <tr>
      <td width="51" class="field_label">Name:</td>
      <td><div id="name-handler"></div></td>
    </tr>
    <tr>
      <td class="field_label">Amount:</td>
      <td><input class="validate[required,custom[number]] text-input" id="deduction_amount" name="amount" type="text" /></td>
    </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
    	<table>
        	<tr>
              <td class="field_label">&nbsp;</td>
              <td><input value="Save" id="add_earning_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeAddEarningDialog()">Cancel</a></td>
            </tr>
        </table>
    </div><!-- #form_default.form_action_section -->
</div><!-- #form_main.popup_form -->
</form>

<div id="text-name" style="display:none">
<select name="label" id="deduction_name">
        <option value="Healthcard" selected="selected">Healthcard</option>
		<option value="PHIC - Advances">PHIC - Advances</option>
		<option value="HDMF - Advance">HDMF - Advances</option>
		<option value="SSS - Advances">SSS - Advances</option>		
		<option value="Salary Loan">Salary Loan</option>
		<option value="Gauvent">Gauvent</option>
		<option value="Other Deductions">Others</option>
      </select>
</div>

<div id="default-name" style="display:none">
		<select name="label" id="deduction_name">
        <option value="ID Card" selected="selected">ID Card</option>
		<option value="Uniform">Uniform</option>
		<option value="Medical">Medical</option>
		<option value="Cash Bond">Cash Bond</option>		
		<option value="Cash Advance">Cash Advance</option>
		<option value="Items/Goods">Items/Goods</option>
		<option value="Placement Fee">Placement Fee</option>
        <option value="SSS">SSS</option>
        <option value="Philhealth">Philhealth</option>
        <option value="Pagibig">Pagibig</option>
        <option value="Other Deductions">Others</option>
      	</select>  
</div>



<div id="option-name" style="display:none">
<select name="label" id="deduction_name">
        <option value="SS Loan" selected="selected">SS Loan</option>
		<option value="HDMF Loan">HDMF Loan</option>
      </select>
</div>

<div id="option-tax-name" style="display:none">
<select name="label" id="deduction_name">
        <option value="Withheld Tax" selected="selected">Withheld Tax</option>
      </select>
</div>

<script>
var default_value = $('#default-name').html();
$('#name-handler').html(default_value);

function onDeductionTypeChange(value) {
	var loan = 3;
	var tax = 4;
	if (value == loan) {
		var option = $('#option-name').html();	
	} else if (value == tax) {
		var option = $('#option-tax-name').html();
	} else {
		var option = $('#text-name').html();
	}
	
	$('#name-handler').html(option);
}
</script>