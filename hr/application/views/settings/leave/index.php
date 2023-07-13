<style>
.general-rule-ul li{list-style: none;display: block;margin:21px;}
.general-rule-container{background-color: #D2D2D2;padding: 2px;border: 1px solid #aaaaaa;}
input[type="radio"] {margin-top: 0px;}
</style>
<script type="text/javascript">
$(function() {
	$("#tabs").tabs();
	load_leave_credit_list();

	$("#add_leave_credit_button_wrapper").click(function(){
		load_add_leave_credits();
	});

	$('#general_condition_form').ajaxForm({
		success:function(o) {
			if (o.is_updated == 1) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);				
				$("#message_container").html(o.message);
				$('#message_container').show();
				location.href = base_url + 'settings/leave'; 
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
});
</script>

<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br />

<div id="leave_credit_form_wrapper" style="display:none">
	<div id="leaveCreditFormsAjax"></div>
</div>  

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Credits</a></li>
        <li><a href="#tabs-2">General</a></li>
		
	</ul>    
	<div id="tabs-1">		
		<a class="blue_button" id="add_leave_credit_button_wrapper" href="javascript:void(0);"><strong>+</strong><b>Add Credit Condition</b></a>
		<br /><br />
    	<div id="c-info">  
    		<div id="AjaxLoadLeaveCreditContainer"></div> 	
       	</div>       	
	</div>
    
    <div id="tabs-2">
		<div id="c-structure">
		<form action="<?php echo $action_leave_general; ?>" method="post"  name="general_condition_form" id="general_condition_form" >
			<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" >
			<div class="general-rule-container">
				<ul class="general-rule-ul">
					<li>
						<label class="checkbox"><input type="radio" <?php echo $leave_general->getConvertLeaveCriteria() == G_Settings_Leave_General::CRITERIA_01 ? 'checked' : ''; ?> name="leave_criteria" value="<?php echo G_Settings_Leave_General::CRITERIA_01; ?>"> <?php echo G_Settings_Leave_General::CRITERIA_DESCRIPTION_01; ?></label>
					</li>
					<li>
						<label class="checkbox"><input type="radio" <?php echo $leave_general->getConvertLeaveCriteria() == G_Settings_Leave_General::CRITERIA_02 ? 'checked' : ''; ?> name="leave_criteria" value="<?php echo G_Settings_Leave_General::CRITERIA_02; ?>"> <?php echo G_Settings_Leave_General::CRITERIA_DESCRIPTION_02; ?></label>
					</li>
					<li>
						<label class="checkbox"><input type="radio" <?php echo $leave_general->getConvertLeaveCriteria() == G_Settings_Leave_General::CRITERIA_03 ? 'checked' : ''; ?> name="leave_criteria" value="<?php echo G_Settings_Leave_General::CRITERIA_03; ?>"> <?php echo G_Settings_Leave_General::CRITERIA_DESCRIPTION_03; ?></label>
					</li>
					<li>
						All leave credits will be deducted from 
	                      <select style="width:33%" name="leave_id" id="leave_id">
	                      		<option <?php echo $leave_general->getLeaveId() == "" ? 'selected' : ''; ?> value="">----</option>
	                        <?php foreach($leave_type as $leave) { ?>
	                        	<option <?php echo $leave_general->getLeaveId() == $leave->getId() ? 'selected' : ''; ?> value="<?php echo $leave->getId();?>"><?php echo $leave->getName();?></option>
	                        <?php } ?>
	                      </select> 	
					</li>
				</ul>
			</div>

	    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:none !important;">
	          <tr>
	            <td align="left" valign="top" style="border:none !important;"><input type="submit" value="Save" class="curve blue_button pull-right" /></td>
	          </tr>
	        </table>  

		</form>
		</div>
	</div>
</div>