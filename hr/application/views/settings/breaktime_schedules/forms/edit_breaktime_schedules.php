<style>
.breaktime-textboxlists .textboxlist-bits{height:66px;width:107%;}
.textboxlist{width:310px;display:inline-block;}
.breaktime-header{background-color:#198CC9;color:#ffffff;padding:7px;margin-top:11px;}
/*.chk-deduct-working-hrs{display: inline-block;}*/
/*.icon-remove-circle{margin-left:2px;}*/
.remove-btn{margin-left:2px;padding:3px 4px;}
.breaktime-schedule-list{min-height: 100px;}
.day-type-options-container{width:78%;background-color:#e9e9e9;margin-top:17px;padding:7px;}
ul.day-type-options-list{list-style: none;}
ul.day-type-options-list li{margin:5px;display: inline-block; min-width: 153px;}
</style>
<script>
$(function(){  
  //var jqAction  = jQuery.noConflict(); 
  $('#edit_breaktime_schedule_form').validationEngine({scroll:false});   
  $('#edit_breaktime_schedule_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){ 
          hideAddBreaktimeSchedule();
          load_breaktime_schedules_dt();
        }
        dialogOkBox(o.message,{});
        $('#token').val(o.token);
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Saving...');
      }
  });
  
  $("#breaktime-start-date").datepicker({
    dateFormat:'yy-mm-dd',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true
  });
 
  $('.breaktime-picker').timepicker({
    'minTime': '8:00 am',
    'maxTime': '7:30 am',
    'timeFormat': 'g:i a'
  });   
  
  $("#btn-insert-breaktime-schedule").click(function(){
    var level     = $('.append-breaktime-schedule-list').length;  
    var new_level = level + 1;
    var new_element_id = "breaktime-schedule-" + new_level;
    $(".append-container").append("<div id='" + new_element_id + "'></div>");
    $('#' + new_element_id).html(loading_image);
    
    $.get(base_url + 'settings/_load_add_breaktime',{level:level},function(o) {
      $('#' + new_element_id).html(o);    
    });

  });

  $("#btn-breaktime-schedule-cancel").click(function(){     
    hideAddBreaktimeSchedule();
  });

});
</script>
<div id="formcontainer">
  <form id="edit_breaktime_schedule_form" name="edit_breaktime_schedule_form" autocomplete="off" method="POST" action="<?php echo url("settings/update_breaktime_schedule"); ?>">
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
  <input type="hidden" id="heid" name="heid" value="<?php echo $break_time_header['id']; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Edit Break Time Schedules</h3>
        <div id="form_main">
          
            <div id="form_default">      
               <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">
                  <tr>
                    <td style="width:27%" align="left" valign="middle">Applied to schedule</td>
                    <td style="width:17%" align="left" valign="middle">
                        <input type="text" style="width:75px;" name="schedule_in" readonly="readonly" id="schedule-in" class="validate[required] text-input" placeholder="Schedule In" value="<?php echo $break_time_header['formatted_schedule_in']; ?>" />
                        to 
                        <input type="text" style="width:75px;" name="schedule_out" readonly="readonly" id="schedule-out" class="validate[required] text-input" placeholder="Schedule Out" value="<?php echo $break_time_header['formatted_schedule_out']; ?>" />
                    </td>
                  </tr>  
                  <tr>
                    <td style="width:27%" align="left" valign="middle">Applied to </td>
                    <td style="width:17%" align="left" valign="middle">
                        <input type="text" style="width:177px;" id="applied_to-in" class="validate[required] text-input" readonly="readonly" value="<?php echo $break_time_header['applied_to']; ?>" />                        
                    </td>
                  </tr>   
                  <tr>
                      <td style="width:15%" align="left" valign="middle">Starts on </td>
                      <td style="width:52%" align="left" valign="middle" class="breaktime-textboxlists"> 
                        <input type="text" style="width:75px;" class="validate[required]" name="date_start" id="breaktime-start-date" value="<?php echo $break_time_header['date_start']; ?>" />
                      </td>
                  </tr>                                 
               </table>
               <p class="breaktime-header"><b>Break Time Schedules</b></p><a href="javascript:void(0);" id="btn-insert-breaktime-schedule" class="btn btn-default pull-right"><i class="icon-plus-sign"></i>Add break time schedule</a>

               <div class="breaktime-schedule-list">                 
                  <div class="append-container">
                      <?php 
                        $append_level = 0;
                        foreach( $break_time_details as $details ){
                          echo "<div id='breaktime-schedule-{$append_level}'>";
                          include('_edit_breaktime.php');
                          echo "</div>";
                          $append_level++; 
                        }
                      ?> 
                  </div>                                                     
               </div>               
            </div>            
            <div id="form_default" class="form_action_section">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                      <td class="field_label">&nbsp;</td>
                        <td>
                        <input type="submit" value="Save" class="curve blue_button" />
                        <a id="btn-breaktime-schedule-cancel" href="javascript:void(0)">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div><!-- #form_main -->
    </div>
  </form>
</div>

