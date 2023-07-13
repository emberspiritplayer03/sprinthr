<script>
$(function(){
    $("#date_from").datepicker({
        dateFormat:'yy-mm-dd',
        changeMonth:true,
        changeYear:true,
        showOtherMonths:true,
        onSelect  :function() {       
          $("#date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});            
        }
    });

    $("#date_to").datepicker({
        dateFormat:'yy-mm-dd',
        changeMonth:true,
        changeYear:true,
        showOtherMonths:true,
        onSelect  :function() {       
          $("#date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});            
        }
    });
});
</script>
<form method="post" id="attendance_sync" name="attendance_sync" action="<?php echo $action;?>">	
<div id="form_main" class="inner_form popup_form">
	<table>                      
        <tr>
          <td class="field_label">Date from:</td>
          <td>
            <input type="text" class="validate[required]" name="date_from" id="date_from" value="" />            
          </td>
        </tr>
        <tr>
          <td class="field_label">Date to:</td>
          <td>
            <input type="text" class="validate[required]" name="date_to" id="date_to" value="" />            
          </td>
        </tr>                        
     </table>
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Synchronize" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>