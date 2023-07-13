<div id="form_main" class="inner_form popup_form wider">
	<form name="editPayPeriod" id="editPayPeriod" method="post" action="<?php echo $action_pay_period; ?>">   
     <input type="hidden" value="<?php echo $pp->getId() ?>" id="pay_period_id" name="pay_period_id" />   
    <div id="form_default"> 
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Pay Period Code:</td>
            <td>
                <input type="text" value="<?php echo $pp->getPayPeriodCode(); ?>" name="pay_period_code" class="validate[required] text" id="pay_period_code" />    
            </td>
        </tr>  
        <tr>
            <td class="field_label">Pay Period Name:</td>
            <td>
                <input type="text" value="<?php echo $pp->getPayPeriodName(); ?>" name="pay_period_name" class="validate[required] text" id="pay_period_name" />    
            </td>
        </tr>
        <tr>
          <td class="field_label">Cut Off:</td>
          <td><input type="text" value="<?php echo $pp->getCutOff(); ?>" name="cut_off" class="validate[required] text" id="cut_off" /></td>
        </tr>
        <tr>
          <td class="field_label">Is Default:</td>
          <td>
          	<select id="is_default" name="is_default" class="select_option_sched">
            	<option <?php echo($pp->getIsDefault() == 1 ? 'selected="selected"' : ''); ?> value="1">Yes</option>
                <option <?php echo($pp->getIsDefault() == 0 ? 'selected="selected"' : ''); ?> value="0">No</option>
            </select>	          	
          </td>
        </tr>    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">&nbsp;</td>
            <td><input type="submit" class="blue_button" value="Save" />&nbsp;<a href="#" onclick="javascript:closeDialog('#_dialog-box_','#editPayPeriod');">Cancel</a></td>
        </tr>          
    </table>
    </div>
    </form>
</div>