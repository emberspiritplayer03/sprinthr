<form id="change_deduction_amount_form" method="post" action="<?php echo $action;?>">
<input type="hidden" name="employee_id" value="<?php echo $employee_id;?>" />
<input type="hidden" name="from" value="<?php echo $from;?>" />
<input type="hidden" name="to" value="<?php echo $to;?>" />
<input type="hidden" name="label" value="<?php echo $label;?>" />
<input type="hidden" name="variable" value="<?php echo $variable;?>" />
<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label"><?php echo $label;?>: </td>
                <td><input id="deduction_amount" class="text-input" name="amount" type="text" value="<?php echo $amount;?>" /></td>
            </tr>
        </table>  
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input value="Save" id="change_deduction_amount_submit" class="blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeChangeDeductionAmountDialog()">Cancel</a></td>
            </tr>
        </table>
    </div><!-- #form_default.form_action_section -->
</div><!-- #form_main.popup_form -->
</form>