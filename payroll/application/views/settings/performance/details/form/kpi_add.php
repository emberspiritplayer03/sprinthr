<script>
$(function() {
$("#kpi_add_form").validationEngine({scroll:false});
$('#kpi_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			loadPerformanceKpi(<?php echo $details->id; ?>);
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});

});
</script>
<h2>Add Key Performance Indicator</h2>
<form id="kpi_add_form" name="form1" method="post" action="<?php echo url('settings/_update_kpi'); ?>">
<input type="hidden" name="performance_id" value="<?php echo $details->id; ?>" />

  <table class="table_form" width="476" border="0" cellpadding="3" cellspacing="3">
  	 <tr>
      <td width="156" align="right" valign="top">Title:</td>
      <td valign="top"><input type="text" name="title" id="title" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Description:</td>
      <td width="241" valign="top"><textarea class="validate[required]" name="description" id="description" cols="45" rows="5"><?php echo $details->answer; ?></textarea></td>
    </tr>
    <tr>

      <td colspan="2" align="left" valign="top">
      <table id="quick_choices_table" width="100%" border="1" style="display:none">
        <tr>
          <td colspan="2" align="center">*Please provide correct answer SAME with the answer above</td>
          </tr>
        <tr>
          <td width="40%" align="right">Choice :</td>
          <td width="60%">a) <input name="choice1" type="text" id="choice1" size="40" /></td>
        </tr>
        <tr>
          <td align="right">Choice :</td>
          <td>b) <input name="choice2" type="text" id="choice2" size="40" /></td>
        </tr>
        <tr>
          <td align="right">Choice :</td>
          <td>c) <input name="choice3" type="text" id="choice3" size="40" /></td>
        </tr>
        <tr>
          <td align="right">Choice :</td>
          <td>d) <input name="choice4" type="text" id="choice4" size="40" /> </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td valign="top"><input type="submit" name="button" id="button" value="Add" /> 
        <a href="javascript:void(0);" onclick="javascript:loadKpiTable();">Cancel</a></td>
    </tr>
  </table>
</form>
