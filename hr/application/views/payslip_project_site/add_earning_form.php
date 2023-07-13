<form id="add_earning_form" method="post" action="<?php echo $action;?>">
<input type="hidden" name="employee_id" value="<?php echo $employee_id;?>" />
<input type="hidden" name="from" value="<?php echo $from;?>" />
<input type="hidden" name="to" value="<?php echo $to;?>" />
<div id="form_main" class="inner_form popup_form">
<div id="form_default">
    <table width="100%">
    <!--<tr>
      <td width="51">Type</td>
      <td width="246">
     <select onchange="onEarningTypeChange(this.value)" name="earning_type" id="earning_type">
        <option value="3">Allowance</option>
      </select>      
      <select onchange="onEarningTypeChange(this.value)" name="earning_type" id="earning_type">
        <option value="2" selected="selected">Adjustment</option>
        <option value="3">Allowance</option>
        <option value="4">Bonus</option>
        <option value="5">Advance</option>
        <option value="6">Lines</option>
      </select>
      </td>
    </tr>-->
    <tr>
      <td class="field_label">Name:</td>
      <td><div id="name-handler"><input id="earning_name" name="label" type="text" /></div></td>
    </tr>
    <tr>
      <td class="field_label">Amount:</td>
      <td><input class="validate[required,custom[number]] text-input" id="earning_amount" name="amount" type="text" /></td>
    </tr>
    </table>
	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
    	<table>
        	<tr>
              <td class="field_label">&nbsp;</td>
              <td><input value="Save" id="add_earning_submit" class="blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeAddEarningDialog()">Cancel</a></td>
            </tr>
        </table>
    </div><!-- #form_default.form_action_section -->
</div><!-- #form_main.popup_form -->
</form>

<div id="default-value" style="display:none">
	<select name="label" id="earning_name">
    	<option value="Meal Allowance" selected="selected">Meal Allowance</option>
        <option value="Accounts Receivable">Accounts Receivable</option>
        <option value="Others">Others</option>
	</select>
</div>

<div id="excess-lines" style="display:none">
<select name="label" id="earning_name">
        <option value="Excess Lines" selected="selected">Excess Lines</option>
      </select>
</div>

<div id="default-name" style="display:none">
<input id="earning_name" name="label" type="text" />
</div>

<div id="allowance" style="display:none">
<select name="label" id="earning_name">
        <option value="Meal" selected="selected">Meal</option>
      </select>
</div>

<script>
var default_value = $('#default-value').html();
$('#name-handler').html(default_value);

function onEarningTypeChange(value) {
	var excess_lines = 6;
	var allowance = 3;
	if (value == excess_lines) {
		var option = $('#excess-lines').html();	
	} else if (value == allowance) {
		var option = $('#allowance').html();
	} else {
		var option = $('#default-name').html();
	}	
	$('#name-handler').html(option);
}
</script>