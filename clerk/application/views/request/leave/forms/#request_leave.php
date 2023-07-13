<script>
	$(function() {	
		$("#start_date").datepicker({
			dateFormat	: 'yy-mm-dd',
			minDate		: '<?php echo date('Y-m-d'); ?>',
			autoSize  	: true,
			onSelect	:function() { $("#end_date").datepicker('option',{minDate:$(this).datepicker('getDate')}); },	
		});	
		$("#end_date").datepicker({
			dateFormat	: 'yy-mm-dd',
			minDate		: '<?php echo date('Y-m-d'); ?>',
			onSelect	:function() { $("#start_date").datepicker('option',{maxDate:$(this).datepicker('getDate')}); },
		});	
	});
</script>

<form id="request_leave_form" name="request_leave_form" method="POST" action="<?php echo url('request/insert_employee_leave'); ?>">
<input type="hidden" id="request_type" name="request_type" value="<?php echo Utilities::encrypt(Settings_Request::LEAVE); ?>" />

<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:15%" align="left" valign="middle">Type :</td>
            <td style="width:85%" align="left" valign="middle">
                <select id="leave" name="leave" style="width:200px;" class="validate[required]">
                    <option value="" selected="selected"> - Select - </option>
                    <?php foreach($leave as $l): ?>
                        <option value="<?php echo Utilities::encrypt($l->getId()); ?>"><?php echo $l->getName(); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date" name="start_date" class="validate[required]" placeholder="From" />
                <input type="text" style="width:150px;" id="end_date" name="end_date" class="validate[required]" placeholder="To" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><textarea id="reason" name="reason" style="height:75px; width:250px"></textarea></td>
          </tr>

        </table>        
	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeDialogBox('#request_leave_form_wrapper','#request_leave_form')">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>

