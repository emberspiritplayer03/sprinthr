<h2 class="field_title"><?php echo $title; ?></h2>
<script>
$(document).ready(function() {
$("#date_time_event").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true,minDate: 0, maxDate: '+1M +10D'});
	$("#applicant_interview_add_form").validationEngine({scroll:false});
	$('#applicant_interview_add_form').ajaxForm({
		success:function(o) {
			if(o==1) {
				dialogOkBox("Successfully Updated",{});	
				$("#application_history_wrapper").html('');
				loadPage('#application_history');
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
<div>
<?php
$date =  substr($history->date_time_event,0,10);
$htime = substr($history->date_time_event,11,18);
 ?>
<form id="applicant_interview_add_form"  action="<?php echo url('recruitment/_update_application_interview'); ?>" method="post"  name="applicant_examination_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="application_history_id" name="application_history_id" value="<?php echo $history->getId(); ?>"  />

<div id="form_main" class="employee_form"> 
    <div id="form_default">
      <table width="100%">
        <tr>
          <td class="field_label">Date:</td>
          <td><input name="date_time_event" type="text" class="text-input" id="date_time_event" value="<?php echo substr($history->date_time_event,0,10); ?>" /></td>
        </tr>
        <tr>
          <td class="field_label">Time:</td>
          <td>
          	<select class="validate[required] select_option" name="time" id="time" >
              <option value="">-- Select Time --</option>
             
               <?php foreach($time as $key=>$value) { ?>
               
                <?php $selected = ($value==$htime)? "selected='selected'": '' ; ?>
             	 <option <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php } ?>
      
              </select>
          </td>
        </tr>
        <tr>
          <td class="field_label">Interviewer:</td>
          <td>
          <div id="position_dropdown_wrapper">
            <input type="text" class="validate[required] text-input" name="hiring_manager_id" id="hiring_manager_id" />
          </div>
          </td>
        </tr>
        <tr>
          <td class="field_label">&nbsp;</td>
          <td>
          <div id="status_dropdown_wrapper">
            <textarea name="notes" id="notes" cols="45" rows="5"><?php echo $history->notes;  ?></textarea>
          </div>
          </td>
        </tr>
      </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" onclick="javascript:loadDeleteApplicationHistoryDialog('<?php echo Utilities::encrypt($history->getId()); ?>')" href="javascript:void(0);"><span class="delete"></span>Delete Application History
</a><input type="submit" value="Save Schedule" class="curve blue_button" /> <a href="javascript:void(0);" onclick="javascript:loadApplicationHistoryTable();">Cancel</a></td>
          </tr>
        </table>        
    </div>
</div>
</form>
</div>
<script>
var t = new $.TextboxList('#hiring_manager_id', {max:1,plugins: {
	autocomplete: {
		minLength: 3,
		onlyFromValues: true,
		queryRemote: true,
		remote: {url: base_url + 'recruitment/_autocomplete_load_scheduled_by'}
	
	}
}});

</script>
<?php if($a) { ?>
<script>
t.add('Entry',<?php echo $a->id ?>, '<?php echo $a->lastname. ', '. $a->firstname; ?>');
</script>
<?php 
}?>

