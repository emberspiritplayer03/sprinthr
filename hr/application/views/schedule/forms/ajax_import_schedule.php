<form method="post" id="import_schedule_form" action="<?php echo $action;?>" enctype="multipart/form-data">	
<div id="form_main" class="inner_form popup_form wider">
	<div id="form_default">
    	<table width="100%">
        	<tr>
            	<td class="field_label">Select File:</td>
                <td><input type="file" name="import_schedule_file" id="import_schedule_file" /></td>
            </tr>
            <tr>
            	<td class="field_label">Start Date:</td>
                <td><input type="text" name="date_start" id="date_start" value="<?php echo date('Y-m-d');?>" /></td>
            </tr>
            <tr>
                <td class="field_label">End Date:</td>
                <td><input type="text" name="end_date" id="end_date" value="<?php echo date('Y-m-d');?>" /></td>
            </tr>
        </table>
    </div>
    <div class="import_links">
    	<small>
        <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/schedule/import_schedule_weekly_v2.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download template</a>&nbsp;<a target="_blank" href="<?php echo url('schedule/html_show_import_format');?>" class="btn btn-mini btn-link"><i class="icon-question-sign icon-fade"></i> Need Help?</a>
        </small>
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
	$("#import_schedule_form #date_start").datepicker({
        dateFormat:'yy-mm-dd',
        changeMonth:true,
        changeYear:true,
        showOtherMonths:true,
        onSelect: function(date){
           $("#import_schedule_form #end_date").datepicker('option',{minDate:$(this).datepicker('getDate')});
        }
    });

    $("#import_schedule_form #end_date").datepicker({
        dateFormat:'yy-mm-dd',
        changeMonth:true,
        changeYear:true,
        showOtherMonths:true
    });
</script>