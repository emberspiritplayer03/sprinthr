<style>
.textbox-list-employee .textboxlist-bits{height:70px;width:105%;}
.textboxlist{width:295px;display:inline-block;}
.ot-allowance-header{padding:8px;background-color: #D6D6D6;font-weight: bold;margin-top:14px;}
.list-day-type li{list-style: none; display: inline-block; margin:13px; }
</style>
<script>
$(function(){  
  //var jqAction  = jQuery.noConflict(); 
  $('#add_ot_rate_form').validationEngine({scroll:false});   
  $('#add_ot_rate_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){         
          $("#btn-add-ot-rate-cancel").trigger('click');
          load_overtime_rate_list_dt();
        }
        dialogOkBox(o.message,{});
        $('#token').val(o.token);
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Saving...');       
      }
  });

  var t = new $.TextboxList('#employee_id', {unique: true,plugins: {
    autocomplete: {
      minLength: 2,
      onlyFromValues: true,
      queryRemote: true,
      remote: {url: base_url + 'autocomplete/ajax_valid_ot_rate_employees'}

    }
  }});
 
  $("#btn-add-ot-rate-cancel").click(function(){     
    $("#overtime-settings-container").show();
    $("#overtime-settings-form-container").hide();
    $('#overtime-settings-form-container').html('');
  });
  
});
</script>
<div id="formcontainer">
  <form id="add_ot_rate_form" name="add_ot_rate_form" autocomplete="off" method="POST" action="<?php echo url("overtime_settings/save_ot_rate"); ?>">
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Add OT Rate</h3>
        <div id="form_main">
          
            <div id="form_default"> 
               <p class="ot-allowance-header">Apply OT Rate to</p>     
               <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">
                  <tr class="txt-box-list">
                       <td style="width:15%" align="left" valign="middle">Employee</td>
                       <td class="textbox-list-employee" style="width:15%" align="left" valign="middle">
                        <input class="validate[required] text-input" type="text" name="employee_id" id="employee_id" value="" /> <br/>
                       </td>
                  </tr>                                   
                </table>                  
                  <hr />
                  <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">                  
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Rate per hour  </td>
                       <td style="width:32%" align="left" valign="middle" class="form-inline"> 
                          <div class="input-append">
                            <input class="validate[required,custom[number]] text-input" style="width:18%;height:18px;" type="text" name="ot_rate" id="ot_rate" value="" />                            
                            <span class="add-on" style="width:66px;">Pesos</span>
                          </div>
                       </td>
                  </tr>                 
               </table>
               
            </div>            
            <div id="form_default" class="form_action_section">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                      <td class="field_label">&nbsp;</td>
                        <td>
                        <input type="submit" value="Save" class="curve blue_button" />
                        <a id="btn-add-ot-rate-cancel" href="javascript:void(0)">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div><!-- #form_main -->
    </div>
  </form>
</div>

