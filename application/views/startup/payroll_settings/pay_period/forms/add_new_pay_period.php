<div id="form_main" class="inner_form popup_form wider">
	<form name="addPayPeriod" id="addPayPeriod" method="post" action="<?php echo $action_pay_period; ?>" >   
    <div id="form_default"> 
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Pay Period Code:</td>
            <td>
                <input type="text" value="" name="pay_period_code" class="validate[required] text" id="pay_period_code" />    
            </td>
        </tr>  
        <tr>
            <td class="field_label">Pay Period Name:</td>
            <td>
                <input type="text" value="" name="pay_period_name" class="validate[required] text" id="pay_period_name" />    
            </td>
        </tr>
        <tr>
          <td class="field_label">Cut Off:</td>
          <td><input type="text" value="" name="cut_off" class="validate[required] text" id="cut_off" /></td>
        </tr>
        <tr>
          <td class="field_label">Is Default:</td>
          <td>
          	<select name="is_default" id="is_default" class="select_option_sched">
            	<option value="1">Yes</option>
                <option value="0">No</option>
            </select>          	
          </td>
        </tr>    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">&nbsp;</td>
            <td><input type="submit" class="blue_button" value="Save" />&nbsp;<a href="#" onclick="javascript:closeDialog('#_dialog-box_','#addPayPeriod');">Cancel</a></td>
        </tr>          
    </table>
    </div>
    </form>
</div>