<script>
$("#contribution_form").validationEngine({scroll:false});
$('#contribution_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#contribution_wrapper").html('');

			var hash = window.location.hash;
			loadPage(hash);
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="contribution_form" name="form1" method="post" action="<?php echo url('employee/_update_contribution'); ?>" style="display:none;" >
<input type="hidden" name="id" value="<?php echo $c->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<table width="515" class="small_input" id="hor-minimalist-b"  border="0">
  <thead>
    <tr>
      <th width="30%" scope="col">Contribution/Benefits</th>
      <th width="35%" scope="col">EE</th>
      <th width="35%" scope="col">ER</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td width="30%" align="center">SSS</td>
      <td width="35%"><input name="sss_ee" class="validate[required,custom[number]] text-input" type="text" id="sss_ee" value="<?php echo $c->sss_ee; ?>" size="10" /></td>
      <td width="35%"><input name="sss_er" class="validate[required,custom[number]] text-input"  type="text" id="sss_er" value="<?php echo $c->sss_er; ?>" size="10" /></td>
    </tr>
    <tr>
      <td width="30%" align="center">PHIC</td>
      <td width="35%"><input name="philhealth_ee" class="validate[required,custom[number]] text-input"  type="text" id="philhealth_ee" value="<?php echo $c->philhealth_ee; ?>" size="10" /></td>
      <td width="35%"><input name="philhealth_er" class="validate[required,custom[number]] text-input"  type="text" id="philhealth_er" value="<?php echo $c->philhealth_er; ?>" size="10" /></td>
    </tr>
    <tr>
      <td width="30%" align="center">HDMF</td>
      <td width="35%"><input name="pagibig_ee" class="validate[required,custom[number]] text-input"  type="text" id="pagibig_ee" value="<?php echo $c->pagibig_ee; ?>" size="10" /></td>
      <td width="35%"><input name="pagibig_er" class="validate[required,custom[number]] text-input"  type="text" id="pagibig_er" value="<?php echo $c->pagibig_er; ?>" size="10" /></td>
    </tr>
    <tr class="form_action_section">
      <td width="30%">&nbsp;</td>
      <td class="action_section" colspan="2"><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadContributionTable();">Cancel</a></td>
      </tr>
  </tbody>
</table>
</form>
