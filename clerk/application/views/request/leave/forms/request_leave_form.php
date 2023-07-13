<script>
	$(function() {	
		$("#start_date").datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true,
			onSelect	:function() { $("#end_date").datepicker('option',{minDate:$(this).datepicker('getDate')}); },	
		});	
		$("#end_date").datepicker({
			dateFormat	: 'yy-mm-dd',
			onSelect	:function() { $("#start_date").datepicker('option',{maxDate:$(this).datepicker('getDate')}); },
		});	
	});
	
	$(function() {
		$("#request_leave_form").validationEngine({scroll:false});
		$('#request_leave_form').ajaxForm({
			success:function(o) {
				if(o.is_saved==1) {
					dialogOkBox('Successfully Added',{});
					cancel_request_leave_form();
					load_requested_leave_list_dt();
					$('.clearValue').val("");
					
				}else {
					dialogOkBox('Error on saving your request',{});	
				}
			},
			dataType:'json',
			beforeSubmit:function() {
				showLoadingDialog('Saving...');	
			}
		});
	});
</script>

<div id="formcontainer">
<div class="mtshad"></div>
<form id="request_leave_form" name="request_leave_form" method="POST" action="<?php echo url('request/insert_employee_leave'); ?>">
<input type="hidden" id="request_type" name="request_type" value="<?php echo Utilities::encrypt(Settings_Request::LEAVE); ?>" />
<input type="hidden" id="token" name="token" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Request Leave</h3>
<div id="form_main">
    <div id="form_default">
      <h3 class="section_title"></h3>
      <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:15%" align="left" valign="middle">Type :</td>
            <td style="width:85%" align="left" valign="middle">
                <select id="leave" name="leave" style="width:200px;" class="validate[required] clearValue">
                    <option value="" selected="selected"> -- Select Type -- </option>
                    <?php foreach($leave as $l): ?>
                        <option value="<?php echo Utilities::encrypt($l->getId()); ?>"><?php echo $l->getName(); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date" name="start_date" class="validate[required] clearValue" placeholder="From" />
                <input type="text" style="width:150px;" id="end_date" name="end_date" class="validate[required] clearValue" placeholder="To" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><textarea id="reason" name="reason" style="height:75px; width:250px" class="clearValue"></textarea></td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                    <input value="Save" class="curve blue_button" type="submit">&nbsp;
                    <a href="javascript:void(0);" onclick="javascript:cancel_request_leave_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div><!-- #formwrap -->
</form>
</div>

