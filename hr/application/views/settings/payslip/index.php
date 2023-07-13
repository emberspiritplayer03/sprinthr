<style>
.general-rule-ul li {
	list-style: none;display: block;margin:21px;
}
.general-rule-container {
	background-color: #D2D2D2;padding: 2px;border: 1px solid #aaaaaa;
}
input[type="radio"] {
	margin-top: 0px;
}
</style>
<script type="text/javascript">
	$('#payslip_settings_form').ajaxForm({
		success:function(o) {
			if (o.is_updated == 1) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);				
				$("#message_container").html(o.message);
				$('#message_container').show();
				location.href = base_url + 'settings/payslip'; 
			} else {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);										
				$("#message_container").html(o.message);
				$('#message_container').show();
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Updating...');
			return true;
		}
	});		
</script>
<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br />

<div id="leave_credit_form_wrapper" style="display:none">
	<div id="leaveCreditFormsAjax"></div>
</div>  

<div id="c-structure">
<form action="<?php echo $action_payslip; ?>" method="post"  name="payslip_settings_form" id="payslip_settings_form" >
	<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" >
	<div class="general-rule-container">
		<ul class="general-rule-ul">
			<li>
                  <select style="width:33%" name="template_id" id="template_id">
                    <?php foreach($payslip_template as $template) { ?>
                    <option <?php echo $template->getIsDefault() == G_Payslip_Template::IS_DEFAULT_YES ? 'selected' : ''; ?> value="<?php echo $template->getId();?>"><?php echo $template->getTemplateName();?></option>
                    <?php } ?>
                  </select> 	
			</li>
		</ul>
	</div>

	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:none !important;">
      <tr>
        <td align="left" valign="top" style="border:none !important;"><input type="submit" value="Update" class="curve blue_button pull-left" /></td>
      </tr>
    </table>  

</form>
</div>
