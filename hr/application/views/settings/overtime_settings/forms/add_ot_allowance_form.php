<style>
.textbox-list-employee .textboxlist-bits{height:70px;width:105%;}
.textboxlist{width:295px;display:inline-block;}
.ot-allowance-header{padding:8px;background-color: #D6D6D6;font-weight: bold;margin-top:14px;}
.list-day-type li{list-style: none; display: inline-block; margin:13px; }
</style>
<script>
$(function(){  
  //var jqAction  = jQuery.noConflict(); 
  $('#add_ot_allowance_form').validationEngine({scroll:false});   
  $('#add_ot_allowance_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){         
          $("#btn-add-ot-allowance-cancel").trigger('click');
          load_overtime_allowance_list_dt();
        }
        dialogOkBox(o.message,{});
        $('#token').val(o.token);
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Saving...');
        /*var total_selected_day_type = $("#add_ot_allowance_form").find('input.chk_day_type:checked').length;          
        if( total_selected_day_type <= 0 ){
          closeDialog('#' + DIALOG_CONTENT_HANDLER);      
          dialogOkBox("Please select atleast 1 day type",{});
          return false;
        }*/
      }
  });

  var t = new $.TextboxList('#employee_id', {unique: true,plugins: {
    autocomplete: {
      minLength: 2,
      onlyFromValues: true,
      queryRemote: true,
      remote: {url: base_url + 'autocomplete/ajax_get_employee_ot_allowance'}

    }
  }});

  var t = new $.TextboxList('#department_id', {unique: true,plugins: {
    autocomplete: {
      minLength: 2,
      onlyFromValues: true,
      queryRemote: true,
      remote: {url: base_url + 'autocomplete/ajax_get_department_ot_allowance'}

    }
  }});

  var t = new $.TextboxList('#employment_status_id', {unique: true,plugins: {
    autocomplete: {
      minLength: 2,
      onlyFromValues: true,
      queryRemote: true,
      remote: {url: base_url + 'autocomplete/ajax_get_employment_status_ot_allowance'}

    }
  }});

  $("#date_start").datepicker({
    dateFormat:"yy-mm-dd"
  });

  $("#btn-add-ot-allowance-cancel").click(function(){     
    $("#overtime-settings-container").show();
    $("#overtime-settings-form-container").hide();
    $('#overtime-settings-form-container').html('');
  });

  $("#all_employee").change(function(){
    if($(this).is(':checked') == true) {
      $(".txt-box-list").hide();
    }else{
      $(".txt-box-list").show();
    }
  });
  
});
</script>
<div id="formcontainer">
  <form id="add_ot_allowance_form" name="add_ot_allowance_form" autocomplete="off" method="POST" action="<?php echo url("overtime_settings/save_ot_allowance"); ?>">
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Add OT Allowance</h3>
        <div id="form_main">
          
            <div id="form_default"> 
               <p class="ot-allowance-header">Apply OT allowance to
               <label class="checkbox pull-right"><input type="checkbox" id="all_employee" name="all_employee" value="1" style="vertical-align:text-bottom;">All Employees</label>
               </p>     
               <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">
                  <tr class="txt-box-list">
                       <td style="width:15%" align="left" valign="middle">Employee</td>
                       <td class="textbox-list-employee" style="width:15%" align="left" valign="middle">
                        <input class="validate[required] text-input" type="text" name="employee_id" id="employee_id" value="" /> <br/>
                       </td>
                  </tr>
                  <tr class="txt-box-list">
                       <td style="width:15%" align="left" valign="middle">Department / Section</td>
                       <td class="textbox-list-employee" style="width:15%" align="left" valign="middle">
                        <input class="validate[required] text-input" type="text" name="department_id" id="department_id" value="" />
                       </td>
                  </tr>
                  <tr class="txt-box-list">
                       <td style="width:15%" align="left" valign="middle">Employment Status</td>
                       <td class="textbox-list-employee" style="width:15%" align="left" valign="middle">
                        <input class="validate[required] text-input" type="text" name="employment_status_id" id="employment_status_id" value="" />
                       </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle"></td>
                       <td class="textbox-list-employee" style="width:15%" align="left" valign="middle">
                       
                       </td>
                  </tr>
                </table>                  
                  <hr />
                  <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Apply to </td>
                       <td style="width:32%" align="left" valign="middle" class="form-inline">: 
                          <select name="day_type" class="validate[required]" id="day_type">
                            <option value="">- Select day type to apply -</option>
                            <?php foreach( $day_types as $key => $type ){ ?>
                            <option value="<?php echo $key; ?>" ><?php echo $type; ?></option>
                            <?php } ?>
                          </select>     
                       </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Add amount of </td>
                       <td style="width:32%" align="left" valign="middle" class="form-inline">: 
                          <div class="input-append">
                            <input class="validate[required,custom[number]] text-input" style="width:18%;height:18px;" type="text" name="ot_allowance" id="ot_allowance" value="" />                            
                            <span class="add-on" style="width:66px;">Pesos</span>
                          </div>
                       </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">For every  </td>
                       <td style="width:32%" align="left" valign="middle" class="form-inline">: 
                          <div class="input-append">
                            <input class="validate[required,custom[number]] text-input" type="text" style="width:18%;height:18px;" name="multiplier" id="multiplier" value="" />                         
                            <span class="add-on" style="width:66px;">OT hours</span>
                          </div>
                       </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Maximum of </td>
                       <td style="width:32%" align="left" valign="middle" class="form-inline">: 
                          <div class="input-append">
                            <input class="validate[required,custom[number]] text-input" type="text" style="width:18%;height:18px;" name="max_ot_allowance" id="max_ot_allowance" value="" />                       
                            <span class="add-on" style="width:66px;">Pesos a day</span>
                          </div>
                       </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Starts on </td>
                       <td style="width:15%" align="left" valign="middle">: 
                          <input class="validate[required] text-input" type="text" style="width:29%;" name="date_start" id="date_start" value="" />
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
                        <a id="btn-add-ot-allowance-cancel" href="javascript:void(0)">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div><!-- #form_main -->
    </div>
  </form>
</div>

