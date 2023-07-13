<div id="form_main" class="inner_form popup_form">
	<form name="addPayrollPeriod" id="addPayrollPeriod" method="post" action="<?php echo url('settings/_insert_payroll_period'); ?>">   
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Select Year : </td>
            <td > 
                <select style="width:216px;" id="payroll_year" name="payroll_year" onchange="javascript:load_payroll_period_list_selected_year(this.value);">
                	<?php for($x=$current_year;$x >= $start_year; $x--){ ?>
                    	<option <?php echo($x == $selected_year ? 'selected="selected"' : ""); ?> value="<?php echo $x; ?>"><?php echo $x; ?></option>
                    <?php } ?>
                </select>               
            </td>
        </tr>
        <tr>
            <td class="field_label">Cutoff Period : </td>
            <td id="payroll_period_selected_year"></td>
        </tr>
        <tr>
            <td class="field_label">Is Lock : </td>
            <td >
                <select style="width:216px;" id="is_lock" name="is_lock">
                	<option selected="selected" value="<?php echo G_Cutoff_Period::NO; ?>"><?php echo G_Cutoff_Period::NO; ?></option>
                    <option value="<?php echo G_Cutoff_Period::YES; ?>"><?php echo G_Cutoff_Period::YES; ?></option>
                    
                </select>               
            </td>
        </tr>
        <tr>
        	<td colspan="2"><br />
<span><small style="font-size:11px;color:#F00;">Note : Generating all will reset all existing cutoff for the selected year.</small></span></td>
        </tr>
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_payroll_period_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addPayrollPeriod');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>
<script>
load_payroll_period_list_selected_year($("#payroll_year").val());
</script>
