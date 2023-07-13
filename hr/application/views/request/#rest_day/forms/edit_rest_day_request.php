<script>
	$(function() {	
		$("#start_date_edit").datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true,
			onSelect	:function() { $("#end_date_edit").datepicker('option',{minDate:$(this).datepicker('getDate')}); },	
		});	
		$("#end_date_edit").datepicker({
			dateFormat	: 'yy-mm-dd',
			onSelect	:function() { $("#start_date_edit").datepicker('option',{maxDate:$(this).datepicker('getDate')}); },
		});	
	});
</script>

<form id="edit_request_rest_day_form" name="edit_request_rest_day_form" method="POST" action="<?php echo url('request/_load_update_rest_day_request'); ?>">
<input type="hidden" id="request_type" name="request_type" value="<?php echo Utilities::encrypt(Settings_Request::RESTDAY); ?>" />
<input type="hidden" id="hid" name="hid" value="<?php echo Utilities::encrypt($rest_day_request->getId()); ?>" />

<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date_edit" name="start_date_edit" class="validate[required]" placeholder="From" value="<?php echo $rest_day_request->getDateStart(); ?>" readonly="readonly" />
                <input type="text" style="width:150px;" id="end_date_edit" name="end_date_edit" class="validate[required]" placeholder="To" value="<?php echo $rest_day_request->getDateEnd(); ?>" readonly="readonly" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><textarea id="reason" name="reason" style="height:100px; width:250px"><?php echo $rest_day_request->getRestDayComments(); ?></textarea></td>
          </tr>

        </table>        
	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeDialogBox('#edit_request_rest_day_form','#edit_request_rest_day_form')">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>

