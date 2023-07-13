<style>
.add-on{height: 18px !important;width:43px !important;}
</style>
<form id="edit_ot_rate_form" name="edit_ot_rate_form" autocomplete="off" method="POST" action="<?php echo url("overtime_settings/update_ot_rate"); ?>">
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
  <input type="hidden" id="eid" name="eid" value="<?php echo Utilities::encrypt($or->getId());?>" >
    <div id="form_main" class="inner_form popup_form wider">     
        <div id="form_default">     
          
            <div id="form_default">                
               <table width="100%" border="0" cellspacing="1" cellpadding="2" id="table-form-container">
                  <tr class="txt-box-list">
                       <td style="width:15%" align="left" valign="middle">Employee Name</td>
                       <td class="textbox-list-employee" style="width:15%" align="left" valign="middle">
                        <input type="text" readonly="readonly" value="<?php echo $employee_name; ?>">
                       </td>
                  </tr>     
                  <tr class="txt-box-list">
                       <td style="width:15%" align="left" valign="middle">Rate per hour  </td>
                       <td style="width:32%" align="left" valign="middle" class="form-inline"> 
                          <div class="input-append">
                            <input class="validate[required,custom[number]] text-input" style="width:35%;height:18px;z-index:9999;" type="text" name="ot_rate" id="ot_rate" value="<?php echo number_format($or->getOtRate(),2); ?>" />                            
                            <span class="add-on">Pesos</span>
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

