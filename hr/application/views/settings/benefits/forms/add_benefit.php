<script>
$(function(){  
  $(".cmb-multiplied-by").prop("disabled", true);
  var jqAction  = jQuery.noConflict(); 
  $('#add_benefit_form').validationEngine({scroll:false});   
  $('#add_benefit_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){ 
          showBenefitsContainer();
          load_benefits_dt();
        }
        dialogOkBox(o.message,{});
        $('#token').val(o.token);
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Saving...');
      }
  });

  $("#btn-benefit-cancel").click(function(){     
    showBenefitsContainer();
  });

  $(".chk-multiplied-by").click(function(){
    if( $(this).is(":checked") ){
      $(".cmb-multiplied-by").prop("disabled", false);
    }else{
      $(".cmb-multiplied-by").prop("disabled", true);
    }
  });

});
</script>
<div id="formcontainer">
  <form id="add_benefit_form" name="add_benefit_form" autocomplete="off" method="POST" action="<?php echo url("settings/save_benefit"); ?>">
  <input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />
    <div id="formwrap"> 
      <h3 class="form_sectiontitle">Add Benefit</h3>
        <div id="form_main">
          
            <div id="form_default">      
               <table width="100%" border="0" cellspacing="1" cellpadding="2">
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Benefit Code</td>
                       <td style="width:15%" align="left" valign="middle">: 
                        <input class="validate[required,max[50]] text-input" type="text" name="benefit_code" id="benefit_code" value="" />
                       </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Benefit Name</td>
                       <td style="width:15%" align="left" valign="middle">: 
                          <input class="validate[required] text-input" type="text" name="benefit_name" id="benefit_name" value="" />
                        </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Benefit Amount</td>
                       <td style="width:15%" align="left" valign="middle" class="form-inline">: 
                          <input style="width:17%;margin-right:10px;" class="validate[required,custom[number]] text-input" type="text" name="benefit_amount" id="benefit_amount" value="" /><label class="checkbox"><input class="chk-multiplied-by" name="chk_multiplied_by" type="checkbox" />Multiplied by</label>
                          <select style="width:28%;" class="cmb-multiplied-by" name="multiplied_by_selected">
                            <?php foreach($multiplied_by as $key => $value){?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option> 
                            <?php } ?>
                          </select>
                        </td>
                  </tr>                  
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Is Taxable</td>
                       <td style="width:15%" align="left" valign="middle">: 
                          <select style="width:17%" name="is_taxable" id="is_taxable">
                            <option value="<?php echo $benefit_no; ?>"><?php echo $benefit_no; ?></option>
                            <option value="<?php echo $benefit_yes; ?>"><?php echo $benefit_yes; ?></option>
                          </select>
                        </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Given every</td>
                       <td style="width:15%" align="left" valign="middle">: 
                          <select style="width:30%" name="benefit_occurance" id="benefit_occurance">
                            <?php foreach( $occurance as $key => $value ){ ?>
                              <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                  </tr>
                  <tr>
                       <td style="width:15%" align="left" valign="middle">Benefit Description</td>
                       <td style="width:15%" align="left" valign="middle">: 
                          <textarea name="benefit_description" id="benefit_description"></textarea>
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
                        <a id="btn-benefit-cancel" href="javascript:void(0)">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div><!-- #form_main -->
    </div>
  </form>
</div>

