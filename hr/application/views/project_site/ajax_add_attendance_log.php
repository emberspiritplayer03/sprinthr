<script>
$(document).ready(function() { 
   /* $("#time_in").change(function(){
         $("#time_out").timepicker('option',{'minTime':$(this).val()});
    });  */ 

    $('#time_in').timepicker({
        'minTime': '8:00 am',
        'maxTime': '7:30 am',
        'timeFormat': 'g:i a',           
    });

     $('#time_out').timepicker({
        'minTime': '8:00 am',
        'maxTime': '7:30 am',
        'timeFormat': 'g:i a'
    });

   /* $("#date_in").datepicker({
        dateFormat  : 'yy-mm-dd',
        autoSize    : true,
        minDate: '<?php echo $from_date ?>',
        maxDate: '<?php echo $to_date ?>',
        onSelect: function(date){
           $("#date_out").datepicker('option',{minDate:$(this).datepicker('getDate')});
        }
    }); */

    $("#date_in").datepicker({
        dateFormat  : 'yy-mm-dd',
        autoSize    : true  
    }); 

    /*$("#date_out").datepicker({
        dateFormat  : 'yy-mm-dd',
        autoSize    : true,
        minDate: '<?php echo $from_date ?>',
        maxDate: '<?php echo $to_date ?>',
    }); */

    $("#date_out").datepicker({
        dateFormat  : 'yy-mm-dd',
        autoSize    : true       
    }); 

    $("#date").datepicker({
        dateFormat  : 'yy-mm-dd',
        autoSize    : true       
    }); 

    $('#time').timepicker({
        'minTime': '8:00 am',
        'maxTime': '7:30 am',
        'timeFormat': 'g:i a'
    });

    var t = new $.TextboxList('#h_employee_id', {max:1,plugins: {
        autocomplete: {
            minLength: 2,
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}
        
        }
    }});    
});

</script>
<style>
ul.textboxlist-bits{width:220px;}
div.textboxlist{width:220px;display:inline-block;}
</style>
<div class="attendanceLogErr"></div>
<form method="post" id="add_attendance_log" name="add_attendance_log" action="<?php echo $action; ?>">
<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%">  
            <tr>
                <td class="field_label">Employee </td>
                <td>
                    <input type="text" class="validate[required] " name="h_employee_id" id="h_employee_id" value="" />
                </td>            
            </tr>
            <tr>
                <td colspan="2"><hr style="display:block;" /></td>
            </tr>       

          <!-- <tr>
            <td class="field_label">Date In</td>
            <td>
                <input type="text" class="validate[required] " name="date_in" id="date_in" value="" />
            </td>            
          </tr>
          <tr>
            <td class="field_label">Time In</td>
            <td>
                <input type="text" class="validate[required] " name="time_in" id="time_in" value="" />
            </td>            
          </tr>
                  
          <tr>
            <td class="field_label">Date Out</td>
            <td>
                <input type="text" class="validate[required] " name="date_out" id="date_out" value="" />
            </td>            
          </tr>
          <tr>
            <td class="field_label">Time Out</td>
            <td>
                <input type="text" class="validate[required] " name="time_out" id="time_out" value="" />
            </td>            
          </tr>           -->
                
            <tr>
                <td class="field_label">Date</td>
                <td>
                    <input type="text" class="validate[required] " name="date" id="date" value="" />
                </td>            
            </tr>
            <tr>
                <td class="field_label">Time</td>
                <td>
                    <input type="text" class="validate[required] " name="time" id="time" value="" />
                </td>            
            </tr>
                    
            <tr>
                <td class="field_label">Type</td>
                <td>
                    <select class="validate[required] select_option" name="type" id="type" >
                        <option value="" selected="selected">-- Select Type --</option>
                        <?php foreach($log_types as $key=>$log_type) { ?>
                            <option value="<?php echo $log_type; ?>"><?php echo strtoupper($log_type); ?></option>
                        <?php } ?>
                    </select>
                </td>            
            </tr> 

             <tr>
                <td class="field_label">Device Number</td>
                <td>
                    <select class="validate[required] select_option" name="remarks" id="remarks" >
                        <option value="" selected="selected">-- Select Device Number --</option>
                        <option value="Device No:--no device--">--no device--</option>
                        <?php foreach($devices as $key => $device) { ?>
                            <option value="<?php echo 'Device No:'.$device->machine_no; ?>"><?php echo $device->machine_no .' - '. $device->device_name; ?></option>
                        <?php } ?>
                    </select>
                </td>            
            </tr> 
            
        </table>
        <br />
        
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
            </tr>
        </table>
    </div><!-- #form_default -->
</div><!-- #form_main.inner_form -->        
</form>
