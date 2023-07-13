<script>
$("#date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#performance_edit_form").validationEngine({scroll:false});
$('#performance_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#performance_wrapper").html('');
			loadPage("#performance");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>

<form id="performance_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_performance'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td width="156" align="right" valign="top">Employee:</td>
      <td valign="top"><?php echo $employee_name; ?></td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Reviewer:</td>
      <td valign="top"><?php echo $reviewer_name; ?></td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Review Period </td>
      <td width="241" valign="top"><?php echo $details->period_from; ?> - <?php echo $details->period_to; ?></td>
      <td width="241" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top">Due Date:</td>
      <td valign="top"><?php echo $details->due_date; ?></td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>

      <td align="right" valign="top">Status:</td>
      <td valign="top"><?php echo $details->status; ?></td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top">Created By</td>
      <td valign="top"><?php echo $created_by; ?></td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="left" valign="top"><table width="100%" border="1">
        <tr>
          <td width="36%">Factor</td>
          <td width="7%">Min</td>
          <td width="6%">Max</td>
          <td width="8%">Rating</td>
          <td width="43%">Reviewer Comment</td>
        </tr>
        <?php foreach($kpi as $key=>$value) { ?>
        <tr>
          <td><?php echo $value['desc']; ?></td>
          <td><?php echo $value['min']; ?></td>
          <td><?php echo $value['max']; ?></td>
          <td><?php echo $value['rate']; ?></td>
          <td><?php echo $value['comment']; ?></td>
        </tr>
        <?php } ?>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadPerformanceDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete Performance</a><input type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadPerformanceTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
