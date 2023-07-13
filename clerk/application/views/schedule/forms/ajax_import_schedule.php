<form method="post" id="import_schedule_form" action="<?php echo $action;?>" enctype="multipart/form-data">	
<div id="form_main" class="inner_form popup_form">
	<div>
    	Select File: <br /><input type="file" name="import_schedule_file" id="import_schedule_file" />        
    </div><br />
	<div>
    	Effectivity Date:<br /><input type="text" name="date_start" id="date_start" value="<?php echo date('Y-m-d');?>" />
    </div>        
    <div align="right">
    	<small><a target="_blank" href="<?php echo url('schedule/html_show_import_format');?>">Need Help?</a></small>
    </div>
    <div id="form_default" class="form_action_section">
        <table class="no_border" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Import" id="import_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>

<script>
	$("#import_schedule_form #date_start").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>