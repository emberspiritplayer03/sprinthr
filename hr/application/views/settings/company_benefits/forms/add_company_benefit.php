<div id="form_main" class="inner_form popup_form">
	<form name="addCompanyBenefit" id="addCompanyBenefit" method="post" action="<?php echo url('settings/_insert_company_benefit'); ?>">   
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Benefit Name:</td>
            <td >
                <input type="text" value="" name="benefit_name" class="validate[required] text" id="benefit_name" style="width:90%;" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Benefit Code:</td>
            <td >
                <input type="text" value="" name="benefit_code" class="validate[required] text" id="benefit_code" style="width:90%;" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Amount:</td>
            <td >
            	<div class="input-append">
                	<input type="text" value="" name="benefit_amount" class="validate[required,custom[money]] text-input" id="benefit_amount" style="width:20%;position:static;" />    
                	<span class="add-on" style="height:15px;">Php</span>
                </div>
                
            </td>
        </tr>
        <tr>
            <td class="field_label">Description:</td>
            <td >
            	<textarea id="benefit_description" name="benefit_description" style="height:100px;width:90%;min-width:333px;"></textarea>                
            </td>
        </tr>
        <tr>
            <td class="field_label">Type:</td>
            <td >
                <select id="benefit_type" name="benefit_type" style="width:30%">
                	<option value="<?php echo G_Settings_Company_Benefits::EARNING; ?>"><?php echo G_Settings_Company_Benefits::EARNING; ?></option>
                    <option value="<?php echo G_Settings_Company_Benefits::DEDUCTION; ?>"><?php echo G_Settings_Company_Benefits::DEDUCTION; ?></option>
                </select> 
            </td>
        </tr>
        <tr>
            <td class="field_label">Is Taxable:</td>
            <td >
                <select id="is_taxable" name="is_taxable" style="width:30%">
                	<option value="<?php echo G_Settings_Company_Benefits::YES; ?>"><?php echo G_Settings_Company_Benefits::YES; ?></option>
                    <option value="<?php echo G_Settings_Company_Benefits::NO; ?>"><?php echo G_Settings_Company_Benefits::NO; ?></option>
                </select>
            </td>
        </tr>
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addCompanBenefit');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>
