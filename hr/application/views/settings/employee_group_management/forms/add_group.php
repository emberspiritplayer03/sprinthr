<form id="add_group_form" name="add_group_form" autocomplete="off" method="POST" action="<?php echo url('settings/_load_insert_new_group'); ?>">
<input type="hidden" id="company_structure_id_add" name="company_structure_id_add" class="company_structure_wrapper" value="<?php echo $h_company_structure_id; ?>" />
<input type="hidden" id="token_add" class="token_wrapper" name="token_add" />

<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:25%" align="left" valign="middle">Name:</td>
            <td style="width:75%" align="left" valign="middle">
                <input type="text" style="width:250px;" id="group_name" name="group_name" class="validate[required]" value="" />
            </td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeDialogBox('#add_group_form_modal_wrapper','#add_group_form')">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>
