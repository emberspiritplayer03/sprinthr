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
<div id="form_main" class="employee_form">
<form id="kpi_add_form" name="form1" method="post" action="<?php echo url('settings/_update_kpi'); ?>">
<input type="hidden" name="performance_id" value="<?php echo $details->id; ?>" />
	<div id="form_default">
      <table width="100%">
         <tr>
          <td class="field_label">Title:</td>
          <td><input type="text" name="title" id="title" /></td>
        </tr>
        <tr>
          <td class="field_label">Description:</td>
          <td><textarea class="validate[required]" name="description" id="description"><?php echo $details->answer; ?></textarea></td>
        </tr>
       </table>
     </div>
     <div id="quick_choices_table" style="display:none;" class="no-padding">
         <div class="form_separator"></div>
         <div id="form_default">
          <table width="100%">
            <tr>
              <td colspan="3" align="center"><strong>*Please provide correct answer SAME with the answer above</strong></td>
              </tr>
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="17%" align="right" style="vertical-align:middle;">Choice :</td>
              <td width="78%">a) <input name="choice1" type="text" id="choice1" size="40" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td align="right" style="vertical-align:middle;">Choice :</td>
              <td>b) <input name="choice2" type="text" id="choice2" size="40" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td align="right" style="vertical-align:middle;">Choice :</td>
              <td>c) <input name="choice3" type="text" id="choice3" size="40" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td align="right" style="vertical-align:middle;">Choice :</td>
              <td>d) <input name="choice4" type="text" id="choice4" size="40" /> </td>
            </tr>
          </table>
        </div>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%">
        	<tr>
              <td class="field_label">&nbsp;</td>
              <td><input type="submit" name="button" id="button" value="Add" class="blue_button" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadKpiTable();">Cancel</a></td>
            </tr>
        </table>
    </div>
</form>
</div>
